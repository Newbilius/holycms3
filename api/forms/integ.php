<?
if (!defined('HCMS')) die();
class CForm_integ extends CForm_Text{
	
	function EditView($name,$data,$add,$idnum)
	{
		if (!isset($data[$name]))
			$data[$name]="";
		?>
			<input name=<?=$name?>[] value="<?=$data[$name]?>" style="width:40px">
		<?
	}

}
?>