<?php
require_once("engine.php");
global $_global_bread;

if (isset($_GET['debug'])) {
    if ($_GET['debug'])
        setcookie('site_debug', 1, time() + 90000, "/");
    else
        setcookie('site_debug', 0, time() - 90000, "/");
}
else {
    if (isset($_COOKIE['site_debug']))
        if ($_COOKIE['site_debug'] == 1)
            $_GET['debug'] = $_COOKIE['site_debug'];
        else
            $_GET['debug'] = 0;
    else
        $_GET['debug'] = 0;
};
$GTIMER1 = new CTimer("Время генерации страницы");

$H_USER = new DUser(1, "site_users", "email", "password", "holy_site");
if ($H_USER->GetID() != 0)
    $user_info = $H_USER->GetInfo();
else
    $user_info['id'] = false;

//переменная никогда не будет пустой
if (!isset($_GET['path']))
    $_GET['path'] = "index";

//разбиваем путь на массив
$path = explode("/", $_GET['path']);

//если последний пустой, удаляем его
if (end($path) == "")
    unset($path[count($path) - 1]);

//число элементов
$max_count = count($path) - 1;

if (!isset($_OPTIONS['page_module']))
    $_OPTIONS['page_module'] = "pages";

$inter = new DBlockElement($_OPTIONS['page_module']);
$templ = new DBlockElement("templates");

$find_end = false;
$data['id'] = 0;
foreach ($path as $i => $url)
    if (!$find_end) {
        $data = $inter->GetOne("name='" . $url . "' AND not_visible=0 AND parent=" . $data['id']);
        if (!isset($data['id']))
            $data['id'] = -1;

        //если не найдено
        if ($data['id'] == -1) {
            $selected_page = "e404";
            $find_end = true;
        } else {
            if ($data['human_url']) {
                $find_end = true;
                $selected_page = $data['id'];
                for ($j = $i; $j <= $max_count; $j++)
                    if ($j != $i)
                        $human_link[] = $path[$j];
            }
            else
            if ($i == $max_count) {
                $selected_page = $data['id'];
                $find_end = true;
            };
        };
    };

$_selected_page = $inter->GetByID($selected_page);
$_selected_page_old = $_selected_page;

$old_template = $templ->GetByID($_selected_page['template']);

if (($_selected_page['template'] == 0) || ($old_template['name'] == "_")) {
    $_selected_page['template'] = 0;
    $now_item_tmp = $_selected_page;
    while ($now_item_tmp['parent'] != 0)
        if ($_selected_page['template'] == 0) {
            $now_item_tmp = $inter->GetByID($now_item_tmp['parent']);
            if ($now_item_tmp['template'] != 0)
                $_selected_page['template'] = $now_item_tmp['template'];
        };
};


if ($_selected_page['template'] == 0)
    $_selected_page['template'] = 1;


if ($_selected_page['folder'] == 1) {
    //
    $inter->GetList("parent=" . $_selected_page['id'] . " AND folder=0");
};

$template = $templ->GetByID($_selected_page['template']);

$_selected_page['template_name'] = $template['name'];
$_selected_page_old['template_name'] = $template['name'];
//подключаем шаблон
//empty
//подгрузить настройки
$_sp_opt = new DBlockElement("options");
$_sp_opt->GetList();
while ($options_temp = $_sp_opt->GetNext())
    if (!isset($_OPTIONS[$options_temp['name']]))
        if ($options_temp)
            if (isset($options_temp['foto'])) {
                if ($options_temp['foto'] != "")
                    $_OPTIONS[$options_temp['name']] = "/upload/" . $options_temp['foto'];
                else
                    $_OPTIONS[$options_temp['name']] = $options_temp['hvalue'];
            } else
                $_OPTIONS[$options_temp['name']] = $options_temp['hvalue'];

if (isset($_selected_page['caption']))
    $_OPTIONS['page_title'] = $_selected_page['caption'];

if (isset($_selected_page['keywords']))
    if ($_selected_page['keywords'] != "")
        $_OPTIONS['keywords'] = $_selected_page['keywords'];

//---------------------------------------
//работаем с крошками
//добавляем текущий элемент
if (!isset($_selected_page['not_in_bread']))
    $_selected_page['not_in_bread'] = 0;

if (!$_selected_page['not_in_bread']) {
    AddToBread($_selected_page['caption'], $_selected_page['name']);
    $global_current_link_array[] = $_selected_page['id'];
};

//работаем с папками - рекурсивно ищем уровень вложенности
$de_current = $_selected_page;
$de_current_res = new DBlockElement("pages");
if (isset($de_current))
    if (isset($de_current['parent']))
        while ($de_current['parent'] != 0) {
            $de_current_res->GetList("id='" . $de_current['parent'] . "'");
            $de_current = $de_current_res->GetNext();

            if (isset($de_current))
                if (isset($de_current['caption']))
                    if ($de_current['caption']) {
                        AddToBread($de_current['caption'], $de_current['name']);
                        $global_current_link_array[] = $de_current['id'];
                    };
        };
if ($_selected_page['name'] != "index") {
    if (isset($_OPTIONS['f_bread']))
        if ($_OPTIONS['f_bread'])
            AddToBread($_OPTIONS['f_bread'], "/");
};

if (count($_global_bread) > 0)
    $_global_bread = array_reverse($_global_bread);

//меняем ссылки
if (count($_global_bread) > 0) {
    foreach ($_global_bread as $i => $gb) {
        if (isset($_global_bread[$i - 1][1]))
            $_global_bread[$i][1] = $_global_bread[$i - 1][1] . "/" . $gb[1];
        if (isset($_global_bread[$i][1][0]))
            if ($_global_bread[$i][1][0] != "/")
                $_global_bread[$i][1] = "/" . $_global_bread[$i][1];
        $_global_bread[$i][1] = str_replace("//", "/", $_global_bread[$i][1]);

    };
};
//---------------------------------------

ob_start();
if ($_selected_page['modules'] == "")
    $_selected_page['modules'] = "empty";

if (file_exists(DOCUMENT_ROOT . "/site/modules/" . $_selected_page['modules'] . "/index.php"))
    include (DOCUMENT_ROOT . "/site/modules/" . $_selected_page['modules'] . "/index.php");
elseif (file_exists(DOCUMENT_ROOT . "/engine/modules/" . $_selected_page['modules'] . "/index.php"))
    include (DOCUMENT_ROOT . "/engine/modules/" . $_selected_page['modules'] . "/index.php");
else
    SystemAlert("Не найден модуль <b>" . $_selected_page['modules'] . "</b>");

$page_text = ob_get_contents();
ob_end_clean();
$_selected_page = $_selected_page_old;


if ($_selected_page['description'] != "")
    $_OPTIONS['description'] = $_selected_page['description'];
if ($_selected_page['caption2'] != "")
    $_OPTIONS['site_title'] = $_selected_page['caption2'];

if (isset($_OPTIONS['TEMPLATE']))
    if ($_OPTIONS['TEMPLATE'])
        $template['name'] = $_OPTIONS['TEMPLATE'];

if (file_exists(FOLDER_ROOT . "/site/templates/" . $template['name'] . "/header.php"))
    include (FOLDER_ROOT . "/site/templates/" . $template['name'] . "/header.php");
else
    include (FOLDER_ROOT . "/engine/templates/" . $template['name'] . "/header.php");

$page_text = str_replace('src="upload', 'src="/upload', $page_text);
echo $page_text;

if (file_exists(FOLDER_ROOT . "/site/templates/" . $template['name'] . "/footer.php"))
    include (FOLDER_ROOT . "/site/templates/" . $template['name'] . "/footer.php");
else
    include (FOLDER_ROOT . "/engine/templates/" . $template['name'] . "/footer.php");



$_DEBUG['Время генерации страницы'] = $GTIMER1->Stop();

if ($_GET['debug'])
    if ($h_info) {
        ?>
        <HR><BR>
        <?
        preprint($_DEBUG);
    };
?>