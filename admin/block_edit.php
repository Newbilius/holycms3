<?
$user_ifno_holy=$H_USER->GetInfo();
if (!$user_ifno_holy['block_control']) die("недостаточно прав");
$return=false;
if (!isset($_GET['group'])) die("не выбрана группа блоков");
if (!isset($_GET['dblock'])) die("не выбран конкретный блок");

global $_global_bread;
$_global_bread[]=Array("Блоки данных","/engine/admin/group_list.php");
		$block = new DBlockGroup();
		
		if (!isset($_GET['group'])) $_GET['group']="";
		$tmp=$block->GetByID($_GET['group']);
$_global_bread[]=Array($tmp['caption'],"/engine/admin/blocks_list.php?group=".$_GET['group']);
		
		$block = new DBlock();
		$tmp=$block->GetByID($_GET['dblock']);
$_global_bread[]=Array($tmp['caption'],"/engine/admin/block_edit.php?group=".$_GET['group']."&dblock=".$_GET['dblock']);

	if (!isset($_GET['page'])) $_GET['page']=1;
	if (!isset($_GET['filter'])) $_GET['filter']=Array();
	$form= new HFormEdit(Array('table'=>"system_data_block",'id'=>$_GET['dblock']));
	$form->return_link="blocks_list.php?group=".$_GET['group'];

	$form->Add(Array("name"=>"caption","caption"=>"Название","type"=>"short_text",'required'=>true));
	$form->Add(Array("name"=>"name","caption"=>"Код","type"=>"short_text",'required'=>true));
	$form->Add(Array("name"=>"sort","caption"=>"Сортировка","type"=>"sort",'required'=>false));
	$form->Add(Array("name"=>"bgroup","caption"=>"Группа","type"=>"list","add_values"=>"system_data_block_group;id;caption",'required'=>true));
        $form->Add(Array("name"=>"childs","caption"=>"Связанные блоки<br>(название_блока;<br>поле_родителя;<br>поле_подчиненного/<br>название_блока;...)","type"=>"wysiwyg_text",'required'=>false));
		$form->Add(Array("name"=>"bind_cache","caption"=>"Чистить<br>следующие<br>блоки при<br>очистки кэша<br>(через ';')","type"=>"wysiwyg_text",'required'=>false));
$form->Add(Array("name"=>"fav","caption"=>"Избранный","type"=>"checkbox",'required'=>false));
$form->Add(Array("name"=>"hide_folders","caption"=>"Скрыть папки","type"=>"checkbox",'required'=>false));
$form->Add(Array("name"=>"hide_folders2","caption"=>"Скрыть папки 2го уровня","type"=>"checkbox",'required'=>false));
$form->Add(Array("name"=>"hide_code","caption"=>"Скрыть код","type"=>"checkbox",'required'=>false));

	if ($form->GO())
		{
					$returns_now=false; if ($_POST['submit']=="Сохранить") $returns_now=true;
					$returns_now=true;
					$return=true;
			unset($_POST['submit']);

			
			if (!isset($_POST["fav"])) $_POST["fav"]=0; else $_POST["fav"]=1;
			if (!isset($_POST["hide_folders"])) $_POST["hide_folders"]=0; else $_POST["hide_folders"]=1;
		if (!isset($_POST["hide_folders2"])) $_POST["hide_folders2"]=0; else $_POST["hide_folders2"]=1;
			if (!isset($_POST["hide_code"])) $_POST["hide_code"]=0; else $_POST["hide_code"]=1;
			$block->Update($_GET['dblock'],Array(
				"caption"=>$_POST["caption"],
				"name"=>$_POST["name"],
				"sort"=>$_POST["sort"],
				"bgroup"=>$_POST["bgroup"],
				"fav"=>$_POST["fav"],
				"hide_folders"=>$_POST["hide_folders"],
				"hide_folders2"=>$_POST["hide_folders2"],
				"hide_code"=>$_POST["hide_code"],
                                "childs"=>$_POST["childs"],
								"bind_cache"=>$_POST["bind_cache"],
			));
			$form->Reload();
			?>
<? if ($returns_now){?>
			<script>
$(document).ready(function () {
window.location='<?=$form->return_link?>'
});
</script>
<?};?>
			<?
		};
		if (!$return)
	$form->Draw();
?>

<? /* -------------------------------------------- */?>
<? /* split */ ?>
<script>
function SaveOn(id)
{
var need_val=$("#global_ajax_1").val()+";"+$("#global_ajax_2").val();

if ($("#global_ajax_3").val()!="")
	need_val=need_val+";"+$("#global_ajax_3").val();
$("#"+id).val(need_val);

parent.$.fancybox.close();
}

function ShowSpecialBox(id)
{

var type_name=$("#"+id).parents("tr").find(".type_select").find("option:selected").attr("name");
var vals;
vals=$("#"+id).val().split(";");


if (!vals[0]) vals[0]="";
if (!vals[1]) vals[1]="";
if (!vals[2]) vals[2]="";

$.get('/engine/admin/ajax/spec/'+type_name+'.php?spec_id='+id+'&val[0]='+vals[0]+'&val[1]='+vals[1]+'&val[2]='+vals[2], function(data){

$.fancybox(data);
});

}

</script>
<? /* -------------------------------------------- */?>
<h2>Список полей</h2>
<?
	$block= new DBlock();
	$table= new HTypeTable(Array("table"=>"system_data_block_fields","filter"=>"data_block=".$block->GetIDByName($_GET['dblock'])));

	$mega_array[]=Array("name","Код","type_text","");
	$mega_array[]=Array("caption","Название","type_text","");
	$mega_array[]=Array("type","Тип","list","system_data_block_types;id;caption;name");
	$mega_array[]=Array("sort","Сорт.","integ","");
	$mega_array[]=Array("required","Обяз.","checkbox","");
	$mega_array[]=Array("multiple","Множест.","checkbox","");
	$mega_array[]=Array("owner_type","Папка","checkbox","");
	$mega_array[]=Array("not_element","Не item","checkbox","");
	$mega_array[]=Array("not_list","Не в списке","checkbox","");
	$mega_array[]=Array("admin_only","Админ only","checkbox","");
	$mega_array[]=Array("meta","Meta","checkbox","");
	$mega_array[]=Array("add_values","Дополнительно","add_values","");
	

	foreach ($mega_array as $i=>$mg)
	$table->Add(Array("name"=>$mg[0],"caption"=>$mg[1],"type"=>$mg[2],"add_values"=>$mg[3]));
	if ($table->GO())
		{
			$tp=new DBlockFields();
			foreach ($_POST['id'] as $num=>$id)
				{
					if (!isset($_POST['name'][$num]))
						$_POST['name'][$num]="";
					if ($_POST['name'][$num]!="")
						if ($_POST['caption'][$num]!="")
							if (intval($_POST['type'][$num])!="")
								{
								if (!isset($_POST['multiple'][$num]))
									$_POST['multiple'][$num]=0;
								if (!isset($_POST['required'][$num]))
									$_POST['required'][$num]=0;
								if (!isset($_POST['owner_type'][$num]))
									$_POST['owner_type'][$num]=0;
								if (!isset($_POST['not_element'][$num]))
									$_POST['not_element'][$num]=0;
								if (!isset($_POST['not_list'][$num]))
									$_POST['not_list'][$num]=0;
								if (!isset($_POST['admin_only'][$num]))
									$_POST['admin_only'][$num]=0;
								if (!isset($_POST['meta'][$num]))
									$_POST['meta'][$num]=0;								
									
									$values=Array(
										"name"=>$_POST['name'][$num],
										"caption"=>$_POST['caption'][$num],
										"sort"=>intval($_POST['sort'][$num]),
										"type"=>intval($_POST['type'][$num]),
										"multiple"=>intval($_POST['multiple'][$num]),
	//									"dgroup"=>intval($_POST['dgroup'][$num]),
										"required"=>intval($_POST['required'][$num]),
										"add_values"=>$_POST['add_values'][$num],
										"owner_type"=>intval($_POST['owner_type'][$num]),
										"not_element"=>intval($_POST['not_element'][$num]),
										"data_block"=>$_GET['dblock'],
										"not_list"=>$_POST['not_list'][$num],
										"admin_only"=>$_POST['admin_only'][$num],
										"meta"=>$_POST['meta'][$num],
									);
									if (isset($_POST['delete_id'][$num]))
										{
											$tp->Delete($_POST['delete_id'][$num]);
										}
										else
										{
									if ($id>0)
										$tp->Update($id,$values);
									else
										$tp->Create($values);
										};
								};
				};
		$table->Load();
		};
	$table->Draw();
?>