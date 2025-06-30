<?php
//Изменение placeholder в мета теге ex2_meta
$metaValue = $APPLICATION->GetMeta('ex2_meta');
$reviewsCount = $arResult['REVIEW_COUNT'];
if (strpos($metaValue, '#count#')) {
    $APPLICATION->SetPageProperty('ex2_meta', 'ex2 ' . $reviewsCount);
}

if (!empty($arResult['FIRST_REVIEW_TITLE'])) {
    $APPLICATION->AddViewContent('additionalContent', '<div id="filial-special" class="information-block">
                    <div class="top"></div>
                    <div class="information-block-inner">
                        <h3>' . GetMessage("ADDITIONAL") . '</h3>
                        <div class="special-product">
                            <div class="special-product-title">
                                ' . $arResult['FIRST_REVIEW_TITLE'] . '
                            </div>
                        </div>
                    </div>
                    <div class="bottom"></div>
                </div>');
}
