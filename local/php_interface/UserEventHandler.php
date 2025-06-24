<?php

class UserEventHandler
{
    private static ?string $oldUserClassName;

    private static ?string $newUserClassName;

    public static function saveUserClassBeforeUpdate(&$arFields)
    {
        $classList = self::makeUserClassFieldsList();

        $currentUser = CUser::GetByID($arFields['ID'])->Fetch();

        $userOldClassName = $classList[$currentUser['UF_USER_CLASS']];
        $userNewClassName = $classList[$arFields['UF_USER_CLASS']];

        self::$oldUserClassName = $userOldClassName;
        self::$newUserClassName = $userNewClassName;
    }

    public static function makeUserClassFieldsList(): array
    {
        $arFilter = [
            'USER_FIELD_ID' => 11 //ID пользовательского поля с классами
        ];

        $userField = CUserFieldEnum::GetList([], $arFilter);

        $userFieldList = [];

        while ($arField = $userField->Fetch()) {
            $userFieldList[$arField['ID']] = $arField['VALUE'];
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
        $user = CUser::GetByID($arParams['FIELDS']['USER_ID'])->Fetch();

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
