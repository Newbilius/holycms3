<?
require_once($_SERVER['DOCUMENT_ROOT']."/engine/engine.php");
//вывести список иблоков
$src_block=new DBlock();
$src_f=new DBlockFields();
$src_f2=new DBlockFields();
?>
<script>
function ReloadThis()
{
$.get('/engine/admin/ajax/spec/list.php?spec_id=<?=$_GET['spec_id']?>&val[0]='+$("#global_ajax_1").val()+'&val[1]=&val[2]=', function(data){
$("#global_name").html(data);
});
}
</script>
<div id=global_name name=global_name>
Блок<BR>
<select style="width:100%;" name=global_ajax_1 id=global_ajax_1 onchange="ReloadThis()">
<?
$src_block->GetList();
while ($data=$src_block->GetNext())
	{
	?>
	<option <? if ($_GET['val'][0]==$data['name']){?>selected<?};?> value="<?=$data['name']?>"><?=$data['caption']?></option>
	<?
	};
?>
</select>

<BR>
Свойство, из которого брать значение<BR>
<select style="width:100%;" name=global_ajax_2 id=global_ajax_2>
<option <? if ($_GET['val'][1]=="id"){?>selected<?};?> value="id">id</option>
<option <? if ($_GET['val'][1]=="name"){?>selected<?};?> value="name">name</option>
<option <? if ($_GET['val'][1]=="caption"){?>selected<?};?> value="caption">caption</option>
<?
$src_f->GetListByBlock($_GET['val'][0]);
while ($data=$src_f->GetNext())
	{
	?>
	<option <? if ($_GET['val'][1]==$data['name']){?>selected<?};?> value="<?=$data['name']?>"><?=$data['caption']?></option>
	<?
	};
?>
</select>

<BR>

Свойство, из которого брать название<BR>
<select style="width:100%;" name=global_ajax_3 id=global_ajax_3>
<option <? if ($_GET['val'][2]=="id"){?>selected<?};?> value="id">id</option>
<option <? if ($_GET['val'][2]=="name"){?>selected<?};?> value="name">name</option>
<option <? if ($_GET['val'][2]=="caption"){?>selected<?};?> value="caption">caption</option>
<?
$src_f2->GetListByBlock($_GET['val'][0]);
while ($data=$src_f2->GetNext())
	{
	?>
	<option <? if ($_GET['val'][2]==$data['name']){?>selected<?};?> value="<?=$data['name']?>"><?=$data['caption']?></option>
	<?
	
	};
?>
</select>

<center><a style="cursor:pointer;" onclick="SaveOn('<?=$_GET['spec_id']?>')">Сохранить</a></center>
</div>