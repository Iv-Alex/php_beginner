<?php
error_reporting(E_ALL);
header('Content-Type: text/html; charset=utf-8');

$base_url = $_SERVER['PHP_SELF'];               //script absolute URI
$sitemap_file = './sitemap.xml';                //sitemap filename


if (isset($_GET['start_url'])) {

    $start_url = $_GET['start_url'];            //uri для построения карты сайта
    $sitemap_limit = $_GET['sitemap_limit'];    //максимальное количество страниц для добавления в карту

    $freq = 'weekly';
    $priority = 1;

    $target = fopen($sitemap_file, "w");
    if (!$target) {
        //    echo "Cannot create $$sitemap_file!" . NL;
        return;
    }

    fwrite($target, "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n" .
        "<?xml-stylesheet type=\"text/xsl\" href=\"\"?>\n" .
        "<!-- Created with Iv-Alex Sitemap Generator 0.1 -->\n" .
        "<urlset xmlns=\"http://www.sitemaps.org/schemas/sitemap/0.9\"\n" .
        "        xmlns:xsi=\"http://www.w3.org/2001/XMLSchema-instance\"\n" .
        "        xsi:schemaLocation=\"http://www.sitemaps.org/schemas/sitemap/0.9\n" .
        "        http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd\">\n");
    $urls_count = 0;
    add_links_to_map($start_url, $start_url, $target, $priority, $freq, $sitemap_limit, $urls_count);

    fwrite($target, "</urlset>\n");
    fclose($target);
    echo $sitemap_file;
} elseif (isset($_GET['urls_count'])) {
    $script_way = 'progress';
    $urls_count = $_GET['urls_count'];
}

//recursive site crawling
function add_links_to_map($start_url, $url, &$file, $priority, $freq, $sitemap_limit, &$urls_count)
{
    //массив проверенных ссылок
    static $seen_links;
    if (empty($seen_links)) $seen_links = array();

    //применим к URI очищающий фильтр
    $url = filter_var($url, FILTER_SANITIZE_URL);

    //продолжим, если ссылку еще не смотрели, она валидна
    if (
        !in_array($url, $seen_links)
        && ($url !== false)
    ) {
        //записываем в просмотренные
        $seen_links[] = $url;
        //если ссылка содержит базовый путь, пройдем по ссылке
        if (strpos(
            str_replace('https://', 'http://', $url),
            str_replace('https://', 'http://', $start_url)
        ) !== false) {
            //получим содержимое по $url
            $link_data = get_link_data($url);
            //продолжим, если есть <title> и отдает заголовок 200 ОК
            if (
                get_title($link_data['html_data'])
                && ($link_data['http_response'] == 200)
            ) {
                //добавим страницу в карту
                $urls_count++;
                $current_priority = number_format(round($priority / count(explode("/", str_ireplace(["http://", "https://"], "", $url))) + 0.5, 3), 2);
                fwrite($file, "  <url>\n" .
                    "    <loc>" . htmlentities($url) . "</loc>\n" .
                    "    <changefreq>$freq</changefreq>\n" .
                    "    <priority>$current_priority</priority>\n" .
                    "  </url>\n");
                //выберем все ссылки в документе и освободим память
                $links = get_all_href($link_data['html_data']);
                unset($link_data);
                $links = array_unique($links);

                foreach ($links as $link) {
                    if (strlen($link) > 0) {
                        $href = rel2abs($link, $url);
                        if ($urls_count < $sitemap_limit)
                            add_links_to_map($start_url, $href, $file, $priority, $freq, $sitemap_limit, $urls_count);
                    }
                }
            } else {
                //ничего не делаем
            }
        } else {
            //ничего не делаем
        }
    } else {
        //ничего не делаем
    }
}

//функция возвращает содержимое страницы одной строкой и код ответа http
function get_link_data($url)
{
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_AUTOREFERER, true);
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_VERBOSE, 1);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE);
    curl_setopt($curl, CURLOPT_HEADER, 0);
    curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 5);
    $html_data = curl_exec($curl);
    //преобразуем html в строку
    $html_data = str_replace(array("\r", "\n"), '', $html_data);
    curl_close($curl);
    $http_response = http_response_code();
    return [
        'html_data' => $html_data,
        'http_response' => $http_response
    ];
}

//возвращает href всех элементов html
function get_all_href($html)
{
    $re = '~((<a\s[^<>]*?\shref\s*=\s*[\'"])|(<a\s+href\s*=\s*[\'"]))(?<href>[^<>\'"]*?)([\'"].*?>(.*?)</a>)~mi';
    preg_match_all($re, $html, $matches, PREG_SET_ORDER);
    $links = array();
    foreach ($matches as $link) {
        $links[] = strtolower($link['href']);
    }
    return $links;
}

//преобразование относительного пути в абсолютный
//взято с просторов интернета
function rel2abs($rel, $base)
{
    /* return if already absolute URL */
    if (parse_url($rel, PHP_URL_SCHEME) != '') return $rel;
    /* queries and anchors */
    //$rel = ($pos = strpos($rel, '#')) ? substr($rel, 0, $pos) : $rel;
    if ($rel[0] == '?') return $base . $rel;
    $pos = strpos($rel, '#');
    if ($pos === 0) {
        return $base;
    } elseif ($pos > 0) {
        $rel = substr($rel, 0, $pos);
    }

    /* parse base URL and convert to local variables:
       $scheme, $host, $path */
    extract(parse_url($base));
    /* remove non-directory element from path */
    $path = isset($path) ? preg_replace('#/[^/]*$#', '', $path) : '';
    /* destroy path if relative url points to root */
    if ($rel[0] == '/') $path = '';
    /* dirty absolute URL */
    $abs = "$host$path/$rel";
    /* replace '//' or '/./' or '/foo/../' with '/' */
    $re = array('#(/\.?/)#', '#/(?!\.\.)[^/]+/\.\./#');
    for ($n = 1; $n > 0; $abs = preg_replace($re, '/', $abs, -1, $n)) {
    }
    /* absolute URL is ready! */
    return $scheme . '://' . $abs;
}

function get_title($str)
{
    $title = preg_match("~<title>(.*?)</title>~iu", $str, $out) ? $out[1] : '';
    return $title;
}
