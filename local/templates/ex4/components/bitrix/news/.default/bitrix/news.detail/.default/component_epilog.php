<?php

if (!empty($arResult['IBLOCK_ELEMENT_NAME'])) {
    global $APPLICATION;
    $APPLICATION->SetPageProperty('canonical', "<link rel= 'canonical' href='{$arResult['IBLOCK_ELEMENT_NAME']}'>");
}


