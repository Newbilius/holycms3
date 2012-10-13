<?
class CForm_add_values{
	
	function BeforeAdd($name,$value,$add)
	{
		return $value;
	}

	function AfterAdd($name,$value,$add)
	{
		return $value;
	}
	
	function BeforeEdit($name,$value,$add)
	{
		return $value;
	}

	function AfterEdit($name,$value,$add)
	{
		return $value;
	}
	
	function EditView($name,$data,$add,$idnum)
	{
		if (!isset($data[$name]))
			$data[$name]="";
		?>
			<input  id='<?=$name?>_<?=$idnum?>' name=<?=$name?>[<?=$idnum?>] value='<?=$data[$name]?>' style="width:80%;float:left;display:none;">
			<a style="cursor:pointer;" onclick="ShowSpecialBox('<?=$name?>_<?=$idnum?>')">Add</a>
		<?
	}
		
	function Add($name,$add,$multiple=false)
		{
		?>
			<input name=<?=$name?><?if ($multiple){?>[]<?};?> value='<? if (isset($_POST[$name])) echo $_POST[$name];?>' style="width:100%">
		<?		
		}
	
	function Edit($name,$data,$add,$multiple=false)
		{
		?>
			<input name=<?=$name?><?if ($multiple){?>[]<?};?> value='<?=$data[$name]?>' style="width:100%">
		<?
		}
	
	function View($name,$data,$add,$multiple=false)
		{
		?>
			<?=$data[$name]?>
		<?
		}
	function Filter($name)
		{
		if (!isset($_GET['filter'][$name])) $_GET['filter'][$name]="";
		?>
			<input name=filter[<?=$name?>] value='<?=$_GET['filter'][$name]?>' style="width:90%">
		<?
		}
}
?>