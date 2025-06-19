<?php

class UserEventHandler
{
    public static function saveUserClassBeforeUpdate(&$arFields)
    {
        $classList = self::makeUserClassFieldsList();

        $currentUser = CUser::GetByID($arFields['ID'])->Fetch();

        $userOldClassName = $classList[$currentUser['UF_USER_CLASS']];
        $userNewClassName = $classList[$arFields['UF_USER_CLASS']];

        $GLOBALS['USER_CHANGES'] = [
            'OLD_USER_CLASS' => $userOldClassName,
            'NEW_USER_CLASS' => $userNewClassName,
        ];
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
        if ($GLOBALS['USER_CHANGES']['OLD_USER_CLASS'] != $GLOBALS['USER_CHANGES']['NEW_USER_CLASS']) {
            self::sendEmail();
        }

        return true;
    }

    public static function sendEmail(): void
    {
        $eventName = 'EX2_AUTHOR_INFO';

        $userOldClass = $GLOBALS['USER_CHANGES']['OLD_USER_CLASS'];
        $userNewClass = $GLOBALS['USER_CHANGES']['NEW_USER_CLASS'];

        AddMessage2Log($userOldClass);
        AddMessage2Log($userNewClass);


        $fields = [
            'OLD_USER_CLASS' => $userOldClass,
            'NEW_USER_CLASS' => $userNewClass,
        ];
        CEvent::Send($eventName, 's1', $fields);
        AddMessage2Log(CEvent::Send($eventName, 's1', $fields));

    }
}
