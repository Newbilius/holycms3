<script>
function specselect_item(id,block)
{
$.get('/engine/admin/ajax/spec/specselect_item.php?block='+block+'&spec_id='+id, function(data){

$.fancybox(data);
});
}
function specselect_item_complete(id,value)
{
$("#"+id).val(value);
//alert(id);
parent.$.fancybox.close();
}
</script>
<?
if (!isset($_GET['id'])) die("не выбран элемент");

if ((!$H_USER->IsAdmin()) && (!$H_USER->CanEdit($_GET['dblock'])))
SystemAlertFatal("Недостаточно прав.");

$user_ifno_holy=$H_USER->GetInfo();

$block= new DBlock();
$tmp_block=$block->GetByID($_GET['dblock']);

$show_folders=true;
$show_code=true;
if (!isset($tmp_block['hide_folders'])) $tmp_block['hide_folders']=0;
if (!isset($tmp_block['hide_folders2'])) $tmp_block['hide_folders2']=0;
if (!isset($tmp_block['hide_code'])) $tmp_block['hide_code']=0;
if ($tmp_block['hide_folders'])
	$show_folders=false;
if (($tmp_block['hide_folders2']) && (isset($_GET['parent'])))
	if ($_GET['parent']!=0)
	$show_folders=false;
if ($tmp_block['hide_code'])
	$show_code=false;

if (!$user_ifno_holy['block_control']) 
{
$not_editor_blocks=array("users","modules","cms_options");
if (in_array($_GET['dblock'],$not_editor_blocks))
die("недостаточно прав");


}
global $_global_bread;
$block= new DBlock();
$tmp=$block->GetByID($_GET['dblock']);
$_global_bread[]=Array($tmp['caption'],"/engine/admin/elements_list.php?dblock=".$_GET['dblock']);

if (isset($_GET['parent']))
if ($_GET['parent']!=0)
{
$menu_pages=new DBlockElement($_GET['dblock']);
$menu_pages_tmp=$menu_pages->GetByID($_GET['parent']);
$tmp_bread[]=$menu_pages_tmp;

if (isset($menu_pages_tmp['parent']))
while ($menu_pages_tmp['parent']!=0)
	{
		$menu_pages_tmp=$menu_pages->GetByID($menu_pages_tmp['parent']);
		$tmp_bread[]=$menu_pages_tmp;
		if (!isset($menu_pages_tmp['parent'])) $menu_pages_tmp['parent']=0;
	};
$tmp_bread=array_reverse($tmp_bread);
foreach ($tmp_bread as $tmp)
if (isset($tmp['caption']))
	$_global_bread[]=Array($tmp['caption'],"/engine/admin/elements_list.php?dblock=".$_GET['dblock']."&parent=".$tmp['id']);
};

	$gr= new DBlock();
	$form= new HElementFormEdit(Array('table'=>$_GET['dblock'],'id'=>$_GET['id']));
	$form->return_link="elements_list.php?dblock=".$_GET['dblock'];
	
	if (isset($_GET['parent']))
		$form->return_link.="&parent=".$_GET['parent'];
		
	$fields=new DBlockFields();
	$types= new DBlockTypes();

	$form->Add(Array("name"=>"caption","caption"=>"Название","type"=>"short_text",'required'=>1));
	if ($show_code)
	$form->Add(Array("name"=>"name","caption"=>"Код","type"=>"short_text",'required'=>1));
	$form->Add(Array("name"=>"sort","caption"=>"Сортировка","type"=>"sort"));
	if ($show_folders)
	$form->Add(Array("name"=>"folder","caption"=>"Папка","type"=>"checkbox"));

	$fields->GetListByBlock($_GET['dblock'],Array("not_element"=>"0"));
	
	while ($data=$fields->GetNext())
		{
		$visible=true;
		if ((!$user_info['block_control']) && ($data['admin_only']))
			$visible=false;
		if (!isset($data['meta']))
			$data['meta']=0;
		$form->Add(Array("name"=>$data['name'],"caption"=>$data['caption'],"type"=>$types->GetNameByID($data['type']),"add_values"=>$data['add_values'],'required'=>$data['required'],"multiple"=>$data['multiple'],"visible"=>$visible,'meta'=>$data['meta']));
		};

					
	if ($form->GO())
		{
			$element= new DBlockElement(Array("table"=>$_GET['dblock']));
			//$element->sql->debug=true;
			if (!isset($_GET['parent'])) $_GET['parent']=0;
			$returns_now=false; if ($_POST['submit']=="Сохранить") $returns_now=true;
			unset($_POST['submit']);
			//if (!isset($_POST['pass'])) unset($_POST['pass']);
			//if (isset($_POST['pass'])) if ($_POST['pass']=="") unset($_POST['pass']);
			$_POST['parent']=$_GET['parent'];
			//print_r($_POST);
			//$element->sql->debug=true;
			
			if (isset($_POST['name']))
			$_POST['name']=rus2translit($_POST['name']);
			
			$element->Update($_GET['id'],$_POST);
			
                        $_del_tmp=$element->GetByID($_GET['id']);
			JournalUpdate(Array(
				"block_name"=>$_GET['dblock'],
				"item_id"=>$_GET['id'],
				"data_before"=>serialize($form->data),
				"data_after"=>serialize($_del_tmp),
				"folder"=>0,
			));
                        
			if (file_exists(FOLDER_ROOT."/site/engine/admin/".$_GET['dblock']."_edit.php"))
				include_once(FOLDER_ROOT."/site/engine/admin/".$_GET['dblock']."_edit.php");
			?>
				<span style="color:green;">
					Элемент изменён
				</span>
				<? if ($returns_now){?>
				<script>
$(document).ready(function () {
window.location='<?=$form->return_link?>'
});
</script>
<?};?>
			<?
			$form->Reload();
		};
	$form->Draw();
?>&nbsp;