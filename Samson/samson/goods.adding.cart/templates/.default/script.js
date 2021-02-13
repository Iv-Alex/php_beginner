
"use strict";

/*
* BX.bind(document, "keypress", SendError);
*/

// set event handlers after full load DOM elements
$(document).ready(function () {
	$('#smsn-btn-search').on('click', function (event) {
		getGoodsList();
		event.preventDefault();
	});

});


function getGoodsList() {
	BX.ajax.runComponentAction('samson:goods.adding.cart', 'getGoodsList', {
		mode: 'ajax',
		data: {
			xmlId: $('#smsn-xmlid').val()
		}
	}).then(function (response) {
		console.log(response);
		if (response['errors'].length == 0) {
			fnCreateHtmlList('#smsn-preselect-list', response['data'], 'smsn-preselect-item');
		}
	});

};

/**
 * @param arGoods array of catalog items as array(ID, XML_ID, NAME)
 * @param cssSelector string target select css selector to fill items
 * @param itemClass string items class name
 * @return html list of links for adding an item to the cart
 */
function fnCreateHtmlList(cssSelector, arGoods, itemClass) {
	$(cssSelector).find('option').remove();
	goodsList = '';
	arGoods.forEach(item => {
		goodsList += item['XML_ID'] + '-' + item['NAME'] + "\r\n";
		$(cssSelector).append(`<option class="${itemClass}" value="${item['ID']}">${item['XML_ID']} - ${item['NAME']}</option>`);
	});

	return goodsList;
}