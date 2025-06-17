<?php

use Bitrix\Main\Diag\FileLogger;

class ReviewEventHandler
{
    public static function onBeforeIBlockElementAddOrUpdateHandler(&$arFields): bool
    {
        $newPreviewText = self::deletePlaceholder('#del#', $arFields['PREVIEW_TEXT']);
        $arFields['PREVIEW_TEXT'] = $newPreviewText;

        if (self::isShorter(5, $arFields['PREVIEW_TEXT'])) {
            CAdminMessage::ShowMessage('Текст анонса слишком короткий: ' . mb_strlen($arFields['PREVIEW_TEXT']));
            return false;
        }
        AddMessage2Log($arFields);
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

    public static function onAfterIBlockElementUpdate(&$arFields): bool
    {
        return true;
    }
}