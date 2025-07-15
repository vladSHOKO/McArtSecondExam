<?php

class UserUpdateChecker
{
    private static $previousUserClass;

    public static function prepareUserClasses(): array
    {
        $userClasses = [];
        $values = CUserFieldEnum::GetList([], ['USER_FIELD_ID' => UF_USER_CLASS_FIELD]);
        while ($value = $values->Fetch()) {
            $userClasses[$value['ID']] = $value['VALUE'];
        }

        return $userClasses;
    }

    public static function beforeUserUpdate(&$arFields)
    {
        $userClasses = self::prepareUserClasses();

        $userClass = CUser::GetByID($arFields['ID'])->Fetch()['UF_USER_CLASS'];

        self::$previousUserClass = $userClasses[$userClass];
    }

    public static function afterUserUpdate(&$arFields)
    {
        $userClasses = self::prepareUserClasses();

        if (self::$previousUserClass !== $userClasses[$arFields['UF_USER_CLASS']]) {
            CEvent::Send(
                'EX2_AUTHOR_INFO',
                's1',
                [
                    'OLD_USER_CLASS' => self::$previousUserClass,
                    'NEW_USER_CLASS' => $userClasses[$arFields['UF_USER_CLASS']]
                ]
            );
        }
    }
}