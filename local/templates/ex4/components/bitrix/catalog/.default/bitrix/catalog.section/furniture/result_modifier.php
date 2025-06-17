<?php

if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
    die();
}

$productIds = array_column($arResult['ITEMS'], 'ID');

$reviews = [];

$arFilter = [
    'IBLOCK_ID' => 5, // ID инфоблока рецензий
    'PROPERTY_PRODUCT' => $productIds,
    'PROPERTY_AUTHOR.STATUS' => 'Публикуется',
    'PROPERTY_AUTHOR.GROUP_ID' => 6 // ID группы "Авторы рецензий"
];
if (!empty($productIds)) {
    $res = CIBlockElement::GetList(["ID" => "ASC"], $arFilter, false, false, ['ID', 'NAME', 'PROPERTY_PRODUCT', 'PROPERTY_AUTHOR']);
}

$reviewsCount = 0;

while ($review = $res->Fetch()) {
    $reviews[$review['PROPERTY_PRODUCT_VALUE']][] = $review['NAME'];
    $reviewsCount++;
}

if ($reviewsCount != 0) {
    //Изменение placeholder в мета теге ex2_meta
    $metaValue = $APPLICATION->GetPageProperty('ex2_meta');
    $APPLICATION->SetPageProperty('ex2_meta', 'ex2 ' . $reviewsCount);

    $firstReview = reset($reviews);
    $firstReviewTitle = $firstReview[key($firstReview)];

    $APPLICATION->AddViewContent('additionalContent', '<div id="filial-special" class="information-block">
                    <div class="top"></div>
                    <div class="information-block-inner">
                        <h3>' . GetMessage("ADDITIONAL") . '</h3>
                        <div class="special-product">
                            <div class="special-product-title">
                                ' . $firstReviewTitle . '
                            </div>
                        </div>
                    </div>
                    <div class="bottom"></div>
                </div>');
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

    if (!empty($firstReview)) {
        $arResult['FIRST_REVIEW'] = $firstReview;
    }
}
