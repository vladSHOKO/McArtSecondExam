<?php

include 'ReviewAuthorChecker.php';
include 'UserUpdateChecker.php';
include 'UserInfoHandler.php';
include 'ElementIndexHandler.php';
include 'BuildGlobalMenuHandler.php';
const REVIEW_ID = 5;
const AUTHOR_GROUP_ID = 6;
const PUBLISHED_STATUS_ID = 35;
const UF_USER_CLASS_FIELD = 11;

const CONTENT_EDITORS = 5;

$eventManager = \Bitrix\Main\EventManager::getInstance();

$eventManager->addEventHandler('iblock', 'OnBeforeIBlockElementAdd', 'OnBeforeIBlockElementAddHandler');
$eventManager->addEventHandler('iblock', 'OnBeforeIBlockElementUpdate', 'OnBeforeIBlockElementAddHandler');

$eventManager->addEventHandler('iblock', 'OnBeforeIBlockElementUpdate', ['ReviewAuthorChecker', 'beforeAuthorUpdate']);
$eventManager->addEventHandler('iblock', 'OnAfterIBlockElementUpdate', ['ReviewAuthorChecker', 'afterAuthorUpdate']);

$eventManager->addEventHandler('main', 'OnBeforeUserUpdate', ['UserUpdateChecker', 'beforeUserUpdate']);
$eventManager->addEventHandler('main', 'OnAfterUserUpdate', ['UserUpdateChecker', 'afterUserUpdate']);

$eventManager->addEventHandler('main', 'OnSendUserInfo', ['UserInfoHandler', 'userInfoHandler']);

$eventManager->addEventHandler('search', 'BeforeIndex', ['ElementIndexHandler', 'elementIndexHandler']);

$eventManager->addEventHandler('main', 'OnBuildGlobalMenu', ['BuildGlobalMenuHandler', 'buildMenu']);

function OnBeforeIBlockElementAddHandler(&$arFields)
{
    if ($arFields['IBLOCK_ID'] !== REVIEW_ID) {
        return false;
    }

    $arFields['PREVIEW_TEXT'] = str_replace('#del#', '', $arFields['PREVIEW_TEXT']);

    if (strlen($arFields['PREVIEW_TEXT']) < 5) {
        CAdminMessage::ShowMessage(sprintf(GetMessage('PREVIEW_TEXT_LESS_SYMBOLS'), strlen($arFields['PREVIEW_TEXT'])));
        return false;
    }

    return true;
}

function Agent_ex_610()
{
    if (empty(COption::GetOptionString('main', 'agent_recent_start'))) {
        COption::SetOptionString('main', 'agent_recent_start', ConvertTimeStamp(time(), 'FULL'));
    }

    $previousStartTime = COption::GetOptionString('main', 'agent_recent_start');

    $validReviews = [];
    $reviews = CIBlockElement::GetList([], ['IBLOCK_ID' => REVIEW_ID, '>TIMESTAMP_X' => $previousStartTime]);
    while ($review = $reviews->Fetch()) {
        $validReviews[] =$review;
    }

    AddMessage2Log($validReviews);

    $reviewCount = count($validReviews);

    CEventLog::Add([
        'DESCRIPTION' => sprintf(GetMessage('AGENT'), $previousStartTime, $reviewCount),
        'AUDIT_TYPE_ID' => 'ex2_610'
    ]);

    return 'Agent_ex_610();';
}
