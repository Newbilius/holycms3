<?
class CForm_checkbox extends CForm_Text{

	function View($name,$data,$add,$multiple=false)
		{
		if (!isset($data[$name]))
			$data[$name]=0;
		?>
			<b><? if ($data[$name]>0){?>Да<?}else{?>Нет<?};?></b>
		<?
		}
		

	function Add($name,$add,$multiple=false)
	{
		if (!isset($data[$name]))
			$data[$name]=0;
		?>
			<input type=checkbox name=<?=$name?><?if ($multiple){?>[]<?};?> <? if (isset($_POST[$name])) if ($_POST[$name]) {?> checked <?};?> value="1">
		<?
	}

	function Edit($name,$data,$add,$multiple=false)
	{
		if (!isset($data[$name]))
			$data[$name]=0;
		?>
			<input type=checkbox name=<?=$name?><?if ($multiple){?>[]<?};?> <? if ($data[$name]) {?> checked <?};?> value="1">
		<?
	}
	
	function EditView($name,$data,$add,$idnum)
	{
		if (!isset($data[$name]))
			$data[$name]=0;
		?>
			<input type=checkbox name=<?=$name?>[<?=$idnum?>] <? if ($data[$name]) {?> checked <?};?> value="1">
		<?
	}
	
	function Filter($name)
		{
		if (!isset($_GET['filter'][$name])) $_GET['filter'][$name]="";
		?>	<select name=filter[<?=$name?>] style="width:95%">
			<option <? if ($_GET['filter'][$name]==""){?>selected<?};?> value=''>[любое]</option>
			<option <? if ($_GET['filter'][$name]=="1"){?>selected<?};?> value='1'>Да</option>
			<option <? if ($_GET['filter'][$name]=="0"){?>selected<?};?> value='0'>Нет</option>
			</select>
		<?
		}
}
?>