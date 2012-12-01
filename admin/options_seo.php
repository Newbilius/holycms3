<?
$_global_bread[]=Array("Настройки сайта","/engine/admin/options.php");
$types=new DBlockElement("options_class");
$values=new DBlockElement("options");

$types->GetList();
while ($tmp=$types->GetNext())
	$arTypes[$tmp['id']]=$tmp;
	
$values->GetList("parent!=0 AND folder=0");
while ($tmp=$values->GetNext())
	$arValues[$tmp['id']]=$tmp;
	
//подготовка данных
if (isset($_POST['form_go']))
	if ($_POST['form_go']==512)
	{
		//preprint($_POST);
		foreach ($arValues as $val)
			{
				$id=$val['id'];
				unset($val['id']);
				if (isset($_POST[$id]))
						$val['hvalue']=$_POST[$id];
					else
						$val['hvalue']="";
				//$values->sql->debug=true;
				//preprint($_POST[$id]);
				//preprint($val);
				$values->Update($id,$val);
				//$values->Update($id,$val);
				//echo "<HR>";
			};
if (!isset($_POST['robotstxt'])) $_POST['robotstxt']="";
$robots_path=FOLDER_ROOT."/robots.txt";
file_put_contents ($robots_path, $_POST['robotstxt']);
//preprint($_POST['favicon']);
if (isset($_POST['favicon']))
	if ($_POST['favicon']['tmp_name'])
		if ($_POST['favicon']['type']=="image/x-icon")
			move_uploaded_file($_FILES['favicon']['tmp_name'],FOLDER_ROOT."/favicon.ico");

unset($arValues);
$values->GetList("parent!=0 AND folder=0");
while ($tmp=$values->GetNext())
	$arValues[$tmp['id']]=$tmp;
	};
//вывод значений
?>
<BR>
<form method=post enctype="multipart/form-data">
<input type=submit value="Сохранить" style="width:100%;HEIGHT:35PX;" class="btn">
<BR>
<input type=hidden name="form_go" value="512">
<table width=100% border=0 cellpadding=0 cellspacing=0 id=tableform name=tableform class=tableform>
<tr>
	<td>
		Свойство
	</td>
	<td>
		Значение
	</td>
</tr>
<?
//вывод значений
$odd=3;
if (isset($arValues))
foreach ($arValues as $val)
	{
		$odd++;
		if ($val['options_class']=="") $val['options_class']=1;
		?>
			<tr <? if ($odd%2==0){?>class="odd"<?};?>>
				<td width=200 valign=top style="padding-top:5px;padding-left:5px;"><?=$val['caption']?> [<?= $val['name'] ?>]</td>
				<td>
					<?
						switch ($arTypes[$val['options_class']]['name'])
							{
								case "text2":?>
								<textarea name="<?=$val['id']?>" style="width:90%;height:190px;"><?=$val['hvalue']?></textarea>
								<?
								break;
								
								case "pic":
								case "wis":?>
								<textarea class="wisiwig" id="s<?=$val['id']?>" name="<?=$val['id']?>" style="width:90%;height:190px;"><?=$val['hvalue']?></textarea>
								<?
								break;
								
								
								case "check":?>
								
								<input type=checkbox name="<?=$val['id']?>" <? if ($val['hvalue']){?>checked<?};?>>
								
								<?break;
								
								default:?>
									<input style="width:90%;" type=text value="<?=$val['hvalue']?>" name="<?=$val['id']?>">
								<?
								break;
							};
					?>
				</td>
			</tr>
		<?
	};
	?>
	<tr <? if ($odd%2==0){?>class="odd"<?};?>>
	
	
	<td width=200 valign=top style="padding-top:5px;padding-left:5px;">Robots.txt</td>
	<td>
	<?
	$robots_path=FOLDER_ROOT."/robots.txt";
	$robots_content="";
	if (file_exists($robots_path))$robots_content=file_get_contents($robots_path);
	if ($robots_content=="")
		{
		$robots_content="#файл пуст! рекомендуемое минимальное содержимое - ниже
User-agent: *
Disallow: /engine/admin/
Disallow: /engine/
Disallow: /forum/engine/admin/
";
		};
	?>
	<textarea name="robotstxt" style="width:90%;height:190px;"><?=$robots_content?></textarea>
	</td>
	</tr>
	
	<tr <? if ($odd%2==0){?>class="odd"<?};?>>
	<?$odd++;?>
	<td width=200 valign=top style="padding-top:5px;padding-left:5px;">favicon.ico</td>
	
	</td>
	<td>
	<? if (file_exists(FOLDER_ROOT."/favicon.ico")){?>
	<img src="/favicon.ico?random=<?=time()?>">&nbsp;<?};?><input name=favicon type=file>
	</td>
	</tr>
	<?
?>
</table>
<br><input type=submit value="Сохранить" style="width:100%;HEIGHT:35PX;" class="btn">
</form>
<? include_once FOLDER_ENGINE."api/forms/wysiwyg_html.php";?>
<? CForm_wysiwyg_html::HTML("");?>