<?php

include 'ReviewEventHandler.php';
include 'UserEventHandler.php';
include 'MenuEventHandler.php';
include 'DefaultValueKeeper.php';
include 'ReviewAgent.php';
include 'MailEventHandler.php';

DefaultValueKeeper::setDefaults();

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

//Задание ex2-620
$eventManager->registerEventHandler('main', 'OnSendUserInfo', 'main', 'UserEventHandler', 'onSendUserInfo');

//Задание ex2-630
$eventManager->registerEventHandler('search', 'BeforeIndex', 'search', 'ReviewEventHandler', 'addReviewTitleOnBeforeIndex');

//Задание ex2-190
$eventManager->addEventHandler('main', 'OnBuildGlobalMenu', ['MenuEventHandler', 'configAdminMenuForContentManager']);

//Задание ex2-51
$eventManager->addEventHandler('main', 'OnBeforeEventAdd', ['MailEventHandler', 'OnBeforeEventAddHandler']);
