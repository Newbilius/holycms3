<?
class CForm_user_list extends CForm_Text{
	
	function View($name,$data,$add,$multiple=false)
		{
		$add_datas[0]="users";
		$add_datas[1]="id";
		$add_datas[2]="caption";
		
		//$add_datas=explode(";",$add);
		$spec_sql=new HolySQL($add_datas[0]);
		$spec_sql->Select();		
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
		$add_datas[0]="users";
		$add_datas[1]="id";
		$add_datas[2]="caption";
		
		$add_datas=explode(";",$add);
		//PrePrint($add_datas);
		//0 таблица
		//1 значащее поле
		//2 поле отображаемое
		$spec_sql=new HolySQL($add_datas[0]);
		$spec_sql->Select();		
		if (!isset($data[$name])) $data[$name]="";
		?>
			<select name=<?=$name?>[<?=$counter?>] style="width:100%">
			<? while($ndata=$spec_sql->GetNext()){?>
			<option <?if ($data[$name]==$ndata[$add_datas[1]]) {?>selected<?};?> value="<?=$ndata[$add_datas[1]]?>"><?=$ndata[$add_datas[2]]?></option>
			<?};?>
			</select>
			
		<?		
		}
		
	function Edit($name,$data,$add,$multiple=false)
		{
		$add_datas[0]="users";
		$add_datas[1]="id";
		$add_datas[2]="caption";
		
		//$add_datas=explode(";",$add);
		//PrePrint($add_datas);
		//0 таблица
		//1 значащее поле
		//2 поле отображаемое
		$spec_sql=new HolySQL($add_datas[0]);
		$spec_sql->Select();		
		?>
			<select name=<?=$name?><?if ($multiple){?>[]<?};?> style="width:100%">
			<? while($ndata=$spec_sql->GetNext()){?>
			<option <?if ($data[$name]==$ndata[$add_datas[1]]) {?>selected<?};?> value="<?=$ndata[$add_datas[1]]?>"><?=$ndata[$add_datas[2]]?></option>
			<?};?>
			</select>
			
		<?		
		}
		
	function Add($name,$add,$multiple=false)
		{
		$add_datas[0]="users";
		$add_datas[1]="id";
		$add_datas[2]="caption";
		
		//$add_datas=explode(";",$add);
		//PrePrint($add_datas);
		//0 таблица
		//1 значащее поле
		//2 поле отображаемое
		$spec_sql=new HolySQL($add_datas[0]);
		$spec_sql->Select();		
		
		$H_USER=new DUser(1);
		//global $H_USER;
		$user_info=$H_USER->GetInfo();
		//PrePrint($user_info);
		if (!isset($_POST[$name])) $_POST[$name]=$user_info['id'];
		?>
			<select name=<?=$name?><?if ($multiple){?>[]<?};?> style="width:100%">
			<? while($ndata=$spec_sql->GetNext()){?>
			<option <?if (isset($_POST[$name])) if ($_POST[$name]==$ndata[$add_datas[1]]) {?>selected<?};?> value="<?=$ndata[$add_datas[1]]?>"><?=$ndata[$add_datas[2]]?></option>
			<?};?>
			</select>
			
		<?		
		}
		
		
	function Filter($name,$add="")
		{
		
		$add_datas[0]="users";
		$add_datas[1]="id";
		$add_datas[2]="caption";
		
		if (!isset($_GET['filter'][$name])) $_GET['filter'][$name]="";
		//$add_datas=explode(";",$add);
		//PrePrint($add_datas);
		//0 таблица
		//1 значащее поле
		//2 поле отображаемое
		$spec_sql=new HolySQL($add_datas[0]);
		$spec_sql->Select();		
		?>
			<select  name=filter[<?=$name?>] style="width:95%">
			<option <? if ($_GET['filter'][$name]==""){?>selected<?};?> value=''>[любое]</option>
			<? while($ndata=$spec_sql->GetNext()){?>
			<option <? if ($_GET['filter'][$name]==$ndata[$add_datas[1]]) {?>selected<?};?> value="<?=$ndata[$add_datas[1]]?>"><?=$ndata[$add_datas[2]]?></option>
			<?};?>
			</select>
			
		<?		
		}
}
?>