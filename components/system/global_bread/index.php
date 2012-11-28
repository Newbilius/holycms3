<? 
if (!defined('HCMS')) die();
global $_global_bread;

if (!isset($_global_bread)) $_global_bread=Array();

//PrePrint($_global_bread);
include($full_template_path);
?>