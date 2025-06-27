<?php

class UserEventHandler
{
    private static ?string $oldUserClassName;

    private static ?string $newUserClassName;

    public static function saveUserClassBeforeUpdate(&$arFields): void
    {
        $classList = self::makeUserClassFieldsList();

        $currentUser = \Bitrix\Main\UserTable::getList([
            'select' => ['ID', 'NAME', 'UF_USER_CLASS'],
            'filter' => [
                'ID' => $arFields['ID'],
            ]
        ])->fetch();

        $userOldClassName = $classList[$currentUser['UF_USER_CLASS']];
        $userNewClassName = $classList[$arFields['UF_USER_CLASS']];

        self::$oldUserClassName = $userOldClassName;
        self::$newUserClassName = $userNewClassName;
    }

    public static function makeUserClassFieldsList(): array
    {
        $userFieldID = \Bitrix\Main\UserFieldTable::getList([
            'select' => ['ID', 'FIELD_NAME'],
            'filter' => ['FIELD_NAME' => 'UF_USER_CLASS']
        ])->fetch();

        $userFieldQuery = \Bitrix\Main\UserField\Types\EnumType::getList([
            'select' => ['*'],
            'filter' => ['USER_FIELD_ID' => $userFieldID['ID']]
        ]);

        $userFieldList = [];
        while ($userField = $userFieldQuery->fetch()) {
            $userFieldList[$userField['ID']] = $userField['VALUE'];
        }

        return $userFieldList;
    }

    public static function checkUserClassChangesAfterUpdate(&$arFields)
    {
        if (self::$oldUserClassName != self::$newUserClassName) {
            self::sendEmail();
            self::unsetOldAndNewUserClass();
        }

        return true;
    }

    public static function sendEmail(): void
    {
        $eventName = 'EX2_AUTHOR_INFO';

        $userOldClass = self::$oldUserClassName;
        $userNewClass = self::$newUserClassName;

        $fields = [
            'OLD_USER_CLASS' => $userOldClass,
            'NEW_USER_CLASS' => $userNewClass,
        ];
        CEvent::Send($eventName, 's1', $fields);
    }

    public static function onSendUserInfo(&$arParams)
    {
        $user = \Bitrix\Main\UserTable::getList([
            'select' => ['ID', 'UF_USER_CLASS'],
            'filter' => ['ID' => $arParams['FIELDS']['USER_ID']]
        ])->fetch();

        $userClassList = self::makeUserClassFieldsList();

        $arParams['FIELDS']['CLASS'] = $userClassList[$user['UF_USER_CLASS']];

        $arParams['FIELDS']['MESSAGE'] = 'TEST';

        CEvent::Send('USER_INFO', 's1', $arParams['FIELDS']);
    }

    private static function unsetOldAndNewUserClass(): void
    {
        self::$oldUserClassName = null;
        self::$newUserClassName = null;
    }
}
