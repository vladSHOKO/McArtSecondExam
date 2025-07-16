<?php


if (!empty($arParams['IBLOCK_ID_FOR_LINK'])) {
    $element = \Bitrix\Iblock\ElementTable::getList([
        'filter' => ['ID' => $arParams['IBLOCK_ID_FOR_LINK'], 'IBLOCK_ID' => DefaultValueKeeper::getCanonicalIBlockId()],
        'select' => ['NAME', 'ID']
    ])->fetch();
}

if (!empty($element)) {
    $arResult['IBLOCK_ELEMENT_NAME'] = $element['NAME'];
    $this->__component->SetResultCacheKeys(['IBLOCK_ELEMENT_NAME']);
}