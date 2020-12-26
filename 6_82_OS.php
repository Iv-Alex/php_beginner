<?php
/*
Используя $_SERVER['HTTP_USER_AGENT'] выведите на экран в наглядном виде какая у пользователя
ОС и его версия, например Windows XP.
То есть на экран нужно вывести "у вас windows xp" или у вас "android 7.0" или "у вас iOS 7.2"
Скрипт должен определять самые распространенные ОСи, а именно:
1. Windows XP, Vista, 7, 8, 10.
2. Linux (версию не обязательно)
3. Mac OS (версию не обязательно)
4. Android 4.0+
5. iOS 7+
*/

error_reporting(E_ALL); //E_ALL) - show all errors; 0 - show off errors
header('Content-Type: text/html; charset=utf-8');

define(
    'test_user_agents',
    array(
        "Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.17 (KHTML, like Gecko) Chrome/24.0.1312.57 Safari/537.17",
        "Mozilla/5.0 (Windows NT 6.3; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/79.0.3945.88 Safari/537.36",
        "Mozilla/5.0 (Windows; U; Windows NT 5.1; ru-RU) AppleWebKit/525.18 (KHTML, like Gecko) Version/3.1.1 Safari/525.17",
        "Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/74.0.3729.131 Safari/537.36",
        "Mozilla/4.0 (compatible; MSIE 7.0; Linux i686)",
        "Mozilla/5.0 (Windows NT 6.1) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/41.0.2228.0 Safari/537.36",
        "Mozilla/5.0 (Macintosh; Intel Mac OS X 10_14_4) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/12.1 Safari/605.1.15",
        "Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:66.0) Gecko/20100101 Firefox/66.0",
        "Mozilla/5.0 (Macintosh; Intel Mac OS X 10.14; rv:66.0) Gecko/20100101 Firefox/66.0",
        "Mozilla/5.0 (X11; Linux x86_64; rv:66.0) Gecko/20100101 Firefox/66.0",
        "Mozilla/5.0 (Windows NT 6.3; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/74.0.3729.131 Safari/537.36",
        "Mozilla/5.0 (iPad; U; CPU OS 3_2 like Mac OS X; en-us) AppleWebKit/531.21.10 (KHTML, like Gecko) Version/4.0.4 Mobile/7B334b Safari/531.21.10 - для айпада",
        "Mozilla/5.0 (iPhone; U; CPU like Mac OS X; en) AppleWebKit/420+ (KHTML, like Gecko) Version/3.0 Mobile/1C25 Safari/419.3",
        "Mozilla/5.0 (iPad; CPU OS 6_0 like Mac OS X) AppleWebKit/536.26 (KHTML, like Gecko) Version/6.0 Mobile/10A5355d Safari/8536.25",
        "Mozilla/5.0 (Linux; Android 6.0.1; SM-G532G Build/MMB29T) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/63.0.3239.83 Mobile Safari/537.36",
        "Mozilla/5.0 (Macintosh; Intel Mac OS X 10.15; rv:75.0) Gecko/20100101 Firefox/75.0",
        "Mozilla/5.0 (Macintosh; Intel Mac OS X 10_13_1) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/63.0.3239.84 Safari/537.36",
        "Mozilla/5.0 (Macintosh; Intel Mac OS X 10.13; rv:75.0) Gecko/20100101 Firefox/75.0",
        "Mozilla/5.0 (iPhone; CPU iPhone OS 13_3 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/13.0.4 Mobile/15E148 Safari/604.1",
        "Mozilla/5.0 (iPhone; CPU iPhone OS 12_1 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) CriOS/70.0.3538.75 Mobile/15E148 Safari/605.1",
        "Mozilla/5.0 (iPhone; CPU iPhone OS 10_2_1 like Mac OS X) AppleWebKit/602.4.6 (KHTML, like Gecko) Mobile/14D27",
        "Mozilla/5.0 (iPhone; CPU iPhone OS 8_1 like Mac OS X) AppleWebKit/600.1.4 (KHTML, like Gecko) Version/8.0 Mobile/12B411 Safari/600.1.4",
        "Mozilla/5.0 (iPad; CPU OS 7_1 like Mac OS X) AppleWebKit/537.51.2 (KHTML, like Gecko) Version/7.0 Mobile/11D167 Safari/9537.53",
        "Mozilla/5.0 (Macintosh; U; PPC; en-CA; rv:1.0.1) Gecko/20020823 Netscape/7.0",
        "Mozilla/4.0 (compatible; MSIE 5.1b1; AOL 5.1; Mac_PowerPC)",
        "Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:41.0) Gecko/20100101 Firefox/41.0",
        "Mozilla/5.0 (SMART-TV; X11; Linux armv7l) AppleWebKit/537.42 (KHTML, like Gecko) Chromium/25.0.1349.2 Chrome/25.0.1349.2 Safari/537.42",
        "Mozilla/5.0 (X11; Linux x86_64; rv:60.0) Gecko/20100101 Firefox/60.0",
        "Mozilla/3.0 (compatible; NetPositive/2.2.1; BeOS)",
        "Mozilla/5.0 (PlayStation Vita 3.67) AppleWebKit/537.73 (KHTML, like Gecko) Silk/3.2",
        "Mozilla/5.0 (X11; U; SunOS sun4u; en-US; rv:1.0.1) Gecko/20020920 Netscape/7.0",
        "server-bag [Watch OS,5.2.1,16U113,Watch3,4]",
        "Mozilla/5.0 (Linux; U; Android 2.2) AppleWebKit/533.1 (KHTML, like Gecko) Version/4.0 Mobile Safari/533.1",
        "Mozilla/5.0 (Linux; Android 9; SM-G950F Build/PPR1.180610.011; wv) AppleWebKit/537.36 (KHTML, like Gecko) Version/4.0 Chrome/74.0.3729.157 Mobile Safari/537.36",
        "Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:55.0) Gecko/20100101 Firefox/55.0/Nutch-1.12"
    )
);

# get_user_os_info($_SERVER['HTTP_USER_AGENT']) возвращает часть HTTP_USER_AGENT,
# содержащую информацию об ОС пользователя или FALSE в случае неудачи
function get_user_os_info($http_user_agent)
{
    $start_pos = strpos($http_user_agent, '(');
    $end_pos = strpos($http_user_agent, ')');
    if (($start_pos !== false) && ($end_pos !== false) && ($start_pos < $end_pos)) {
        $user_os_info = substr($http_user_agent, $start_pos, $end_pos - $start_pos + 1);
    } else {
        $user_os_info = false;
    }
    return $user_os_info;
}

# в базе WhatIsMyBrowser.com содержится более 27,894,000 вариантов HTTP_USER_AGENT
# с учетом появления новых версий ОС целесообразно разбить решение на 2 этапа:
# 1 - определить семейство ОС по условиям задачи, 2 - определить версию;
# причем в 1-м этапе во избежание ошибок учитывать порядок определения: Android || Linux; iOS || Mac OS; Ubuntu || Linux
# во 2-м этапе - способ определения версии: Windows - ассоциативный; Android, iOs - прямой.
# При ассоциативном способе целесообразно определять версию на 1-м этапе (утверждение относительно ОС из задачи).
#
# os_detect(string $os) : string возвращает наименование ОС в соответствии со списком или
# фразу 'не определена' в случае отсутствия ОС в списке
function os_detect($os)
{
    // массив ОС: индикатор_ОС => семейство_ОС, способ_определения_версии_false-ассоц_true-прямой,
    // строка_индикатор_версии, граница_версии, символ_делитель_версии
    $most_popular_os = array(
        // windows
        'windows nt 10.0' => array('Windows 10', false),
        'windows nt 6.3' => array('Windows 8.1', false),
        'windows nt 6.2' => array('Windows 8', false),
        'windows nt 6.1' => array('Windows 7', false),
        'windows nt 6.0' => array('Windows Vista', false),
        'windows nt 5.2' => array('Windows Server 2003/XP x64', false),
        'windows nt 5.1' => array('Windows XP', false),
        'windows nt' => array('Windows', false),
        'windows xp' => array('Windows XP', false),
        // Android
        'android' => array('Android', true, 'Android ', ';', '.'),
        // Linux
        'ubuntu' => array('Ubuntu', false),
        'linux' => array('Linux', false),
        // iOS
        'iphone' => array('iOS', true, 'iPhone OS ', ' ', '_'),
        'ipad' => array('iOS/iPadOS', true, 'CPU OS ', ' ', '_'),
        // macOS
        'mac_powerpc' => array('Mac OS X', false),
        'macintosh' => array('macOS/OS X/Mac OS X', true, 'Mac OS X ', ')', '_')
    );
    $user_os = false;
    foreach ($most_popular_os as $key => $val) {
        if (stripos($os, $key) !== false) {
            // семейство ОС
            $user_os = $val[0];
            if ($val[1]) {
                // прямой способ определения версии
                // ищем версию по строке_индикатору и вырезаем по границе
                if (($ver_start = stripos($os, $val[2])) !== false) {
                    $ver_start += strlen($val[2]);
                    $ver = substr($os, $ver_start, stripos($os, $val[3], $ver_start) - $ver_start);
                    // преобразуем символ_делитель_версии в ".", если он другой
                    if ($val[4] != '.') $ver = str_replace($val[4], '.', $ver);
                    $user_os .= (' ' . $ver);
                } else {
                    //информация о версии отсутствует
                }
            } else {
                // ассоциативный способ определения версии - версия в наименовании
            }
            break;
        } else {
            // ничего не меняем: ОС пока не определена
        }
    }
    return $user_os;
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>OS</title>
    <style>
        h3 {
            background: #0088bd;
            color: white;
        }

        h4 {
            color: #0088bd;
        }
    </style>
</head>

<body>

    <h3>Рабочий вариант</h3>
    <h5>HTTP_USER_AGENT = <?= $http_user_agent = $_SERVER['HTTP_USER_AGENT']; ?></h5>
    <h5>Информация об ОС: <?= $user_os_info = get_user_os_info($http_user_agent); ?></h5>
    <h4><?= ((($user_os = os_detect($user_os_info)) !== false) ? 'у вас ' . $user_os : 'ОС не определена'); ?></h4>

    <h3>Тестовый вариант</h3>
    <h5>test HTTP_USER_AGENT = <?= $http_user_agent = test_user_agents[rand(0, count(test_user_agents) - 1)]; ?></h5>
    <h5>Информация об ОС: <?= $user_os_info = get_user_os_info($http_user_agent); ?></h5>
    <h4><?= ((($user_os = os_detect($user_os_info)) !== false) ? 'у вас ' . $user_os : 'ОС не определена'); ?></h4>

</body>

</html>