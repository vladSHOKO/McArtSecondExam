<?php

class UserInfoHandler
{
    public static function userInfoHandler(&$arParams)
    {
        $userClasses = UserUpdateChecker::prepareUserClasses();

        $userClass = CUser::GetByID($arParams['USER_FIELDS']['ID'])->Fetch()['UF_USER_CLASS'];

        $arParams['FIELDS']['CLASS'] = $userClasses[$userClass];
    }
}