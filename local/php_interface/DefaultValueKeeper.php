<?php

\Bitrix\Main\Loader::includeModule('iblock');

class DefaultValueKeeper
{
    private static int $reviewIBlockId;

    private static int $authorGroupId;

    private static int $userStatusIBlockId;

    private static int $canonicalIBlockId;

    public static function setDefaults(): void
    {
        self::setReviewIBlockId();
        self::setAuthorGroupId();
        self::setUserStatusIBlockId();
        self::setCanonicalIBlockId();
    }

    private static function setReviewIBlockId(): void
    {
        self::$reviewIBlockId = (\Bitrix\Iblock\IblockTable::getList([
            'filter' => [
                'CODE' => 'reviews'
            ],
            'select' => ['ID']
        ])->fetch())['ID'];
    }

    public static function getReviewIBlockId(): int
    {
        return self::$reviewIBlockId;
    }

    private static function setAuthorGroupId(): void
    {
        self::$authorGroupId = (\Bitrix\Main\GroupTable::getList([
            'filter' => ['STRING_ID' => 'review_authors'],
            'select' => ['ID']
        ])->fetch())['ID'];
    }

    public static function getAuthorGroupId(): int
    {
        return self::$authorGroupId;
    }

    private static function setUserStatusIBlockId(): void
    {
        self::$userStatusIBlockId = (\Bitrix\Iblock\IblockTable::getList([
            'filter' => ['CODE' => 'status'],
            'select' => ['ID']
        ]))->fetch()['ID'];
    }

    public static function getUserStatusIBlockId(): int
    {
        return self::$userStatusIBlockId;
    }

    private static function setCanonicalIBlockId(): void
    {
        self::$canonicalIBlockId = (\Bitrix\Iblock\IblockTable::getList([
            'filter' => [
                'CODE' => 'canonical'
            ],
            'select' => ['ID']
        ])->fetch())['ID'];
    }

    public static function getCanonicalIBlockId(): int
    {
        return self::$canonicalIBlockId;
    }
}
