<?php
error_reporting(E_ALL);
header('Content-Type: text/html; charset=utf-8');

$base_uri = $_SERVER['PHP_SELF'];

// рабочих часов в месяце
$hours = 172;

$zp = isset($_GET['zp']) ? $_GET['zp'] : 50000;
$emp_tax = isset($_GET['emp_tax']) ? $_GET['emp_tax'] : 13;
$pfr = isset($_GET['pfr']) ? $_GET['pfr'] : 22;
$med_ens = isset($_GET['med_ens']) ? $_GET['med_ens'] : 5.1;
$fss = isset($_GET['fss']) ? $_GET['fss'] : 2.9;
$injury = isset($_GET['injury']) ? $_GET['injury'] : 0.2;
$effect = isset($_GET['effect']) ? $_GET['effect'] : 50;
$profit = isset($_GET['profit']) ? $_GET['profit'] : 10;
$tax = isset($_GET['tax']) ? $_GET['tax'] : 6;
$zp_boss = isset($_GET['zp_boss']) ? $_GET['zp_boss'] : 15;
$amort = isset($_GET['amort']) ? $_GET['amort'] : 10;
$rent = isset($_GET['rent']) ? $_GET['rent'] : 7;
$other = isset($_GET['other']) ? $_GET['other'] : 10;
$market = isset($_GET['market']) ? $_GET['market'] : 20;
//база для отчислений в фонды
$baze_summ = round($zp / (100 - $emp_tax) * 100, 2);
//ПФР
$pfr_summ = round($baze_summ * $pfr / 100, 2);
//Мед.страх.
$med_ens_summ = round($baze_summ * $med_ens / 100, 2);
//ФСС
$fss_summ = round($baze_summ * $fss / 100, 2);
//Травмы
$injury_summ = round($baze_summ * $injury / 100, 2);
//затрат на сотрудника в месяц
$summary_expenses = $baze_summ + $pfr_summ + $med_ens_summ + $fss_summ + $injury_summ;
// затрат на сотрудника в час
$hour_expenses = round($summary_expenses / $hours, 2);
// затрат на оплачиваемый заказчиком час сотрудника (с учетом эффективности)
$effective_hour = round($hour_expenses * 100 / $effect, 2);
// стоимость часа с учетом планируемой прибыли
$profit_hour = round($effective_hour / (100 - $profit) * 100, 2);
// стоимость часа с учетом планируемой прибыли и налогов юр. лица
$tax_hour = round($profit_hour / (100 - $tax) * 100, 2);
// расходы, исчисляемые от оборота или валовой прибыли
$overdraft = $zp_boss + $amort + $rent + $other + $market;
$hour_pre_cost = round($tax_hour / (100 - $overdraft) * 100, 2);
// стоимость часа в прайсе
$hour_cost = round($hour_pre_cost, -1);

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
    <form action="" method="get">
        <div class="break">
            <h3>Калькулятор расчета стоимости часа на PHP</h3>
            <span class="comment">Расчеты производятся после отправки формы</span>
        </div>
        <div id="calc-form">
            <label>
                <span>Желаемая зарплата, руб.: </span>
                <input id="zp" name="zp" type="text" value="<?= $zp ?>">
            </label>
            <br>
            <label>
                <span>Подоходный налог с физ.лиц, %: </span>
                <input id="emp-tax" name="emp_tax" type="text" value="<?= $emp_tax ?>">
            </label>
            <span class="comment">сумма до вычета: <span id="emp-tax-comment">
                    <?= $baze_summ ?></span>&nbsp;руб.
            </span>
            <div class="break"><span>Отчисления в фонды</span></div>
            <label>
                <span>Пенсионный фонд, %: </span>
                <input id="pfr" name="pfr" type="text" value="<?= $pfr ?>">
            </label>
            <span class="comment">сумма отчислений:
                <span id="pfr-comment"><?= $pfr_summ ?></span>&nbsp;руб.
            </span>
            <br>
            <label>
                <span>Медицинское страхование, %: </span>
                <input id="med-ens" name="med_ens" type="text" value="<?= $med_ens ?>">
            </label>
            <span class="comment">сумма отчислений:
                <span id="med-ens-comment"><?= $med_ens_summ ?></span>&nbsp;руб.
            </span>
            <br>
            <label>
                <span>Социальное страхование, %: </span>
                <input id="fss" name="fss" type="text" value="<?= $fss ?>">
            </label>
            <span class="comment">сумма отчислений:
                <span id="fss-comment"><?= $fss_summ ?></span>&nbsp;руб.
            </span>
            <br>
            <label>
                <span>Травматизм, %: </span>
                <input id="injury" name="injury" type="text" value="<?= $injury ?>">
            </label>
            <span class="comment">сумма отчислений:
                <span id="injury-comment"><?= $injury_summ ?></span>&nbsp;руб.
            </span>
            <div class="summary">
                <span>Итого, расходы на сотрудника в месяц:
                    <span id="summary-exp" class="numeric"><?= $summary_expenses ?></span>&nbsp;руб.
                </span><br>
                <span>Стоимость 1 часа работы сотрудника (
                    <span id="hours-in-month"><?= $hours ?></span> часа в месяц):
                    <span id="hour-exp" class="numeric"><?= $hour_expenses ?></span>&nbsp;руб.
                </span>
            </div>
            <div class="break"><span>Расчет тарифа</span></div>
            <label>
                <span>Эффективность использования часа, %: </span>
                <input id="effect" name="effect" type="text" value="<?= $effect ?>">
            </label>
            <span class="comment">реальные затраты на 1 оплаченный час:
                <span id="effect-comment"><?= $effective_hour ?></span>&nbsp;руб.
            </span>
            <br>
            <label>
                <span>Планируемая прибыль, %: </span>
                <input id="profit" name="profit" type="text" value="<?= $profit ?>">
            </label>
            <span class="comment">стоимость 1 часа с учетом прибыли:
                <span id="profit-comment"><?= $profit_hour ?></span>&nbsp;руб.</span>
            <br>
            <label>
                <span>Ставка налога (юр. лицо), %: </span>
                <input id="tax" name="tax" type="text" value="<?= $tax ?>">
            </label>
            <span class="comment">стоимость 1 часа с учетом налога:
                <span id="tax-comment"><?= $tax_hour ?></span>&nbsp;руб.
            </span>
            <br>
            <label>
                <span>Зарплата руководства, %: </span>
                <input id="zp-boss" name="zp_boss" type="text" value="<?= $zp_boss ?>">
            </label>
            <br>
            <label>
                <span>Расходы на технику, амортизацию оборудования, канцелярию, %: </span>
                <input id="amort" name="amort" type="text" value="<?= $amort ?>">
            </label>
            <br>
            <label>
                <span>Расходы на снятие офиса, %: </span>
                <input id="rent" name="rent" type="text" value="<?= $rent ?>">
            </label>
            <br>
            <label>
                <span>Расходы на бухгалтерию, и прочее, %: </span>
                <input id="other" name="other" type="text" value="<?= $other ?>">
            </label>
            <br>
            <label>
                <span>Расходы на маркетинг и продажи, %: </span>
                <input id="market" name="market" type="text" value="<?= $market ?>">
            </label>
            <br>
            <label>
                <span>Итого, расходы, исчисляемые от оборота или валовой прибыли: <?= $overdraft ?>%; </span>
            </label>
            <span class="comment">стоимость 1 часа, увеличенная на сумму этих расходов:
                <span id="tax-comment"><?= $hour_pre_cost ?></span>&nbsp;руб.
            </span>
            <br>
            <div class="summary">
                <span>Итого, cтоимость 1 часа работы сотрудника:
                    <span id="summary-comment" class="numeric"><?= $hour_cost ?></span>&nbsp;руб.
                </span>
            </div>
        </div>
        <button type="submit">Отправить данные для нового расчета</button>
    </form>
</body>

</html>