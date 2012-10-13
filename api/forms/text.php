<?
class CForm_text{
	
	function NeedInsert($value)
	{
		return false;
	}
	
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
			<input name=<?=$name?><?if ($multiple){?>[]<?};?> value="<?=htmlspecialchars($data[$name])?>" style="width:100%">
		<?
		}
	
	function View($name,$data,$add,$multiple=false)
		{
		?>
			<? if (strpos($data[$name],"<script")===FALSE){?>
			<?=$data[$name]?>
			<?}else{?><?};?>
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