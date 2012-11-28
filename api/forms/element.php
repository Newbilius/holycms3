<?
class CForm_element extends CForm_Text{
	
	function View($name,$data,$add,$multiple=false)
		{
		$add_datas=explode(";",$add);
		$spec_sql=new HolySQL($add_datas[0]);
		$spec_sql->Select("folder=0");		
		if (!isset($data[$name])) $data[$name]="";
		?>
			<? while($ndata=$spec_sql->GetNext()){?>
			<?if ($data[$name]==$ndata[$add_datas[1]]) {echo $ndata[$add_datas[2]];?>
		 [<?=$data[$name]?>]
		<?
		}
		}
		}

	function EditView($name,$data,$add,$counter)
		{
		$add_datas=explode(";",$add);
		//PrePrint($add_datas);
		//0 таблица
		//1 значащее поле
		//2 поле отображаемое
		$spec_sql=new HolySQL($add_datas[0]);
		$spec_sql->Select();		
		if (!isset($data[$name])) $data[$name]="";
		?>
			<select class=type_select name=<?=$name?>[<?=$counter?>] style="width:100%">
			<? while($ndata=$spec_sql->GetNext()){
			?>
			<option <? if (isset($add_datas[3])) {?>name="<?=$ndata[$add_datas[3]]?>"<?};?> <?if ($data[$name]==$ndata[$add_datas[1]]) {?>selected<?};?> value="<?=$ndata[$add_datas[1]]?>"><?=$ndata[$add_datas[2]]?></option>
			<?};?>
			</select>
			
		<?		
		}
		
	function Edit($name,$data,$add,$multiple=false)
		{
		$add_datas=explode(";",$add);
		//PrePrint($add_datas);
		//0 таблица
		//1 значащее поле
		//2 поле отображаемое
		$spec_sql=new HolySQL($add_datas[0]);
		$spec_sql->Select("folder=0");		
		$uid=uniqid();
		?>
			<select id=<?=$uid?> name=<?=$name?><?if ($multiple){?>[]<?};?> style="width:100%">
			<option value="">[нет значения]</option>
			<? while($ndata=$spec_sql->GetNext()){?>
			<option <?if ($data[$name]==$ndata[$add_datas[1]]) {?>selected<?};?> value="<?=$ndata[$add_datas[1]]?>"><?=$ndata[$add_datas[2]]?></option>
			<?};?>
			</select>
			
		<?		
		}
		
	function Add($name,$add,$multiple=false)
		{
		$add_datas=explode(";",$add);
		//PrePrint($add_datas);
		//0 таблица
		//1 значащее поле
		//2 поле отображаемое
		$spec_sql=new HolySQL($add_datas[0]);
		$spec_sql->Select("folder=0","caption ASC");		
		
		?>
			<select name=<?=$name?><?if ($multiple){?>[]<?};?> style="width:100%">
			<option value="">[нет значения]</option>
			<? while($ndata=$spec_sql->GetNext()){?>
			<option <?if (isset($_POST[$name])) if ($_POST[$name]==$ndata[$add_datas[1]]) {?>selected<?};?> value="<?=$ndata[$add_datas[1]]?>"><?=$ndata[$add_datas[2]]?></option>
			<?};?>
			</select>
			
		<?		
		}
		
	function Filter($name,$add="")
		{
		if (!isset($_GET['filter'][$name])) $_GET['filter'][$name]="";
		$add_datas=explode(";",$add);
		//PrePrint($add_datas);
		//0 таблица
		//1 значащее поле
		//2 поле отображаемое
		$spec_sql=new HolySQL($add_datas[0]);
		$spec_sql->Select("folder=0");
		?>
			<select  name=filter[<?=$name?>]<?if ($multiple){?>[]<?};?> style="width:95%">
			<option <? if ($_GET['filter'][$name]==""){?>selected<?};?> value=''>[любое]</option>
			<? while($ndata=$spec_sql->GetNext()){?>
			<option <? if ($_GET['filter'][$name]==$ndata[$add_datas[1]]) {?>selected<?};?> value="<?=$ndata[$add_datas[1]]?>"><?=$ndata[$add_datas[2]]?></option>
			<?};?>
			</select>
			
		<?		
		}
}
?>