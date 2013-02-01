<?php
if (!defined('HCMS')) die();

require_once("xmltoarray_holy.php");
require_once("tools_holy.php");
require_once("components_holy.php");
require_once("components_holy_old.php");    //старый принцип подключения компонентов
require_once("pic_resize_holy.php");
require_once("sql_holy.php");
require_once("mail_holy.php");
require_once("timer_holy.php");
require_once("stepbystep_holy.php");
require_once("cache_holy.php");
require_once("backup_holy.php");
require_once("rss_holy.php");
require_once("tags_holy.php");
require_once("debug_holy.php");
require_once("cookie_holy.php");
require_once("validator_holy.php");
require_once("datetime_holy.php");
require_once("arrays_holy.php");
require_once("files_holy.php");
require_once("old_holy.php");
include_once("forms/text.php");
require_once("view_holy.php");

require_once("dblock/DBaseClass.php");
require_once("dblock/DBlockGroup.php");
require_once("dblock/DBlock.php");
require_once("dblock/DBlockTypes.php");
require_once("dblock/DBlockFields.php");
require_once("dblock/DBlockElement.php");

require_once("user_holy.php");

require_once("admin/table_forms_base.php");
require_once("admin/edit_forms_base.php");
require_once("admin/add_forms_base.php");
require_once("admin/add_forms_element.php");
require_once("admin/type_forms_base.php");
require_once("admin/edit_forms_element.php");
require_once("admin/add_forms_folder.php");
require_once("admin/table_forms_elements.php");

require_once("php_mailer/class.phpmailer.php");
require_once("php_mailer/class.pop3.php");
require_once("php_mailer/class.smtp.php");
?>