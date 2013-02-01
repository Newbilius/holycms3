<?php
include_once("_base_menu.php");
require_once(dirname(dirname(__FILE__)) . "/site/config.php");

$_holy_vers = file_get_contents($_SERVER['DOCUMENT_ROOT'] . "/engine/VERSION");

$host_temp = explode(".", $_SERVER['HTTP_HOST']);
if (($host_temp[0] == "www") || ($host_temp[0] == "local"))
    $_OPTIONS['LANG'] = $host_temp[1];
else
    $_OPTIONS['LANG'] = $host_temp[0];

if (!isset($_OPTIONS['back_email']))
    $_OPTIONS['back_email'] = "info@" . $_SERVER['HTTP_HOST'];

require_once(dirname(dirname(__FILE__)) . "/site/config.php");
//@todo переписать циклом
defined('FOLDER_ROOT') or define("FOLDER_ROOT", $_SERVER["DOCUMENT_ROOT"]);
defined('FOLDER_UPLOAD') or define("FOLDER_UPLOAD", $_SERVER["DOCUMENT_ROOT"] . "/upload/");
defined('URI_UPLOAD') or define("URI_UPLOAD", "/upload/");
defined('FOLDER_IMAGE') or define("FOLDER_IMAGE", $_SERVER["DOCUMENT_ROOT"] . "/upload/pics/");
defined('URI_IMAGE') or define("URI_IMAGE", "/upload/pics/");
defined('FOLDER_FILES') or define("FOLDER_FILES", $_SERVER["DOCUMENT_ROOT"] . "/upload/files/");
defined('URI_FILES') or define("URI_FILES", "/upload/files/");

defined('FOLDER_SITE') or define("FOLDER_SITE", $_SERVER["DOCUMENT_ROOT"] . "/site/");
defined('FOLDER_ADDONS_SITE') or define("FOLDER_ADDONS_SITE", $_SERVER["DOCUMENT_ROOT"] . "/site/addons/");
defined('FOLDER_ADDONS_ENGINE') or define("FOLDER_ADDONS_ENGINE", $_SERVER["DOCUMENT_ROOT"] . "/engine/addons/");
defined('FOLDER_ADMIN') or define("FOLDER_ADMIN", $_SERVER["DOCUMENT_ROOT"] . "/engine/admin/");
defined('URI_ADMIN') or define("URI_ADMIN", "/engine/admin/");
defined('URI_ENGINE') or define("URI_ENGINE", "/engine/");
defined('FOLDER_ENGINE') or define("FOLDER_ENGINE", $_SERVER["DOCUMENT_ROOT"] . "/engine/");

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

if (file_exists(dirname(dirname(__FILE__)) . "/site/options.php"))
    require_once(dirname(dirname(__FILE__)) . "/site/options.php");

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