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
];
