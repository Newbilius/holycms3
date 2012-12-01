<?
$_global_bread[]=Array("Управление резервными копиями базы данных","/engine/admin/sql_backup.php");
//preprint($_POST);
if (isset($_POST['form_go']))
	if ($_POST['submit']=="Сделать новый бэкап")
		{
			backup_database_tables('*');
		}
	elseif ($_POST['submit']=="Удалить выделенные")
		if (isset($_POST['file_list']))
		if (count($_POST['file_list'])>0)
		foreach ($_POST['file_list'] as $file)
		{
			//echo $file."<HR>";
			$f_name=FOLDER_UPLOAD."backup/".$file;
			if (file_exists($f_name))
			unlink($f_name);
		};
?>
<BR>
<form method=post>
<BR>
<input type=hidden name="form_go" value="512">
<table width=100% border=0 cellpadding=1px cellspacing=0 id=tableform name=tableform class=tableform>
<tr>
	<td>
		Файл
	</td>
	<td>
		Значение
	</td>
</tr>
<?
if (file_exists(FOLDER_UPLOAD."backup/"))
$file_list = scandir(FOLDER_UPLOAD."backup/");
$i=0;
if (isset($file_list))
if (is_array($file_list))
foreach ($file_list as $file)
if (($file!='..') && ($file!='.'))
if (strpos($file,"sql")!==FALSE){
{
$i++;
$good=false;
?>
	<tr <? if ($i%2==0){?>class="odd"<?};?>>
		<td>
			<input type='checkbox' name='file_list[]' value='<?=$file?>' checked>
		</td>
		<td>
			<a taget=_new href="/upload/backup/<?=$file?>"><?=$file?></a>
		</td>
	</tr>
<?};};
?>
</table>
<BR><br><input name=submit type=submit value="Удалить выделенные" style="width:40%;HEIGHT:28px;" class="btn btn-success">
<input name=submit type=submit value="Сделать новый бэкап" style="width:40%;HEIGHT:28px;" class="btn btn-warning">
</form>