<?php

class MailEventHandler
{
    public static function OnBeforeEventAddHandler(&$event, &$lid, &$arFields)
    {
        global $USER;
        if ($USER->IsAuthorized()) {
            $arFields['AUTHOR'] = "Пользователь авторизован: {$USER->GetID()} ({$USER->GetLogin()}) {$USER->GetFirstName()}, данные из формы: {$arFields['AUTHOR']}";
        } else {
            $arFields['AUTHOR'] = "Пользователь не авторизован, данные из формы: {$arFields['AUTHOR']}";
        }

        CEventLog::Add([
            'DESCRIPTION' => "Замена данных в отсылаемом письме – {$arFields['AUTHOR']}",
        ]);
    }
}