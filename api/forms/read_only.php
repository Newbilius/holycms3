<?
class CForm_read_only extends CForm_Text{
	
	
	function Edit($name,$data,$add,$multiple=false)
		{
		?>
			<input readonly name=<?=$name?><?if ($multiple){?>[]<?};?> value="<?=htmlspecialchars($data[$name])?>" style="width:100%">
		<?
		}

}
?>