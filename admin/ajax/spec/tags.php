<?
require_once(realpath(str_replace("\\","/",dirname(dirname(dirname(dirname(__FILE__))))."/engine.php")));
//вывести список иблоков
$src_block=new DBlock();
$src_f=new DBlockFields();
?>
<script>
function ReloadThis()
{
//alert('/engine/admin/ajax/spec/tags.php?spec_id=<?=$_GET['spec_id']?>&val[0]='+$("#global_ajax_1").val()+'&val[1]=&val[2]=');
$.get('/engine/admin/ajax/spec/tags.php?spec_id=<?=$_GET['spec_id']?>&val[0]='+$("#global_ajax_1").val()+'&val[1]=&val[2]=', function(data){
//alert(data);
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

Свойство, из которого брать тэги<BR>
<select style="width:100%;" name=global_ajax_2 id=global_ajax_2>
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
<input style="display:none;" name=global_ajax_3 id=global_ajax_3 value="<?=$_GET['val'][2]?>">

<center><a style="cursor:pointer;" onclick="SaveOn('<?=$_GET['spec_id']?>')">Сохранить</a></center>
</div>