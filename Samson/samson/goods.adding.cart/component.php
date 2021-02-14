<?php
#
# Компонент множественного добавления товаров в корзину
# Размещается в любом месте страницы в виде кнопки,
# вызывающей модальную форму подбора товаров по XML_ID
#

// TODO .gif
// TODO SEF mode
// TODO cache
// TODO param add count of lines in PRESELECT for scrolling (overflow)
// TODO param add timeout for ajax start (script.js)
// TODO add price and summ
// TODO vue elements adding

if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

$arResult['DATE'] = '';

// Компонент отработает только при успешном подключении модуля Инфоблоков
if (CModule::IncludeModule("iblock")) {
	// Выводим кнопку вызова формы подбора товаров

	// Блок формы подбора товаров

?>
	<div id="smsn-form-multiple-goods">
		<div class="form-row align-items-center">
			<div class="col">
				<input type="text" id="smsn-input-xmlid" class="form-control" placeholder="<?= GetMessage("SMSN_XML_ID_INPUT_PLACEHOLDER") ?>">
			</div>
			<div class="col">
				<button type="button" id="smsn-btn-add-goods-to-cart" class="btn btn-primary"><?= GetMessage("SMSN_ADD_TO_CART") ?></button>
			</div>
		</div>
		<div class="form-check">
			<input class="form-check-input" type="checkbox" id="smsn-chbox-xmlid-clear">
			<label class="form-check-label" for="smsn-chbox-xmlid-clear">
				<?= GetMessage("SMSN_XML_ID_INPUT_CLEAR_CAPTION") ?>
			</label>
		</div>
		<div id="smsn-preselect-list" class="list-group"></div>
		<div id="smsn-goods-for-adding"></div>
	</div>

<?php

};

$this->IncludeComponentTemplate();
?>