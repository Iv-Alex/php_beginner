<?php
#components/bitrix/example/ajax.php

if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

CModule::IncludeModule("iblock");

class CGoods extends \Bitrix\Main\Engine\Controller
{

	// Обязательный метод
	public function configureActions()
	{
		// Сбрасываем фильтры по-умолчанию (ActionFilter\Authentication и ActionFilter\HttpMethod)
		// Предустановленные фильтры находятся в папке /bitrix/modules/main/lib/engine/actionfilter/
		return [
			'getGoodsList' => [ // Ajax-метод
				'prefilters' => [],
			],
		];
	}
	/**
	 * 
	 */

	public function getGoodsListAction($xmlId = 'none')
	{
		$arItems = array();
		$arFilter = array('XML_ID' => "%{$xmlId}%", "ACTIVE" => "Y");
		$arSelect = array('ID', 'XML_ID', 'NAME');
		$goods = CIBlockElement::GetList(
			array("ID" => "ASC"),
			$arFilter,
			false,
			false,
			$arSelect
		);
		while ($arFields = $goods->GetNext()) {
			$arItems[] = array(
				'ID' => $arFields['ID'],
				'XML_ID' => $arFields['XML_ID'],
				'NAME' => $arFields['NAME']
			);
		}
		return $arItems;
	}
}
