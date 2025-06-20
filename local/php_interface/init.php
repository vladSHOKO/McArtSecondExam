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

<<<<<<< HEAD
//Задание ex2-630
$eventManager->registerEventHandler('search', 'BeforeIndex', 'search', 'ReviewEventHandler', 'addReviewTitleOnBeforeIndex');

//Задание ex2-610
CAgent::AddAgent("Agent_ex_610();", '', 'Y', '20');

function Agent_ex_610() {

    $recentStart = COption::GetOptionString("main", "agent_recent_start");
    $currentTime = ConvertTimeStamp(time(), "FULL");

    if (empty($recentStart)) {
        AddMessage2Log('Агент сработал, но без первой даты');
        COption::SetOptionString("main", 'agent_recent_start', $currentTime);
        return "Agent_ex_610();";
    }

    $arFilter = [
        'ACTIVE' => 'Y',
        'IBLOCK_ID' => 5, //ID рецензий
    ];

    $res = CIBlockElement::GetList([], $arFilter);

    AddMessage2Log($res->Fetch());

    return "Agent_ex_610();";
}
=======
//Задание ex2-620
$eventManager->registerEventHandler('main', 'OnSendUserInfo', 'main', 'UserEventHandler', 'onSendUserInfo');
>>>>>>> Task-ex2-620
