<?php

class UserEventHandler
{
    private static ?string $oldUserClassName;

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

        self::$oldUserClassName = $userOldClassName;
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
        $classList = self::makeUserClassFieldsList();

        $newUserClassName = $classList[$arFields['UF_USER_CLASS']];

        if (self::$oldUserClassName !== $newUserClassName) {
            self::sendEmail($newUserClassName);
        }
        self::unsetOldUserClass();

        return true;
    }

    public static function sendEmail(string $newUserClass): void
    {
        $eventName = 'EX2_AUTHOR_INFO';

        $oldUserClass = self::$oldUserClassName;

        $fields = [
            'OLD_USER_CLASS' => $oldUserClass,
            'NEW_USER_CLASS' => $newUserClass,
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

        CEvent::Send('USER_INFO', 's1', $arParams['FIELDS']);
    }

    private static function unsetOldUserClass(): void
    {
        self::$oldUserClassName = null;
    }
}
