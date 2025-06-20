<?php

include 'ReviewEventHandler.php';
include 'UserEventHandler.php';

$eventManager = \Bitrix\Main\EventManager::GetInstance();

//Первая часть задания ex2-590
$eventManager->registerEventHandler('iblock', 'OnBeforeIBlockElementAdd', 'iblock', 'ReviewEventHandler', 'onBeforeIBlockElementAddOrUpdateHandler');
$eventManager->registerEventHandler('iblock', 'OnBeforeIBlockElementUpdate', 'iblock', 'ReviewEventHandler', 'onBeforeIBlockElementAddOrUpdateHandler');

//Вторая часть задания ex2-590
$eventManager->registerEventHandler('iblock', 'OnBeforeIBlockElementUpdate', 'iblock', 'ReviewEventHandler', 'saveAuthorNameBeforeChange');
$eventManager->registerEventHandler('iblock', 'OnAfterIBlockElementUpdate', 'iblock', 'ReviewEventHandler', 'checkAuthorChangesAfterUpdate');

//Задание ex2-600
$eventManager->registerEventHandler('main', 'OnBeforeUserUpdate', 'main', 'UserEventHandler', 'saveUserClassBeforeUpdate');
$eventManager->registerEventHandler('main', 'OnAfterUserUpdate', 'main', 'UserEventHandler', 'checkUserClassChangesAfterUpdate');

//Задание ex2-630
$eventManager->registerEventHandler('search', 'BeforeIndex', 'search', 'ReviewEventHandler', 'addReviewTitleOnBeforeIndex');
