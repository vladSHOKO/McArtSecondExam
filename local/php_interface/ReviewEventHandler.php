<?php

use Bitrix\Main\Diag\FileLogger;

class ReviewEventHandler
{
    public static function onBeforeIBlockElementAddOrUpdateHandler(&$arFields): bool
    {
        //Проверка, что это инфоблок рецензии
        if ($arFields['IBLOCK_ID'] != 5) {
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
        if ($arFields['IBLOCK_ID'] != 5) {
            return true;
        }

        $element = CIBlockElement::GetByID($arFields['ID'])->Fetch();

        $oldAuthor = CIBlockElement::GetProperty($arFields['IBLOCK_ID'], $element['ID'], [], [], ['CODE' => 'AUTHOR'])->Fetch()['VALUE'];

        //Получение ID нового автора по изменяемому id свойства
        $newAuthorKey = key($arFields['PROPERTY_VALUES'][9]);
        $newAuthor = $arFields['PROPERTY_VALUES'][9][$newAuthorKey]['VALUE'];


        $GLOBALS['AUTHOR_CHANGES'] = [
            'OLD_AUTHOR' => $oldAuthor,
            'NEW_AUTHOR' => $newAuthor,
        ];

        return true;
    }

    public static function checkAuthorChangesAfterUpdate(&$arFields): bool
    {
        if ($arFields['IBLOCK_ID'] != 5) {
            return true;
        }

        if ($GLOBALS['AUTHOR_CHANGES']['OLD_AUTHOR'] !== $GLOBALS['AUTHOR_CHANGES']['NEW_AUTHOR']) {
            self::logAuthorChanges($arFields['IBLOCK_ID']);
        }

        return true;
    }

    public static function logAuthorChanges(string|int $reviewId): void
    {
        $oldAuthor = $GLOBALS['AUTHOR_CHANGES']['OLD_AUTHOR'];
        $newAuthor = $GLOBALS['AUTHOR_CHANGES']['NEW_AUTHOR'];

        CEventLog::Add(
            [
                'SEVERITY' => 'INFO',
                'AUDIT_TYPE_ID' => 'ex2-590',
                'DESCRIPTION' => "В рецензии {$reviewId} изменился автор с {$oldAuthor} на {$newAuthor}"

            ]
        );
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
}
