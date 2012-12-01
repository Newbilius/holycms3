<?
echo require_once(realpath(str_replace("\\","/",dirname(dirname(dirname(__FILE__)))."/engine.php")));

//PrePrint($_GET);

if (isset($_GET['table'])){
if (intval($_GET['sort'])>0)
if (intval($_GET['id'])>0){
$table=new HolySQL($_GET['table']);
//$table->debug=true;
$table->Update("id=".$_GET['id'],Array("sort"=>$_GET['sort']));
}};
?>