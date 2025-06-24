<?php

class ReviewEventHandler
{
    private static ?int $oldAuthor;

    private static ?int $newAuthor;

    public static function onBeforeIBlockElementAddOrUpdateHandler(&$arFields): bool
    {
        $reviewIBlockId = DefaultValueKeeper::getReviewIBlockId();

        if ($arFields['IBLOCK_ID'] != $reviewIBlockId) {
            return true;
        }

        $newPreviewText = self::deletePlaceholder('#del#', $arFields['PREVIEW_TEXT']);
        $arFields['PREVIEW_TEXT'] = $newPreviewText;

        if (self::isShorter(5, $arFields['PREVIEW_TEXT'])) {
            CAdminMessage::ShowMessage('Текст анонса слишком короткий: ' . mb_strlen($arFields['PREVIEW_TEXT']));
            return false;
        }

        return true;
    }

    private static function deletePlaceholder(string $placeholder, string $announcement): string
    {
        return str_replace($placeholder, '', $announcement);
    }

    private static function isShorter(int $length, string $announcement): bool
    {
        return mb_strlen($announcement) < $length;
    }

    public static function saveAuthorNameBeforeChange(&$arFields): bool
    {
        $reviewIBlockId = DefaultValueKeeper::getReviewIBlockId();

        if ($arFields['IBLOCK_ID'] != $reviewIBlockId) {
            return true;
        }

        $dataClass = \Bitrix\Iblock\Iblock::wakeUp(DefaultValueKeeper::getReviewIBlockId())->getEntityDataClass();
        $oneMoreElement = $dataClass::getList([
            'select' => ['ID', 'NAME', 'AUTHOR.VALUE'],
            'filter' => ['ID' => $arFields['ID'], 'ACTIVE' => 'Y'],
        ])->fetch();

        $oldAuthor = (int)$oneMoreElement['IBLOCK_ELEMENTS_ELEMENT_REVIEWS_AUTHOR_VALUE'];

        //Получение ID нового автора по изменяемому id свойства
        $newAuthorKey = key($arFields['PROPERTY_VALUES'][9]);
        $newAuthor = $arFields['PROPERTY_VALUES'][9][$newAuthorKey]['VALUE'];

        self::$oldAuthor = $oldAuthor;
        self::$newAuthor = $newAuthor;

        return true;
    }

    public static function checkAuthorChangesAfterUpdate(&$arFields): bool
    {
        $reviewIBlockId = DefaultValueKeeper::getReviewIBlockId();

        if ($arFields['IBLOCK_ID'] != $reviewIBlockId) {
            return true;
        }

        if (self::$oldAuthor !== self::$newAuthor) {
            self::logAuthorChanges($arFields['ID']);
        }

        return true;
    }

    public static function logAuthorChanges(string|int $reviewId): void
    {
        $oldAuthor = self::$oldAuthor;
        $newAuthor = self::$newAuthor;

        CEventLog::Add(
            [
                'SEVERITY' => 'INFO',
                'AUDIT_TYPE_ID' => 'ex2-590',
                'DESCRIPTION' => "В рецензии {$reviewId} изменился автор с {$oldAuthor} на {$newAuthor}"

            ]
        );
        self::unsetOldAndNewAuthor();
    }

    public static function addReviewTitleOnBeforeIndex($arFields): array
    {
        if ($arFields['MODULE_ID'] != 'iblock' || $arFields['PARAM1'] != 'ex2') {
            return $arFields;
        }

        $requestProperty = CIBlockElement::GetProperty(5, $arFields['ITEM_ID']);

        $reviewProperties = [];

        while ($result = $requestProperty->Fetch()) {
            $reviewProperties[$result['ID']] = $result['VALUE'];
        }

        $authorID = $reviewProperties[9];

        $classList = UserEventHandler::makeUserClassFieldsList();

        $requestAuthorProperty = CUser::GetByID($authorID)->Fetch();

        $userClassName = $classList[$requestAuthorProperty['UF_USER_CLASS']];
        if (!empty($userClassName)) {
            $arFields['TITLE'] .= ". Класс: {$userClassName}";
        }

        return $arFields;
    }

    private static function unsetOldAndNewAuthor(): void
    {
        self::$oldAuthor = null;
        self::$newAuthor = null;
    }
}
