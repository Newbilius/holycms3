<?php

if (!isset($_log_name))
    $_log_name = $_SERVER['DOCUMENT_ROOT'] . "/log.txt";

$_holy_vers = "3.0";

$host_temp = explode(".", $_SERVER['HTTP_HOST']);
if (($host_temp[0] == "www") || ($host_temp[0] == "local"))
    $_OPTIONS['LANG'] = $host_temp[1];
else
    $_OPTIONS['LANG'] = $host_temp[0];


$_top_menu['utilits'] = array("caption" => "�������", "parent" => "");
$_top_menu['options'] = array("caption" => "���������", "parent" => "");

$_top_menu['file_manager'] = Array(
    "url" => "/engine/admin/file_manager.php",
    "caption" => "�������� ��������",
    "admin_right" => true,
    "parent" => "utilits",
);
$_top_menu['sql_backup'] = Array(
    "url" => "/engine/admin/sql_backup.php",
    "caption" => "���������� ��������",
    "admin_right" => false,
    "parent" => "utilits",
);
$_top_menu['journal'] = Array(
    "url" => "/engine/admin/journal.php",
    "caption" => "������ ���������������� ��������",
    "admin_right" => false,
    "parent" => "utilits",
);

$_top_menu['options_simple'] = Array(
    "url" => "/engine/admin/options.php",
    "caption" => "����� �����",
    "parent" => "options",
);
$_top_menu['options_seo'] = Array(
    "url" => "/engine/admin/options_seo.php",
    "caption" => "SEO ����� �����",
    "parent" => "options",
);
$_top_menu['line1'] = Array(
    "caption" => "-",
    "parent" => "options",
);
$_top_menu['clear_cache'] = Array(
    "url" => "/engine/admin/clear_cache.php",
    "caption" => "������� ����",
    "parent" => "options",
);


//���� �� ������ ����� ��������
$_menu1['before'] = array();
$_menu1['after'] = array(
);

//���� �� ������ ����� ��������
$_menu2['before'] = array();
$_menu2['after'] = array(
    Array("/engine/admin/types.php", "���� �����"),
);

//��������� �����
$_menu_best['before'] = array();
$_menu_best['after'] = array();

$_standard_element_fields = Array(
    array(
        "caption" => "����",
        "code" => "sdate",
        "type" => "ddatetime",
        "multi" => false,
        "folder" => false,
        "not_in_list" => false,
        "meta" => false,
    ),
    array(
        "caption" => "������� �����",
        "code" => "preview_text",
        "type" => "wysiwyg_text",
        "multi" => false,
        "folder" => false,
        "not_in_list" => true,
        "meta" => false,
    ),
    array(
        "caption" => "��������� �����",
        "code" => "detail_text",
        "type" => "wysiwyg_html",
        "multi" => false,
        "folder" => false,
        "not_in_list" => true,
        "meta" => false,
    ),
    array(
        "caption" => "����",
        "code" => "cost",
        "type" => "double_integ",
        "multi" => false,
        "folder" => false,
        "not_in_list" => false,
        "meta" => false,
    ),
    array(
        "caption" => "����",
        "code" => "foto",
        "type" => "image",
        "multi" => false,
        "folder" => false,
        "not_in_list" => false,
        "meta" => false,
    ),
    array(
        "caption" => "�������������� ����",
        "code" => "foto_multi",
        "type" => "image",
        "multi" => true,
        "folder" => false,
        "not_in_list" => true,
        "meta" => false,
    ),
    array(
        "caption" => "����-�����������",
        "code" => "spec1",
        "type" => "checkbox",
        "multi" => false,
        "folder" => false,
        "not_in_list" => false,
        "meta" => false,
    ),
);


if (!isset($_OPTIONS['back_email']))
    $_OPTIONS['back_email'] = "info@" . $_SERVER['HTTP_HOST'];
define("FOLDER_ROOT", $_SERVER["DOCUMENT_ROOT"]);
define("FOLDER_UPLOAD", $_SERVER["DOCUMENT_ROOT"] . "/upload/");
define("URI_UPLOAD", "/upload/");
define("FOLDER_IMAGE", $_SERVER["DOCUMENT_ROOT"] . "/upload/pics/");
define("URI_IMAGE", "/upload/pics/");
define("FOLDER_FILES", $_SERVER["DOCUMENT_ROOT"] . "/upload/files/");
define("URI_FILES", "/upload/files/");
define("FOLDER_SITE", $_SERVER["DOCUMENT_ROOT"] . "/site/");
define("FOLDER_ADMIN", $_SERVER["DOCUMENT_ROOT"] . "/engine/admin/");
define("URI_ADMIN", "/engine/admin/");
define("FOLDER_ENGINE", $_SERVER["DOCUMENT_ROOT"] . "/engine/");

setlocale(LC_ALL, 'ru_RU', 'rus_RUS', 'Russian');

require_once($_SERVER["DOCUMENT_ROOT"] . "/site/config.php");
if (file_exists($_SERVER["DOCUMENT_ROOT"] . "/site/options.php"))
    require_once($_SERVER["DOCUMENT_ROOT"] . "/site/options.php");

if (!isset($_CONFIG['CACHE_SYSTEM']))
    $_CONFIG['CACHE_SYSTEM'] = false;


if (isset($_CONFIG['LOGIN'])) {
    if (!isset($_CONFIG['SERVER']))
        $_CONFIG['SERVER'] = "localhost";
    if ($_CONFIG['SERVER'] == "")
        $_CONFIG['SERVER'] = "localhost";

    @$link = mysql_connect($_CONFIG['SERVER'], $_CONFIG['LOGIN'], $_CONFIG['PASS']) or die("�������� ����������� � ���� ������ - ���� �� ����� ���������������!");
    @mysql_select_db($_CONFIG['BASE']) or die("��� ����� ����.");
    mysql_query("SET NAMES cp1251");
};

require_once("api/holy_api.php");
POSTPreWork();

$H_USER = new DUser(1);
$h_info = $H_USER->GetID();

if (isset($_GET['nocache']))
    if ($h_info)
        $_CONFIG['CACHE_SYSTEM'] = false;

if (isset($_GET['clear_cache']))
    if ($h_info) {
        $tmp_cache = new HolyCacheOut();
        $tmp_cache->ClearFull();
        $_CONFIG['CACHE_SYSTEM'] = false;
    };
if (isset($_GET['exit']))
    if ($_GET['exit'] == 1) {
        $H_USER->Logout();
        header("Location: /engine/admin/");
    };
?>