<?
if (!isset($_GET['id'])) die("не выбран элемент");

global $force_filter;
if ($force_filter){
    $_force_filter_name=$_GET['force_filter_name'];
    $_force_filter_value=$_GET['force_filter_value'];
    $_GET[$_force_filter_name]=$_force_filter_value;
    $_POST[$_force_filter_name]=$_force_filter_value;
    $_REQUEST[$_force_filter_name]=$_force_filter_value;
};

if ((!$H_USER->IsAdmin()) && (!$H_USER->CanEdit($_GET['dblock'])))
SystemAlertFatal("Недостаточно прав.");

global $_global_bread;
$block= new DBlock();
$tmp=$block->GetByID($_GET['dblock']);

$show_folders=true;
$show_code=true;
$tmp_block=$tmp;
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
	$form->return_link="elements_list.php?dblock=".$_GET['dblock'].$force_filter;
	
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

	$fields->GetListByBlock($_GET['dblock'],Array("owner_type>"=>"1"));
	
	while ($data=$fields->GetNext())
		{
		if (!isset($data['meta']))
			$data['meta']=0;
		$form->Add(Array("name"=>$data['name'],"caption"=>$data['caption'],"type"=>$types->GetNameByID($data['type']),"add_values"=>$data['add_values'],'required'=>$data['required'],'meta'=>$data['meta'],'multiple'=>$data['multiple']));
		};

					
	if ($form->GO())
		{
			$element= new DBlockElement(Array("table"=>$_GET['dblock']));
			//$element->sql->debug=true;
			//preprint($_POST);
			if (!isset($_POST['folder']))
				$_POST['folder']=0;
				else
				$_POST['folder']=1;
			if (!isset($_GET['parent'])) $_GET['parent']=0;
			$_POST['parent']=$_GET['parent'];
			$returns_now=false; if ($_POST['submit']=="Сохранить") $returns_now=true;
			unset($_POST['submit']);
			
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