<?php

include 'ReviewEventHandler.php';

$eventManager = \Bitrix\Main\EventManager::GetInstance();

//Первая часть задания ex2-590
$eventManager->registerEventHandler('iblock', 'OnBeforeIBlockElementAdd', 'iblock', 'ReviewEventHandler', 'onBeforeIBlockElementAddOrUpdateHandler');
$eventManager->registerEventHandler('iblock', 'OnBeforeIBlockElementUpdate', 'iblock', 'ReviewEventHandler', 'onBeforeIBlockElementAddOrUpdateHandler');

//Вторая часть задания x2-590
$eventManager->registerEventHandler('iblock', 'OnBeforeIBlockElementUpdate', 'iblock', 'ReviewEventHandler', 'saveAuthorNameBeforeChange');
$eventManager->registerEventHandler('iblock', 'OnAfterIBlockElementUpdate', 'iblock', 'ReviewEventHandler', 'checkAuthorChangesAfterUpdate');
