<?
if (!isset($_GET['dblock'])) die("не выбран конкретный блок");

global $force_filter;
if ($force_filter){
    $_force_filter_name=$_GET['force_filter_name'];
    $_force_filter_value=$_GET['force_filter_value'];
    $_GET[$_force_filter_name]=$_force_filter_value;
    $_POST[$_force_filter_name]=$_force_filter_value;
    $_REQUEST[$_force_filter_name]=$_force_filter_value;
};

if ((!$H_USER->IsAdmin()) && (!$H_USER->CanAdd($_GET['dblock'])))
SystemAlertFatal("Недостаточно прав.");

global $_global_bread;
$block= new DBlock();
$tmp=$block->GetByID($_GET['dblock']);
$tmp_block=$tmp;
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
	$form= new HFolderFormAdd(Array('table'=>$_GET['dblock']));

	$fields=new DBlockFields();
	$types= new DBlockTypes();
$show_code=true;
if (!isset($tmp_block['hide_code'])) $tmp_block['hide_code']=0;
if ($tmp_block['hide_code'])
	$show_code=false;
	
	$form->Add(Array("name"=>"caption","caption"=>"Название","type"=>"short_text",'required'=>1));
	if ($show_code)
	$form->Add(Array("name"=>"name","caption"=>"Код","type"=>"short_text",'required'=>1));
	$form->Add(Array("name"=>"sort","caption"=>"Сортировка","type"=>"sort"));

	//$fields->sql->debug=true;
	$fields->GetListByBlock($_GET['dblock'],Array("owner_type>"=>"1"));

	$form->return_link="elements_list.php?dblock=".$_GET['dblock'].$force_filter;
	if (isset($_GET['parent']))
		$form->return_link.="&parent=".$_GET['parent'];
		
	while ($data=$fields->GetNext())
		{
		if (!isset($data['meta']))
			$data['meta']=0;
		$form->Add(Array("name"=>$data['name'],"caption"=>$data['caption'],"type"=>$types->GetNameByID($data['type']),"add_values"=>$data['add_values'],'required'=>$data['required'],'meta'=>$data['meta'],'multiple'=>$data['multiple']));
		};
	
	if ($form->Draw())
		{
			$element= new DBlockElement(Array("table"=>$_GET['dblock']));
			$_POST['parent']=$_GET['parent'];
			$_POST['folder']=1;
			//PrePrint($_POST);
			if (isset($_POST['name']))
			$_POST['name']=rus2translit($_POST['name']);
                        $_tmp=$element->GetByID($element->sql->last_id);
			JournalAdd(Array(
				"block_name"=>$_GET['dblock'],
				"item_id"=>$element->sql->last_id,
				"data_after"=>serialize($_tmp),
				"folder"=>1,
			));
			
			$element->Add($_POST);
			?>
				<span style="color:green;">
					Элемент добавлен
				</span>
				<script>
window.location='<?=$form->return_link?>'
</script>
			<?
		};
?>&nbsp;