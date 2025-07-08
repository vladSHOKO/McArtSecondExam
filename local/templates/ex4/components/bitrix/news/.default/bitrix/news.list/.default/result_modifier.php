<?php

if ($arParams['DISPLAY_SPECIALDATE'] === 'Y') {

    $news = \Bitrix\Iblock\ElementTable::getList([
        'filter' => ['IBLOCK_ID' => DefaultValueKeeper::getNewsIBlockId()],
        'select' => ['*'],
        'order' => ['ID' => 'DESC'],
    ])->fetch()['ACTIVE_FROM']->format('d.m.Y');

    $arResult['NEWS_DATE'] = $news;

    $this->__component->SetResultCacheKeys(['NEWS_DATE']);
}
