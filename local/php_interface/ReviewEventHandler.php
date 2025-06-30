<?php

class ReviewEventHandler
{
    private static ?int $oldAuthor;

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

        self::$oldAuthor = $oldAuthor;

        return true;
    }

    public static function checkAuthorChangesAfterUpdate(&$arFields): bool
    {
        $reviewIBlockId = DefaultValueKeeper::getReviewIBlockId();

        if ($arFields['IBLOCK_ID'] != $reviewIBlockId) {
            return true;
        }

        //Получение ID нового автора по изменяемому id свойства
        $newAuthorKey = key($arFields['PROPERTY_VALUES'][9]);
        $newAuthor = $arFields['PROPERTY_VALUES'][9][$newAuthorKey]['VALUE'];

        if (self::$oldAuthor !== $newAuthor) {
            self::logAuthorChanges($arFields['ID'], $newAuthor);
        }

        return true;
    }

    public static function logAuthorChanges(string|int $reviewId, string|int $newAuthor): void
    {
        $oldAuthor = self::$oldAuthor;

        CEventLog::Add(
            [
                'SEVERITY' => 'INFO',
                'AUDIT_TYPE_ID' => 'ex2-590',
                'DESCRIPTION' => "В рецензии {$reviewId} изменился автор с {$oldAuthor} на {$newAuthor}"

            ]
        );
        self::unsetOldAuthor();
    }

    public static function addReviewTitleOnBeforeIndex($arFields): array
    {
        if ($arFields['MODULE_ID'] != 'iblock' || $arFields['PARAM1'] != 'ex2') {
            return $arFields;
        }

        $classList = UserEventHandler::makeUserClassFieldsList();

        $dataClass = \Bitrix\Iblock\Iblock::wakeUp(DefaultValueKeeper::getReviewIBlockId())->getEntityDataClass();
        $element = $dataClass::getList([
            'select' => ['ID', 'AUTHOR.VALUE'],
            'filter' => [
                'ID' => $arFields['ITEM_ID'],
            ]
        ])->fetch();

        $user = \Bitrix\Main\UserTable::getList([
            'select' => ['ID', 'UF_USER_CLASS'],
            'filter' => [
                'ID' => (int)$element['IBLOCK_ELEMENTS_ELEMENT_REVIEWS_AUTHOR_VALUE'],
            ]
        ])->fetch();

        $userClassName = $classList[$user['UF_USER_CLASS']];

        if (!empty($userClassName)) {
            $arFields['TITLE'] .= ". Класс: {$userClassName}";
        }

        return $arFields;
    }

    private static function unsetOldAuthor(): void
    {
        self::$oldAuthor = null;
    }
}
