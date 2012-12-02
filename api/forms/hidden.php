<?
class CForm_hidden extends CForm_Text{
	
    protected $block;
	function EditView($name,$data,$add,$idnum)
	{
		if (!isset($data[$name]))
			$data[$name]="";
		?>
			<input type="hidden" name=<?=$name?>[<?=$idnum?>] value='<?=$data[$name]?>' style="width:90%;">
		<?
	}
		
	function Add($name,$add,$multiple=false)
		{
		?>
			<input type="hidden" name=<?=$name?><?if ($multiple){?>[]<?};?> value='<? if (isset($_POST[$name])) echo $_POST[$name];?>' style="width:100%">
		<?		
		}
	
	function Edit($name,$data,$add,$multiple=false)
		{
		?>
			<input type="hidden" name=<?=$name?><?if ($multiple){?>[]<?};?> value="<?=htmlspecialchars($data[$name])?>" style="width:100%">
		<?
		}
	
	function View($name,$data,$add,$multiple=false)
		{
		?>
		<?
		}
}
?>