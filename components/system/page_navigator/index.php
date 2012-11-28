<? 
if (!defined('HCMS')) die();
global $_global_bread;

//PrePrint($_global_bread);
$params['page_max']=Ceil($params['max_count']/$params['count']);
if (!isset($params['base_link'])) $params['base_link']="";
if (!isset($params['page_param'])) $params['page_param']="page";
//$params['page_param1']="page";


//PrePrint($params);

if ($params['page_max']>1)
include($full_template_path);
?>