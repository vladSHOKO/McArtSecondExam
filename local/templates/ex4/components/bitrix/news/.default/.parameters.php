<?php

if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
    die();
}

$arTemplateParameters = [

    "DISPLAY_SPECIALDATE" => array(
        "NAME" => GetMessage("SET_SPECIALDATE"),
        "TYPE" => "CHECKBOX",
        "DEFAULT" => "N",
    ),
    'IBLOCK_ID_FOR_LINK' => [
        'NAME' => GetMessage('IBLOCK_ID_FOR_CANONICAL'),
        'TYPE' => 'STRING',
    ],
];
