<?
if (!isset($_GET['parent']))
	$_GET['parent']=0;
if ($_GET['parent']=="") $_GET['parent']=0;

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
	
if (!isset($_GET['dblock'])) die("�� ������ ���������� ����");
$user_ifno_holy=$H_USER->GetInfo();
if (!$user_ifno_holy['block_control']) 
{
$not_editor_blocks=array("users","modules","cms_options");
if (in_array($_GET['dblock'],$not_editor_blocks))
die("������������ ����");


}
function SaveOptions2()
{
$sdata=new DBlockElement("cms_options");
$data=$sdata->GetOne("opt_block='".$_GET['dblock']."'");


if (!isset($data['id']))
	$data['id']=0;

if (!isset($_GET['page_count']))
	$_GET['page_count']=10;
	
if ($data['id']==0)
	{
		$sdata->Add(Array(
		"name"=>$_GET['dblock'],
		"caption"=>$_GET['dblock'],
		"opt_block"=>$_GET['dblock'],
		"opt_count"=>$_GET['page_count'],
		));
	}
	else
	{
		$sdata->Update($_GET['dblock'],Array(
		"opt_count"=>$_GET['page_count'],
		),false);
	};
};

function SaveOptions()
{
$sdata=new DBlockElement("cms_options");
$data=$sdata->GetOne("opt_block='".$_GET['dblock']."'");

if (!isset($data['id']))
	$data['id']=0;
	
if ($data['id']==0)
	{
	if ($_GET['sort_by']=="") $_GET['sort_by']="sort";
	if ($_GET['sort_direct']=="") $_GET['sort_direct']="ASC";
		$sdata->Add(Array(
		"name"=>$_GET['dblock'],
		"caption"=>$_GET['dblock'],
		"opt_block"=>$_GET['dblock'],
		"opt_sort"=>$_GET['sort_by'],
		"opt_by"=>$_GET['sort_direct'],
		));
	}
	else
	{
		$sdata->Update($_GET['dblock'],Array(
		"opt_sort"=>$_GET['sort_by'],
		"opt_by"=>$_GET['sort_direct'],
		),false);
	};
};

function LoadOptions2()
{
if (!isset($_GET['page_count']))
	{
		$sdata=new DBlockElement("cms_options");
		$data=$sdata->GetOne("opt_block='".$_GET['dblock']."'");

		if (isset($data['id']))
		if ($data['id']>0)
		if ($_GET['dblock']!=""){

		if (intval($data['opt_count'])>0)
		$_GET['page_count']=$data['opt_count'];
		
		};
	};
};

function LoadOptions()
{
if (!isset($_GET['sort_by']))
	{
		$sdata=new DBlockElement("cms_options");
		$data=$sdata->GetOne("opt_block='".$_GET['dblock']."'");

		if (isset($data['id']))
		if ($data['id']>0)
		if ($_GET['dblock']!=""){

		$_GET['dblock']=$data["opt_block"];
		if (isset($data['opt_sort']))
		if ($data['opt_sort']!="")
		$_GET['sort_by']=$data["opt_sort"];
		
		if (isset($data['opt_by']))
		if ($data['opt_by']!="")
		$_GET['sort_direct']=$data["opt_by"];
		
		};
	};
};



if (isset($_GET['sort_by'])) SaveOptions(); else LoadOptions();
if (isset($_GET['page_count'])) SaveOptions2(); else LoadOptions2();


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

if (isset($_POST['what_to_do']))
if (isset($_POST['id'][0]))
	{
	if ($_POST['what_to_do']=="del")
		{
			$gr= new DBlockElement(Array("table"=>$_GET['dblock']));
			if (is_array($_POST['id']))
			foreach ($_POST['id'] as $fid)
                        {
                            $gr->Delete($fid);
                            $_del_tmp=$gr->GetByID($fid);
                            JournalDelete(Array(
				"block_name"=>$_GET['dblock'],
				"item_id"=>$fid,
				"data_after"=>serialize($_del_tmp),
                            ));
                        }
		};
	if ($_POST['what_to_do']=="move")
		{
			$gr= new DBlockElement(Array("table"=>$_GET['dblock']));
			foreach ($_POST['id'] as $fid)
                        {
                        $_del_tmp=$gr->GetByID($fid);
                        $_del_tmp2=$_del_tmp;
                        $_del_tmp2["parent"]=$_POST['where_move'];
			JournalUpdate(Array(
				"block_name"=>$_GET['dblock'],
				"item_id"=>$fid,
				"data_before"=>serialize($_del_tmp),
				"data_after"=>serialize($_del_tmp2),
				"folder"=>$_del_tmp['folder'],
			));
				$gr->Update($fid,Array("parent"=>$_POST['where_move']),false);
                        };
		};	
	};

	if (isset($_GET['delete']))
		{
			$gr= new DBlockElement(Array("table"=>$_GET['dblock']));
			$_del_tmp=$gr->GetByID($_GET['delete']);
                        //preprint($_del_tmp);
                        //die();
                        $gr->Delete($_GET['delete']);
                        JournalDelete(Array(
				"block_name"=>$_GET['dblock'],
				"item_id"=>$_GET['delete'],
				"data_after"=>serialize($_del_tmp),
                        ));
			?>
				<span style="color:green;">
					������� <?=$_GET['delete']?> �����
				</span>
			<?
		};
		
	if (!isset($_GET['page'])) $_GET['page']=1;
	if (!isset($_GET['parent'])) $_GET['parent']=0;
	if (!isset($_GET['filter'])) $_GET['filter']=Array();
	
	
	$fields=new DBlockFields();
	if ($_GET['parent']!=-1)
	$_GET['filter']['parent']=$_GET['parent'];
	
	$table= new HElementForm(Array("table"=>$_GET['dblock'],
	"edit_link"=>"element_edit.php?dblock=".$_GET['dblock']."&id=#ID#&parent=".$_GET['parent'],
	"add_link"=>"element_add.php?dblock=".$_GET['dblock']."&parent=".$_GET['parent'],
	"add_link2"=>"folder_add.php?parent=".$_GET['parent']."&dblock=".$_GET['dblock'],
	"delete_link"=>"?dblock=".$_GET['dblock']."&delete=#ID#",
	"filter"=>$_GET['filter'],
	
	"show_folders"=>$show_folders,
	"show_code"=>$show_code,
	));
	
	if (!isset($_GET['page_count']))
		$_GET['page_count']=10;
	if (!isset($_GET['page']))
		$_GET['page']=1;
	
	$table->SetPaginator($_GET['page_count'],$_GET['page']);
	
	$table->base_link='elements_list.php?dblock='.$_GET['dblock'];

		if (isset($_GET['parent']))
		$table->delete_link_base.="&parent=".$_GET['parent'];
		
	$types= new DBlockTypes();
	
	$fields->GetListByBlock($_GET['dblock']);
	
	$table->Add(Array("name"=>"sort","caption"=>"����������","type"=>"sort"));
	$table->Add(Array("name"=>"id","caption"=>"ID","type"=>"short_text"));
	if ($show_code)
	$table->Add(Array("name"=>"name","caption"=>"���","type"=>"short_text"));
	$table->Add(Array("name"=>"caption","caption"=>"��������","type"=>"short_text"));
	
	while ($data=$fields->GetNext())
		{
		if ((!$data['not_list']) && (($user_info['block_control']) || (!$data['admin_only'])))
		$table->Add(Array("name"=>$data['name'],"caption"=>$data['caption'],"type"=>$types->GetNameByID($data['type']),"add_values"=>$data['add_values'],"multiple"=>$data['multiple']));
		};

	$table->Draw();
?>&nbsp;