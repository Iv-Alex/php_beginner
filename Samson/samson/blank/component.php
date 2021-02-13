<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

$arResult['DATE'] = '';

if (CModule::IncludeModule("iblock")) {
    $arResult['DATE'] = 'Ok';

//    $IBLOCK_ID = 5;
  //  $arFilter = array("IBLOCK_ID" => $IBLOCK_ID, "ACTIVE" => "Y");
    $arFilter = array("XML_ID" => "268", "ACTIVE" => "Y");
    $arSelect = array('ID', 'XML_ID', 'DETAIL_PAGE_URL');
    $my_elements = CIBlockElement::GetList(
        array("ID" => "ASC"),
        $arFilter,
        false,
        false,
        $arSelect
    );

    while ($ar_fields = $my_elements->GetNext()) {
        $arResult['DATE'] .= $ar_fields['ID'] . " " . $ar_fields['XML_ID'] . " " . $ar_fields['DETAIL_PAGE_URL'] . "<br>";
        //$arResult['DATE'] .= '0';
    }
};



$this->IncludeComponentTemplate();
?>