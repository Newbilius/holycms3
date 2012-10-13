<?
$block= new DBlock();
$_GET['dblock']="journal";
$tmp_block=$block->GetByID($_GET['dblock']);
$show_folders=false;
$show_code=false;
$user_ifno_holy=$H_USER->GetInfo();

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
$_global_bread[]=Array($tmp['caption'],"/engine/admin/journal.php?dblock=".$_GET['dblock']);
		
	if (!isset($_GET['page'])) $_GET['page']=1;
	if (!isset($_GET['parent'])) $_GET['parent']=0;
	if (!isset($_GET['filter'])) $_GET['filter']=Array();
	
	
	$fields=new DBlockFields();
	if ($_GET['parent']!=-1)
	$_GET['filter']['parent']=$_GET['parent'];
	
	$table= new HElementForm(Array("table"=>$_GET['dblock'],
	"edit_link"=>"journal_view.php?dblock=".$_GET['dblock']."&id=#ID#&parent=".$_GET['parent'],
	"filter"=>$_GET['filter'],
	
	"show_folders"=>$show_folders,
	"show_code"=>$show_code,
        "hide_group_action"=>true,
        "show_count_list"=>true,
        "folder_link"=>false,
	));
	
	if (!isset($_GET['page_count']))
		$_GET['page_count']=10;
	if (!isset($_GET['page']))
		$_GET['page']=1;
	
	$table->SetPaginator($_GET['page_count'],$_GET['page']);
	
	$table->base_link='journal.php?dblock='.$_GET['dblock'];
		
	$types= new DBlockTypes();
	
	$fields->GetListByBlock($_GET['dblock']);
	
	$table->Add(Array("name"=>"item_id","caption"=>"ID элемента","type"=>"short_text"));
        $table->Add(Array("name"=>"action","caption"=>"Действие","type"=>"journal_text"));
        $table->Add(Array("name"=>"date_time","caption"=>"Дата","type"=>"short_text"));
        $table->Add(Array("name"=>"block_name","caption"=>"Data-блок","type"=>"list","add_values"=>"system_data_block;name;caption"));
        $table->Add(Array("name"=>"user_id","caption"=>"Пользователь","type"=>"list","add_values"=>"users;id;caption"));
        
        
        
        

	$table->Draw();
?>&nbsp;