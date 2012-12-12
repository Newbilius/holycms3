<?php
include_once("_base_menu.php");
require_once(dirname(dirname(__FILE__)) . "/site/config.php");
if (file_exists(dirname(dirname(__FILE__)) . "/site/options.php"))
    require_once(dirname(dirname(__FILE__)) . "/site/options.php");

$_holy_vers = file_get_contents($_SERVER['DOCUMENT_ROOT'] . "/engine/VERSION");

$host_temp = explode(".", $_SERVER['HTTP_HOST']);
if (($host_temp[0] == "www") || ($host_temp[0] == "local"))
    $_OPTIONS['LANG'] = $host_temp[1];
else
    $_OPTIONS['LANG'] = $host_temp[0];

if (!isset($_OPTIONS['back_email']))
    $_OPTIONS['back_email'] = "info@" . $_SERVER['HTTP_HOST'];

require_once(dirname(dirname(__FILE__)) . "/site/config.php");

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
define("URI_ENGINE", "/engine/");
define("FOLDER_ENGINE", $_SERVER["DOCUMENT_ROOT"] . "/engine/");

if (!isset($_log_name))
    $_log_name = FOLDER_ROOT . "/site/log.txt";

setlocale(LC_TIME, 'RUS');

if (!isset($_CONFIG['CACHE_SYSTEM']))
    $_CONFIG['CACHE_SYSTEM'] = false;

if (!isset($_CONFIG['CACHE_MODE'])){
        $_CONFIG['CACHE_MODE']="base";
    }


if (isset($_CONFIG['LOGIN'])) {
    if (!isset($_CONFIG['SERVER']))
        $_CONFIG['SERVER'] = "localhost";
    if ($_CONFIG['SERVER'] == "")
        $_CONFIG['SERVER'] = "localhost";

    if (!isset($_CONFIG['CODEPAGE']))
        $_CONFIG['CODEPAGE'] = "utf8";
    if (!isset($_CONFIG['COLLATE']))
        $_CONFIG['COLLATE'] = "utf8_general_ci";

    @$link = mysql_connect($_CONFIG['SERVER'], $_CONFIG['LOGIN'], $_CONFIG['PASS']) or die("Проблемы подключения к базе данных - сайт не может функционировать!");
    @mysql_select_db($_CONFIG['BASE']) or die("Нет такой базы.");
    mysql_query("SET NAMES " . $_CONFIG['CODEPAGE']);
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