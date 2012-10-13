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
parent.$.fancybox.close();
}
</script>
<?
if (!isset($_GET['dblock'])) die("не выбран конкретный блок");

$block= new DBlock();
$tmp_block=$block->GetByID($_GET['dblock']);

$show_code=true;
if (!isset($tmp_block['hide_code'])) $tmp_block['hide_code']=0;
if ($tmp_block['hide_code'])
	$show_code=false;

$user_ifno_holy=$H_USER->GetInfo();
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
	$form= new HElementFormAdd(Array('table'=>$_GET['dblock']));

	$fields=new DBlockFields();
	$types= new DBlockTypes();
	
	$form->Add(Array("name"=>"caption","caption"=>"Название","type"=>"short_text",'required'=>1));
	if ($show_code)
	$form->Add(Array("name"=>"name","caption"=>"Код","type"=>"short_text",'required'=>1));
	$form->Add(Array("name"=>"sort","caption"=>"Сортировка","type"=>"sort"));

	$form->return_link="elements_list.php?dblock=".$_GET['dblock'];
	if (isset($_GET['parent']))
		$form->return_link.="&parent=".$_GET['parent'];

	$fields->GetListByBlock($_GET['dblock'],Array("not_element"=>"0"));
	
	while ($data=$fields->GetNext())
		{
		$visible=true;
		
		if ((!$user_info['block_control']) && ($data['admin_only']))
			$visible=false;
		if (!isset($data['meta']))
			$data['meta']=0;
		$form->Add(Array("name"=>$data['name'],"caption"=>$data['caption'],"type"=>$types->GetNameByID($data['type']),"add_values"=>$data['add_values'],'required'=>$data['required'],'multiple'=>$data['multiple'],"visible"=>$visible,'meta'=>$data['meta']));
		};
	
	if ($form->Draw())
		{
			$element= new DBlockElement(Array("table"=>$_GET['dblock']));
			if (!isset($_GET['parent'])) $_GET['parent']=0;
			$_POST['parent']=$_GET['parent'];
			
			if (isset($_POST['name']))
			$_POST['name']=rus2translit($_POST['name']);
			
			$element->Add($_POST);
			
			JournalAdd(Array(
				"block_name"=>$_GET['dblock'],
				"item_id"=>$element->sql->last_id,
				"data_after"=>serialize($_POST),
				"folder"=>0,
			));
			
			if (file_exists($_SERVER['DOCUMENT_ROOT']."/site/engine/admin/".$_GET['dblock']."_add.php"))
				include_once($_SERVER['DOCUMENT_ROOT']."/site/engine/admin/".$_GET['dblock']."_add.php");
			?>
				<span style="color:green;">
					Элемент добавлен 
					<?=$form->return_link?>
				</span>
				<script>


window.location='<?=$form->return_link?>';


</script>
			<?
		};
?>&nbsp;