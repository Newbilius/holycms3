<?
class CForm_tags extends CForm_text{
	
	function Edit($name,$data,$add,$multiple=false)
	{
		$add=explode(";",$add);
		?>
		<script>
		function AddTages<?=$name?>(new_text)
			{
				if ($("#<?=$name?>").val()=="")
				$("#<?=$name?>").val(new_text);
				else
				$("#<?=$name?>").val($("#<?=$name?>").val()+","+new_text);
				//html
			};
		</script>
			<textarea id="<?=$name?>" name="<?=$name?><?if ($multiple){?>[]<?};?>" style="width:100%;height:90px;"><?=$data[$name]?></textarea>
			<div style="padding-top:5px;padding-bottom:5px;">
			<?
				$sql=new HolySQL($add[0]);
				$sql->Select();
				while ($add_data=$sql->GetNext())
					{
					$add_tmp_massiv=explode(",",$add_data[$add[1]]);
					foreach ($add_tmp_massiv as $atm)
						if ($atm!="")
						$add_massiv[$atm]=$atm;
					};
			if (isset($add_massiv))
			foreach ($add_massiv as $am)
				{
			?>
				<a onclick="AddTages<?=$name?>('<?=$am?>')"><span style="padding:5px;cursor:pointer"><?=$am?></span></a>
			<?	};?>
			</div>
		<?
	}

	function Add($name,$add,$multiple=false)
	{
		$add=explode(";",$add);
		?>
		<script>
		function AddTages<?=$name?>(new_text)
			{
				if ($("#<?=$name?>").val()=="")
				$("#<?=$name?>").val(new_text);
				else
				$("#<?=$name?>").val($("#<?=$name?>").val()+","+new_text);
				//html
			};
		</script>
			<textarea id=<?=$name?> name=<?=$name?><?if ($multiple){?>[]<?};?> style="width:100%;height:90px;"><? if (isset($_POST[$name])){?><?=$_POST[$name]?><?};?></textarea>
			<div style="padding-top:5px;padding-bottom:5px;">
			<?
				$sql=new HolySQL($add[0]);
				$sql->Select();
				while ($add_data=$sql->GetNext())
					{
					$add_tmp_massiv=explode(",",$add_data[$add[1]]);
					foreach ($add_tmp_massiv as $atm)
						if ($atm!="")
						$add_massiv[$atm]=$atm;
					};
			if (isset($add_massiv))
			foreach ($add_massiv as $am)
				{
			?>
				<a onclick="AddTages<?=$name?>('<?=$am?>')"><span style="padding:5px;cursor:pointer"><?=$am?></span></a>
			<?	};?>
			</div>
		<?
	}
}
?>