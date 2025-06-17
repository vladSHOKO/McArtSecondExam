<?php

include 'ReviewEventHandler.php';

$eventManager = \Bitrix\Main\EventManager::GetInstance();

$eventManager->registerEventHandler('iblock', 'OnBeforeIBlockElementAdd', 'iblock', 'ReviewEventHandler', 'onBeforeIBlockElementAddOrUpdateHandler');
$eventManager->registerEventHandler('iblock', 'OnBeforeIBlockElementUpdate', 'iblock', 'ReviewEventHandler', 'onBeforeIBlockElementAddOrUpdateHandler');
