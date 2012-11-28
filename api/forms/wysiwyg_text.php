<?
if (!defined('HCMS')) die();
class CForm_wysiwyg_text extends CForm_text{
	
	function Edit($name,$data,$add,$multiple=false)
	{
		?>
			<textarea name="<?=$name?><?if ($multiple){?>[]<?};?>" style="width:100%;height:90px;"><?=$data[$name]?></textarea>
		<?
	}

	function Add($name,$add,$multiple=false)
	{
		?>
			<textarea name=<?=$name?><?if ($multiple){?>[]<?};?> style="width:100%;height:90px;"><? if (isset($_POST[$name])){?><?=$_POST[$name]?><?};?></textarea>
		<?
	}
}
?>