<?php

class ReviewAuthorChecker
{
    private static $previousAuthor;

    public static function beforeAuthorUpdate(&$arFields)
    {
        $review = CIBlockElement::GetList([], ['ID' => $arFields['ID']], false, false, ['ID', 'PROPERTY_AUTHOR'])->Fetch();

        self::$previousAuthor = $review['PROPERTY_AUTHOR_VALUE'];

        return true;
    }

    public static function afterAuthorUpdate(&$arFields)
    {
        $review = CIBlockElement::GetList([], ['ID' => $arFields['ID']], false, false, ['ID', 'PROPERTY_AUTHOR'])->Fetch();

        if (self::$previousAuthor !== $review['PROPERTY_AUTHOR_VALUE']) {
            CEventLog::Add(['DESCRIPTION' => sprintf(GetMessage('CHANGED_AUTHOR'), $review['ID'], self::$previousAuthor, $review['PROPERTY_AUTHOR_VALUE'])]);
        }
    }
}
