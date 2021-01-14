<?php
error_reporting(E_ALL);
header('Content-Type: text/html; charset=utf-8');




?>

<!DOCTYPE html>
<html lang="ru">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Урок 155. Калькулятор расчета стоимости часа на PHP</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .comment {
            color: grey;
        }

        #calc-form label {
            line-height: 1.5;
        }

        #calc-form label>span {
            display: inline-block;
            width: 20em;
            text-align: right;
        }

        #calc-form label>input {
            width: 5em;
        }

        #calc-form .summary {
            margin-top: 1em;
        }

        #calc-form .summary .numeric {
            font-weight: bold;
        }

        .break {
            padding: 0.5em;
            margin: 1em 0;
            border-top: 1px dotted grey;
            background: lightgray;
        }
        .break h3 {
            margin: 0;
        }
    </style>

</head>

<body>
    <div class="break">
        <h3>Калькулятор расчета стоимости часа на JS</h3>
        <span class="comment">Расчеты производятся автоматически, при изменении полей ввода</span>
    </div>
    <div id="calc-form">
        <label>
            <span>Желаемая зарплата, руб.: </span>
            <input id="zp" type="text" value="50000">
        </label>
        <br>
        <label>
            <span>Подоходный налог с физ.лиц, %: </span>
            <input id="emp-tax" type="text" value="13">
        </label>
        <span class="comment">сумма до вычета: <span id="emp-tax-comment"></span>&nbsp;руб.</span>
        <div class="break"><span>Отчисления в фонды</span></div>
        <label>
            <span>Пенсионный фонд, %: </span>
            <input id="pfr" type="text" value="22">
        </label>
        <span class="comment">сумма отчислений: <span id="pfr-comment"></span>&nbsp;руб.</span>
        <br>
        <label>
            <span>Медицинское страхование, %: </span>
            <input id="med-ens" type="text" value="5.1">
        </label>
        <span class="comment">сумма отчислений: <span id="med-ens-comment"></span>&nbsp;руб.</span>
        <br>
        <label>
            <span>Социальное страхование, %: </span>
            <input id="fss" type="text" value="2.9">
        </label>
        <span class="comment">сумма отчислений: <span id="fss-comment"></span>&nbsp;руб.</span>
        <br>
        <label>
            <span>Травматизм, %: </span>
            <input id="injury" type="text" value="0.2">
        </label>
        <span class="comment">сумма отчислений: <span id="injury-comment"></span>&nbsp;руб.</span>
        <div class="summary">
            <span>Итого, расходы на сотрудника в месяц: <span id="summary-exp"
                    class="numeric"></span>&nbsp;руб.</span><br>
            <span>Стоимость 1 часа работы сотрудника (<span id="hours-in-month">172</span> часа в месяц): <span
                    id="hour-exp" class="numeric"></span>&nbsp;руб.</span>
        </div>
        <div class="break"><span>Расчет тарифа</span></div>
        <label>
            <span>Эффективность использования часа, %: </span>
            <input id="effect" type="text" value="50">
        </label>
        <span class="comment">реальные затраты на 1 оплаченный час: <span id="effect-comment"></span>&nbsp;руб.</span>
        <br>
        <label>
            <span>Планируемая прибыль, %: </span>
            <input id="profit" type="text" value="10">
        </label>
        <span class="comment">стоимость 1 часа с учетом прибыли: <span id="profit-comment"></span>&nbsp;руб.</span>
        <br>
        <label>
            <span>Ставка налога (юр. лицо), %: </span>
            <input id="tax" type="text" value="6">
        </label>
        <span class="comment">стоимость 1 часа с учетом налога: <span id="tax-comment"></span>&nbsp;руб.</span>
        <br>
        <div class="summary">
            <span>Итого, cтоимость 1 часа работы сотрудника: <span id="summary-comment"
                    class="numeric"></span>&nbsp;руб.</span>
        </div>
    </div>

    <script>
        "use strict";
        for (let item of document.getElementById('calc-form').getElementsByTagName('input')) {
            item.setAttribute('onkeypress', 'javascript:setTimeout(calc)');
            item.setAttribute('onfocusout', 'javascript:setTimeout(calc)');
        }
        calc();

        //get_with_percents(base, tax) возвращает сумму base + tax = 100%, округленную до сотых
        function get_with_percents(base, tax) {
            return Math.round(base * 100 / (1 - tax / 100)) / 100;
        }

        // в вычислениях сознательно использованы переменные, совпадающие с id
        function calc() {
            let zp = +document.getElementById('zp').value;
            let emp_tax = +document.getElementById('emp-tax').value;
            let base_summ = get_with_percents(zp, emp_tax);
            document.getElementById('emp-tax-comment').innerHTML = base_summ;
            // фонды
            // ПФР
            let pfr = Math.round(parseFloat(document.getElementById('pfr').value) * base_summ) / 100;
            document.getElementById('pfr-comment').innerHTML = pfr;
            // мед. страх
            let med_ens = Math.round(parseFloat(document.getElementById('med-ens').value) * base_summ) / 100;
            document.getElementById('med-ens-comment').innerHTML = med_ens;
            // ФСС
            let fss = Math.round(parseFloat(document.getElementById('fss').value) * base_summ) / 100;
            document.getElementById('fss-comment').innerHTML = fss;
            // травмы
            let injury = Math.round(parseFloat(document.getElementById('injury').value) * base_summ) / 100;
            document.getElementById('injury-comment').innerHTML = injury;
            // затрат на сотрудника в месяц
            let summary_expenses = base_summ + pfr + med_ens + fss + injury;
            document.getElementById('summary-exp').innerHTML = summary_expenses;
            // затрат на сотрудника в час
            let hour_expenses = Math.round(summary_expenses * 100 / parseInt(document.getElementById('hours-in-month').innerHTML)) / 100;
            document.getElementById('hour-exp').innerHTML = hour_expenses;
            // затрат на оплачиваемый заказчиком час сотрудника (с учетом эффективности)
            let effective_hour = Math.round(hour_expenses * 10000 / parseFloat(document.getElementById('effect').value)) / 100;
            document.getElementById('effect-comment').innerHTML = effective_hour;
            // стоимость часа с учетом планируемой прибыли
            let profit_hour = get_with_percents(effective_hour, parseFloat(document.getElementById('profit').value));
            document.getElementById('profit-comment').innerHTML = profit_hour;
            // стоимость часа с учетом планируемой прибыли и налогов юр. лица
            let hour_pre_cost = get_with_percents(profit_hour, parseFloat(document.getElementById('tax').value));
            document.getElementById('tax-comment').innerHTML = hour_pre_cost;
            // стоимость часа в прайсе
            let hour_cost = Math.round(hour_pre_cost / 10) * 10;
            document.getElementById('summary-comment').innerHTML = hour_cost;
        }

    </script>
</body>

</html>