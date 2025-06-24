<?php

if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
    die();
}

$productIds = array_column($arResult['ITEMS'], 'ID');

$reviews = [];

$reviewIBlockId = DefaultValueKeeper::getReviewIBlockId();

$authorGroupId = DefaultValueKeeper::getAuthorGroupId();

$users = \Bitrix\Main\UserTable::getList([
    'filter' => [
        '=UF_AUTHOR_STATUS' => '35', //Значение "Публикуется"
        "Bitrix\Main\UserGroupTable:USER.GROUP_ID" => $authorGroupId
    ],
    'select' => [
        'ID'
    ]
])->fetchAll();

$validAuthorsId = array_column($users, 'ID');

$reviewsCount = 0;

if (!empty($productIds)) {
    $dataClass = \Bitrix\Iblock\Iblock::wakeUp(DefaultValueKeeper::getReviewIBlockId())->getEntityDataClass();
    $res = $dataClass::getList([
        'select' => ['ID', 'NAME', 'PRODUCT.VALUE', 'AUTHOR.VALUE'],
        'filter' => [
            'IBLOCK_ID' => $reviewIBlockId,
            'AUTHOR.VALUE' => $validAuthorsId,
            'PRODUCT.VALUE' => $productIds,
        ],
        'order' => ['PRODUCT.VALUE' => 'desc']
    ]);

    while ($review = $res->fetch()) {
        $reviews[(int)$review['IBLOCK_ELEMENTS_ELEMENT_REVIEWS_PRODUCT_VALUE']][] = $review['NAME'];
        $reviewsCount++;
    }
}

if ($reviewsCount != 0) {
    $firstReview = reset($reviews);
    $firstReviewTitle = $firstReview[key($firstReview)];
}
foreach ($arResult['ITEMS'] as $key => $arItem) {
    $arItem['PRICES']['PRICE']['PRINT_VALUE'] = number_format(
        (float)$arItem['PRICES']['PRICE']['PRINT_VALUE'],
        0,
        '.',
        ' '
    );
    $arItem['PRICES']['PRICE']['PRINT_VALUE'] .= ' ' . $arItem['PROPERTIES']['PRICECURRENCY']['VALUE_ENUM'];

    $arItem['REVIEWS'] = $reviews[$arItem['ID']] ?? [];

    $arResult['ITEMS'][$key] = $arItem;


}
if (!empty($firstReviewTitle)) {
    $arResult['FIRST_REVIEW_TITLE'] = $firstReviewTitle;
    $this->__component->SetResultCacheKeys(['FIRST_REVIEW_TITLE']);
}
$arResult['REVIEW_COUNT'] = $reviewsCount;
$this->__component->SetResultCacheKeys(['REVIEW_COUNT']);
