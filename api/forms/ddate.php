<?
class CForm_ddate extends CForm_Text{
	
	function HTML($name)
		{
		?>
	<script type="text/javascript" charset="utf-8">
		$().ready(function() {
		//$('#<?=$name?>').datepicker({ dayNamesMin: ["Пн", "Вт", "Ср", "Чт", "Пт", "Сб", "Вск"],dateFormat:'yy-mm-dd',monthNames: ["Январь","Февраль","Март","Апрель","Май","Июнь","Июль","Август","Сентябрь","Октябрь","Ноябрь","Декабрь"] });
                $('#<?=$name?>').attachDatepicker({ dayNamesMin: ["Пн", "Вт", "Ср", "Чт", "Пт", "Сб", "Вск"],dateFormat:'yy-mm-dd',monthNames: ["Январь","Февраль","Март","Апрель","Май","Июнь","Июль","Август","Сентябрь","Октябрь","Ноябрь","Декабрь"] });
		})
	</script>
	<?
		}
		
	function Add($name,$add,$multiple=false)
		{
		if (!isset($_POST[$name]))
			$_POST[$name]=date("Y-m-d");
		if (isset($_POST[$name]))
			if ($_POST[$name]=="")
				$_POST[$name]=date("Y-m-d");
		$this->HTML($name);
		?>
			<input id=<?=$name?><?if ($multiple){?>[]<?};?> name=<?=$name?> value="<?=$_POST[$name];?>" style="width:200px">
		<?		
		}
	
	function Edit($name,$data,$add,$multiple=false)
		{
			$this->HTML($name);
			if ($data[$name]=="0000-00-00")
				$data[$name]=date("Y-m-d");
		?>
			<input id=<?=$name?><?if ($multiple){?>[]<?};?> name=<?=$name?> value="<?=$data[$name]?>" style="width:200px">
		<?
		}

	function Filter($name)
		{
		if (!isset($_GET['filter'][$name]))
			$_GET['filter'][$name]="";
		$this->HTML($name);
		?>
			<input id=<?=$name?> name=filter[<?=$name?>] value="<?=$_GET['filter'][$name];?>" style="width:90%">
		<?	
		}
}
?>