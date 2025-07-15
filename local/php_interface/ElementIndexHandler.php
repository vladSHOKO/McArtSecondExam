<?php

class ElementIndexHandler
{
    public static function elementIndexHandler($arFields)
    {

        if ($arFields['MODULE_ID'] !== 'iblock' || $arFields['PARAM2'] != REVIEW_ID) {
            return $arFields;
        }

        $review = CIBlockElement::GetList([], ['ID' => $arFields['ITEM_ID']], false, false, ['PROPERTY_AUTHOR', 'ID'])->Fetch();

        $author = CUser::GetByID($review['PROPERTY_AUTHOR_VALUE'])->Fetch();

        $authorClassID = $author['UF_USER_CLASS'];

        $authorClasses = UserUpdateChecker::prepareUserClasses();

        $arFields['TITLE'] .= sprintf(GetMessage('ELEMENT_TITLE'), $authorClasses[$authorClassID]);

        return $arFields;
    }
}