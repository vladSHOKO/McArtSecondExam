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

$arFilter = [
    'IBLOCK_ID' => $reviewIBlockId,
    'PROPERTY_PRODUCT' => $productIds,
    'PROPERTY_AUTHOR' => $validAuthorsId,
];
if (!empty($productIds)) {
    $res = CIBlockElement::GetList(["PROPERTY_PRODUCT" => "desc"],
        $arFilter,
        false,
        false,
        ['ID', 'NAME', 'PROPERTY_PRODUCT', 'PROPERTY_AUTHOR']);
}

$reviewsCount = 0;

while ($review = $res->Fetch()) {
    $reviews[$review['PROPERTY_PRODUCT_VALUE']][] = $review['NAME'];
    $reviewsCount++;
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
