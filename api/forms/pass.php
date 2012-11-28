<?
if (!defined('HCMS')) die();
class CForm_pass extends CForm_Text{

	function NeedInsert($value)
	{
		if ($value==="") return true;
		return false;
	}

	function View($name,$data,$add,$multiple=false)
		{
		}

	function Add($name,$data,$add,$multiple=false)
		{
		?>
			<input type=password name=<?=$name?><?if ($multiple){?>[]<?};?> value="" style="width:100%">
		<?
		}
		
	function Edit($name,$data,$add,$multiple=false)
		{
		?>
			<input type=password name=<?=$name?><?if ($multiple){?>[]<?};?> value="" style="width:100%">
		<?
		}
		
	function AfterEdit($name,$value,$add)
	{
		return MD5($value);
	}
	
	function BeforeAdd($name,$value,$add)
	{
		return MD5($value);
	}
}
?>