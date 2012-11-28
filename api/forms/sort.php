<?
class CForm_sort extends CForm_Text{
	
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
			<input name=<?=$name?>[<?=$idnum?>] value='<?=$data[$name]?>' style="width:90%;">
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
			<span id=value1 name=value1><?=$data[$name]?></span> <div style="display:none;">
			<input name=vid value=<?=$data['id']?>>
			<input name=value0 value=<?=$data[$name]?>>
			</div>
		<?
		}
}
?>