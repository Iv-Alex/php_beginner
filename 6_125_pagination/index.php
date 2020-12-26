<?php
error_reporting(E_ALL);
header('Content-Type: text/html; charset=utf-8');

$root_dir = '/6_125_pagination/';
//считаем, что папка создана
$dir = 'articles/'; //папка статей
$per_page = 5; //количество статей на странице

//обработка загрузки статьи
if (isset($_POST['new_article'])) {
    if ($_FILES['path']['error'] == 0) {                            //если нет ошибок, загружаем файл
        //формируем имя файла
        //для этого ищемем максимальный номер файла
        $files = scandir($dir);
        $next_num = 0; // по умолчанию, файлов нет
        foreach ($files as $value) {
            if (!is_dir($value)) {
                $cur_num = +basename($value, '.txt');
                if ($cur_num > $next_num) {
                    $next_num = $cur_num;
                } else {
                }
            } else {
            }
        }
        $next_num++; // номер нового файла
        $file_name = $dir . $next_num . '.txt'; //
        // копируем файл из временной папки в постоянную
        move_uploaded_file($_FILES['path']['tmp_name'], $file_name);
        // формируем первой строкой название статьи из формы
        if ($_POST['article_name'] > '') {
            $file_data = $_POST['article_name'] . "\n";
        } else {
            $file_data = "(Заголовок отсутствует)\n";
        }
        // добавляем дату публикации
        $file_data .= date('d-m-Y') . "\n";
        // добавляем полученные строки в файл
        $file_data .= file_get_contents($file_name);
        file_put_contents($file_name, $file_data);
        // обновляем страницу, сообщаем об успешной загрузке
        header('Location: ' . $root_dir . '?message=add_ok');
    } elseif ($_FILES['path']['error'] == 4) {                      // файл для загрузки не выбран, сообщаем об этом
        header('Location: ' . $root_dir . '?add_article=empty');
    } else {                                                        // другие ошибки - сообщаем код
        echo '<br>' . $_FILES['path']['error'];
    }
}

//обработка удаления статьи
if (isset($_GET['del_article'])) {
    $del_file = './' . $dir . $_GET['del_article'] . '.txt';
    header('Location: ' . $root_dir . '?message=del_' . (unlink($del_file) ? 'ok' : 'err'));
}

// Функция возвращает массив статей для текущей страницы
// (путь, текущая_страница, статей_на_странице, посчитать_всего_статей)
function get_articles($dir, $page, $per_page, &$total_articles)
{
    $files = scandir($dir, 0); // 0/1 - режим сортировки
    //общее количество статей
    $total_articles = count($files) - 2;  // . и ..
    //проверяем корректность номера страницы
    if (+$page < 1) {
        $page = 1;
    } elseif (($max_page = ceil($total_articles / $per_page)) < $page) {
        $page = $max_page;
    } else {
    }
    //определяем номера первой и последней статей на странице
    $start = ($page - 1) * $per_page + 1;
    $end = $start + $per_page - 1;
    //список статей для текущей страницы
    if ($total_articles < $end) {
        $end = $total_articles;
    } else {
    }
    $articles = array();
    $i = 0; //счетчик просмотренных статей
    $j = 0; //индекс массива файлов
    while ($i < $end) {
        if ($files[$j] != '.' && $files[$j] != '..') {
            $i++;
            if ($i >= $start) {
                $articles[] = $dir . $files[$j];
            } else {
            }
        } else {
        }
        $j++;
    }
    return $articles;
}

// show_articles(массив, класс списка)
// Выводит список статей из массива
function show_articles($articles, $ul_class)
{
    $articles_block = '<ul class="' . $ul_class . '">';
    for ($i = 0; $i <= count($articles) - 1; $i++) {
        //получаем название статьи
        $handle = @fopen('./' . $articles[$i], "r");
        $article_header = false; //по умолчанию значение такое же, как в случае неудачи чтения строки из файла
        if ($handle) {
            //читаем первую строку, удаляем html и php теги, преобразуем спецсимолы
            $article_header = htmlspecialchars(strip_tags(fgets($handle)));
            //обрезаем заголовок до заданного количества символов
            $article_header_max_length = 250;
            if (mb_strlen($article_header) > $article_header_max_length) {
                mb_substr($article_header, 0, mb_stripos($article_header, ' ', $article_header_max_length)) . '...';
            } else {
            }
            fclose($handle);
        } else {
        }
        if ($article_header === false) {
            $article_header = '(Текст в файле статьи отсутствует)';
        } else {
        }
        //формируем строку списка
        $article_id = basename($articles[$i], '.txt');
        $articles_block .= <<<ASTR
            <li>
                <div class="container">
                    <div class="link"><a href="./?article_file=$article_id">$article_header</a></div>
                    <div class="form"><a href="./?del_article=$article_id">&nbsp;</a></div>
                </div>
            </li>
ASTR;
    }
    $articles_block .= '</ul>';
    return $articles_block;
}

// Выводит пагинацию
function show_page_nav($page_count, $page, $ul_class)
{
    $pagination = '<ul class="' . $ul_class . '"><li><span>Страницы: </span></li>';
    $li_prefix = '<li><a href="./?page=';
    $li_middle = '">';
    $li_postfix = '</a></li>';
    if ($page > 1) {
        $pagination .= $li_prefix . ($page - 1) . $li_middle . '<<' . $li_postfix;
        $pagination .= $li_prefix . '1' . $li_middle . '1' . $li_postfix;
    } else {
    }
    $pagination .= ($page > 4) ? '<li><span>...</span></li>' : '';
    $pagination .= ($page > 3) ? $li_prefix . ($page - 2) . $li_middle . ($page - 2) . $li_postfix : '';
    $pagination .= ($page > 2) ? $li_prefix . ($page - 1) . $li_middle . ($page - 1) . $li_postfix : '';
    $pagination .= '<li><span class="active">' . $page . '</span></li>';
    $pagination .= ($page + 1 < $page_count) ? $li_prefix . ($page + 1) . $li_middle . ($page + 1) . $li_postfix : '';
    $pagination .= ($page + 2 < $page_count) ? $li_prefix . ($page + 2) . $li_middle . ($page + 2) . $li_postfix : '';
    $pagination .= ($page + 3 < $page_count) ? '<li><span>...</span></li>' : '';
    if ($page < $page_count) {
        $pagination .= $li_prefix . $page_count . $li_middle . $page_count . $li_postfix;
        $pagination .= $li_prefix . ($page + 1) . $li_middle . '>>' . $li_postfix;
    } else {
    }
    $pagination .= '</ul>';
    return $pagination;
}

// get_article(полное_имя_файла_статьи, заголовок_и_первая_строка)
// Возвращает массив (header-Заголовок, text-Текст, created-Создана, modify-Изменена)
function get_article($article_file)
{
    $article = array();
    if (is_readable($article_file)) {
        $article_text = file($article_file);
        $article['header'] = htmlspecialchars(strip_tags($article_text[0]));
        $article['date'] = htmlspecialchars(strip_tags($article_text[1]));
        $article['text'] = '<p>';
        //преобразуем строки в абзацы html, удаляем пустые 
        for ($i = 2; $i < count($article_text); $i++) {
            if ($article_text[$i] > '') {
                $article['text'] .= htmlspecialchars(strip_tags($article_text[$i])) . '</p><p>';
            } else {
            }
        }
        $article['text'] .= '</p>';
        $article['created'] = date("Y-m-d", filectime($article_file)); //для UNIX - это время последнего изменения индексного дескриптора. Время создания отсутствует, как понятие
        $article['modify'] = date("Y-m-d", filemtime($article_file));
    } else {
        $article['header'] = 'Статья не найдена';
    }
    return $article;
}

?>

<!DOCTYPE html>
<html lang="ru">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Каталог статей</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Balsamiq+Sans&display=swap');

        html {
            font-family: 'Balsamiq Sans', cursive;
        }

        body {
            padding: 1vmin 10vw;
        }

        a.button {
            display: block;
            text-decoration: none;
            padding: 0.5rem;
            min-width: 12rem;
            text-align: center;
            background: silver;
            outline: 1px solid grey;
            color: black;
        }

        a.button:hover {
            cursor: pointer;
            color: red;
            outline-width: 2px;
        }

        #breadcrumbs ul {
            display: flex;
            list-style-type: none;
            margin: 0;
            padding: 0;
            line-height: 1.5;
        }

        #breadcrumbs ul li {
            padding: 0 2px;
            margin: 0;
        }

        #breadcrumbs ul li:after {
            content: '>';
            color: grey;
            margin-left: 3px;
        }

        #breadcrumbs ul li:last-child:after {
            content: '';
        }

        header h3 {
            font-size: 1.5rem;
        }

        footer {
            margin: 2vmin 0;
        }

        .align-right {
            text-align: right;
        }

        .article-list {
            padding-left: 10px;
            list-style-type: none;
            line-height: 2;
        }

        .article-list li {
            position: relative;
        }

        .article-list li:hover::before {
            top: 0.3rem;
            content: '';
            width: 20px;
            height: 20px;
            position: absolute;
            background: url(data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABQAAAAUCAYAAACNiR0NAAAAk0lEQVQ4T8XUMQ5BQRAG4O+VJAoJjcohlC7gfJR6R3ABiTNouIQDCKF7u89sRmy9+2V25t/tJK8u2fNXcIwt9jiXbhapcIbbG9rg1IdGwOf5NY41NAoOoi1gFW0Fi+gHXOKASTCXC0xxxwqXn4HBwl7beyfe2sNifFrAahajYGqw57hmPr0Rdpmfw1dJiPZwEE0HHwSZHhVXj03HAAAAAElFTkSuQmCC);
        }

        .article-list li:hover .container .form {
            background: no-repeat url(data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAACAAAAAgCAYAAABzenr0AAAEQklEQVRYR7WXX2xTVRzHv79z+8eumSSAbKM8Ef8wImBce9uNJwjRYHziRTCQGB2G8mI0xuCLCfFFQ0h8GsSN+MR80xeUaEKyB1257UhcRCFBkX9LBt0eJI51bc855nfXW29vb9vbZfatOef3+37O7985lxDwdzWdfpq0fh1K7ZdC7DGU2q6E6K2ZP9bAX4ZSsxDiiib6LmNZj4O4pk6bCsnkC9IwTpGUhyHEU5322+tKLUOIrzXRZxnLutXOpiXAzNBQjzSMT6HUexDCCCTs3aRUlYi+qIZCn4zkcst+PnwBLNN8HlJ+C8PYuSZhjxFp/Ws1FDo0ksv94fXXBJDLZF6GUj8IYPN6iLt8FEnrV8xC4Re33wYAPrkCfv4fxB3NojSMEXck6gCzu3fHS+Fw3i/sIhaDWvZNYcsgGfE45NJS03otHWmnJuoAlmmeBfCB1yJx/Dg2HzyIGydPojw/Hygr0a1bMXjuHIqXLmFufNzP5kw6n/+IF2wAbjUF/OatdhZPjI7aDlbm5gJBOOKR/n7bbm5iohlCqaoMhwc5FTbA1XT6K9L6LTcqh33X5CTYofPrBBEdGMCO8+cRrYmzXenOHVw/dgyqXG6MhFIX0jMzozQzNLRBEs37DRk+BYcyCEQrcU5dZXGxKQ0KeCLD4X6yTPNNABdbJTcIBJ/YPvnAQN1N6e5d3MhmfcWdTaT1YbKSyQkI8U676vITcNLBdt4ole7dWxVfWGhbtKT1lzRtmgUDSHYq71YQIGpIEYvfzGZR7iBe07M4AosQYmMnAF73g3Dble7fx80TJ4KKs2mRcqZZFkA4CIAN4VPpdrU/eLAqXiwGdQUFlNcXgEP/6FHXAAsC2BTEittxB7elq88bUsBR6A6iGLwIfcQ57DzJotu2/dd+3UHYRdi5DWuz3Rmvds654LJZcBfYbbgGCLsNLdM8AmCyVQqiiQQGx8bQIO5ptciWLWuCIK3fIPuxKSWP4pgXIoi4YxPp61uFSCQCpYNHcamnp8++jCzTvADgbTcA3+d8GTWcnMcrz/YWQ6YVxPWjR5veE5poPGNZ7zq34XMk5e8QIuSGcF/H9mxvI94qEn7XsQIqYSkHk9eu/Vl/kORTqTOa6ENvGhhi04EDq+I+t5pf7dgX2NgYFi5f9n2Q1J7rH7NtHYCf4RXDsATwotepiESa7/MOg0NEo1ArK827pJx90tub2Tc1VWoA4D/Tw8PPGlJOA3gmyGDqeo9SDwXRSKpQuO3YNj3L86nUS5rox3WHUOohhHg1nc/PusF9P0w4EqFq9RtNtKvrU/oZSDkrhDjkPnnLCDgL08PDMUPK01DqfW93BIXiaieis8ux2Gkn517bjh+ndl1UKqeUEEcE0BNEnIcMEV0MVaufc6u1s+kI4Bj/tHdvb6Rcfg3Afk20B8B2BWzgdQH8DeA2ac35vbIUj3+/b2rqnyCw/wL+7xUQybrqggAAAABJRU5ErkJggg==);
        }

        .article-list li .container {
            display: flex;
            justify-content: space-between;
            margin-left: 30px;
            margin-right: 10px;
        }

        .article-list li .container .form {
            margin-left: 10px;
            vertical-align: middle;
            background-position: left center;
        }

        .article-list li .container .form a {
            display: inline-block;
            width: 32px;
            height: 32px;
            text-decoration: none;
            border-radius: 50%;
        }

        .article-list li .container .form a:hover {
            box-shadow: 0 0 15px red;
        }

        .pagination {
            display: flex;
            list-style-type: none;
            padding: 0;
        }

        .pagination span {
            font-weight: bold;
        }

        .pagination li * {
            padding: 2px 10px;
        }

        .pagination li a:hover {
            color: red;
            font-weight: bold;
        }

        .pagination li .active {
            background: yellow;
            border-radius: 50%;
        }

        .total-articles {
            margin-left: 10px;
        }

        .control-buttons,
        .article-info {
            margin-left: 40px;
        }

        .col-2,
        .article-info>div {
            display: flex;
            justify-content: space-between;
            margin-bottom: 1vmin;
        }

        .col-2>article {
            width: 100%;
        }

        .article-info>div {
            min-width: 280px;
        }

        .article-info>div>div {
            padding-left: 5px;
            font-weight: bold;
        }

        #message .container {
            display: flex;
            width: 100%;
            height: 20vmin;
        }

        #message .ok-message,
        #message .err-message {
            display: flex;
            width: 50%;
            height: 50%;
            margin: auto;
            justify-content: center;
            align-items: center;
        }

        #message .ok-message {
            background: #abe88d;
            border: 1px green solid;
        }

        #message .err-message {
            background: #e89f8d;
            border: 1px red solid;
        }

        .ok-message {
            color: green;
        }

        .err-message {
            color: red;
        }

        #article-form {
            display: flex;
            flex-direction: column;
        }

        #article-form * {
            font-family: 'Balsamiq Sans', cursive;
        }

        #article-form .input {
            width: 15rem;
            margin: 2vmin 0;
        }

        #article-form .input input {
            width: 14rem;
        }

        #article-form .submit {
            padding: 2vmin;
            width: 14.5rem;
            margin: 2vmin 0;
        }
    </style>
</head>

<body>

    <?php
    if (isset($_GET['article_file'])) {
        //Показываем статью
        $article = get_article('./' . $dir . $_GET['article_file'] . '.txt');
    ?>
        <section id="article">
            <header>
                <h3><?= $article['header'] ?></h3>
                <h5>Дата публикации: <?= $article['date'] ?></h5>
            </header>
            <nav id="breadcrumbs">
                <ul>
                    <li><a href="<?= $root_dir ?>">Главная</a></li>
                    <li><span>Статья №<?= $_GET['article_file'] ?></span></li>
                </ul>
            </nav>
            <div class="col-2">
                <article>
                    <?= isset($article['text']) ? $article['text'] : ''; ?>
                </article>
                <aside class="article-info">
                    <div>
                        <?php
                        if (isset($article['modify'])) {
                        ?>
                            <div>Дата изменения статьи: </div>
                            <div><?= $article['modify'] ?></div>
                        <?php
                        }
                        ?>
                    </div>
                    <div>
                        <?php
                        if (isset($article['created'])) {
                        ?>
                            <div>Дата создания:</div>
                            <div><?= $article['created'] ?></div>
                        <?php
                        }
                        ?>
                    </div>
                </aside>
            </div>
            <footer>
                <div class="align-right">
                    <a href="<?= $root_dir ?>">Вернуться на главную</a>
                </div>
            </footer>
        </section>
    <?php
    } elseif (isset($_GET['add_article'])) {
        //Выводим форму для добавления статьи
    ?>
        <section id="add_article">
            <header>
                <h3>Добавление статьи</h3>
            </header>
            <div>
                <?php
                // обработка ошибок заполнения формы
                if ($_GET['add_article'] == 'empty') {
                ?>
                    <div class="err-message">Необходимо выбрать файл</div>
                <?php
                } elseif ($_GET['add_article'] == 'err') {
                ?>
                    <div class="err-message">
                        Ошибка загруки файла. Код: <?= isset($_GET['err']) ? $_GET['err'] : 'unknown' ?>
                    </div>
                <?php
                } else {
                }
                ?>
                <form name="article_form" id="article-form" action="./" enctype="multipart/form-data" method="post">
                    <input type="hidden" name="new_article" value="new">
                    <label class="input">
                        Название статьи:<br>
                        <input type="text" name="article_name" placeholder="не обязательно, если нет в файле">
                    </label>
                    <input type="hidden" name="MAX_FILE_SIZE" value="2000000">
                    <input type="file" name="path" class="input" title="Выберите файл">
                    <input type="submit" name="submit" class="submit" value="Добавить">
                </form>
            </div>
            <footer>
                <div class="align-right">
                    <a href="<?= $root_dir ?>">Вернуться на главную</a>
                </div>
            </footer>
        </section>
    <?php
    } elseif (isset($_GET['message'])) {
        //обрабатываем сообщения
    ?>
        <section id="message">
            <div class="container">
                <?php
                if ($_GET['message'] == 'add_ok') {
                ?>
                    <div class='ok-message'>Статья успешно добавлена</div>
                <?php
                } elseif ($_GET['message'] == 'del_ok') {
                ?>
                    <div class='ok-message'>Статья успешно удалена</div>
                <?php
                } elseif ($_GET['message'] == 'del_err') {
                ?>
                    <div class='err-message'>Ошибка удаления статьи</div>
                <?php
                } else {
                }
                ?>
            </div>
            <footer>
                <div class="align-right">
                    <a href="<?= $root_dir ?>">Вернуться на главную</a>
                </div>
            </footer>
        </section>
    <?php
    } else {
        //Формируем страницу статей
        $page = isset($_GET['page']) ? $_GET['page'] : 1;
        //получение списка статей на страницу и количества статей вообще
        if (file_exists($dir)) {
            //получаем список статей для текущей страницы, в $total_articles функция запишет общее кол-во статей
            $articles = get_articles($dir, $page, $per_page, $total_articles);
        } else {
            $total_articles = 0;
            $articles = array();
        };
    ?>
        <section id="articles-list">
            <header>
                <h3>Статьи</h3>
            </header>
            <div class="col-2">
                <article>
                    <?= (count($articles) > 0) ? show_articles($articles, 'article-list') : 'Статей пока нет'; ?>
                </article>
                <div class="control-buttons">
                    <a class="button" href="./?add_article=true">Добавить статью</a>
                </div>
            </div>
            <footer>
                <nav>
                    <?= show_page_nav(ceil($total_articles / $per_page), $page, 'pagination'); ?>
                </nav>
                <div class="total-articles">Всего статей: <?= $total_articles; ?></div>
            </footer>
        </section>
    <?php
    }
    ?>

</body>

</html>