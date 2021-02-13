<?php
#
# Компонент множественного добавления товаров в корзину
# Размещается в любом месте страницы в виде кнопки,
# вызывающей модальную форму подбора товаров по XML_ID
#

// TODO .gif
// TODO SEF mode
// TODO cache
// TODO param add count of lines in SELECT
// TODO param add timeout for ajax start (script.js)

if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

define('SMSN_PRESELECT_LIST_SIZE', '5');

$arResult['DATE'] = '';

// Компонент отработает только при успешном подключении модуля Инфоблоков
if (CModule::IncludeModule("iblock")) {
	// Выводим кнопку вызова формы подбора товаров

	// Блок формы подбора товаров

?>
	<div id="smsn-form-multiple-goods">
		<input type="text" id="smsn-xmlid" class="form-control col-2">
		<button type="button" id="smsn-btn-search" class="btn btn-primary">Проверить</button>
		<div id="smsn-preselect-list-container">
			<select id="smsn-preselect-list" class="custom-select" size="<?= SMSN_PRESELECT_LIST_SIZE ?>">
				<option class="is" selected>Open this select menu</option>
				<option class="is" value="1">One</option>
				<option class="is" value="2">Two</option>
				<option class="is" value="3">Three</option>
			</select>
		</div>
	</div>


<?php
	$arResult['DATE'] = 'Ok';

	//    $IBLOCK_ID = 5;
	//  $arFilter = array("IBLOCK_ID" => $IBLOCK_ID, "ACTIVE" => "Y");
	$arFilter = array("XML_ID" => "26%", "ACTIVE" => "Y");
	$arSelect = array('ID', 'XML_ID', 'NAME');
	$my_elements = CIBlockElement::GetList(
		array("ID" => "ASC"),
		$arFilter,
		false,
		false,
		$arSelect
	);

	while ($ar_fields = $my_elements->GetNext()) {
		$arResult['DATE'] .= $ar_fields['ID'] . " " . $ar_fields['XML_ID'] . " " . $ar_fields['NAME'] . "<br>";
		//$arResult['DATE'] .= '0';
	}
};

$this->IncludeComponentTemplate();
?>