<?
$_global_bread[]=Array("������� ����","");
//preprint($_POST);
if (isset($_POST['form_go']))
		{
		$SQL=new HolySQL('system_cache');
		
			//preprint($_POST);
			if (isset($_POST['clear_base']))
				{
					$ok[]="��� ���� ������";
					$SQL->Query('TRUNCATE TABLE `system_cache`');
				};
			if (isset($_POST['clear_resize']))
				{
					$ok[]="��� �������� ������";
					$cache_img_folder=$_SERVER['DOCUMENT_ROOT']."/upload/resize_cache/";
					if (file_exists($cache_img_folder))
						{
							DeleteDir($cache_img_folder);
							mkdir($cache_img_folder);
						};
				};
			if (isset($_POST['clear_tmp']))
				{
					$ok[]="��������� ����� �������";
					$tmp_folder=$_SERVER['DOCUMENT_ROOT']."/upload/tmp/";
					if (file_exists($cache_img_folder))
						{
							DeleteDir($tmp_folder);
							mkdir($tmp_folder);
						};
				};
			//�������� � ������� �������� ������-�������
			if (isset($_POST['clear_orphan']))
				{/*
					$arBlock=new DBlock();
					
					//�������� �������� ID
						$types=new DBlockTypes();
						$type_id=$types->GetIDByName("image");
					//�������� ��������-��������
						$fields=new DBlockFields();
						$fields->GetList("type=".$type_id);
					//���� �� ������ �������-��������
						while ($field=$fields->GetNext())
							{
								$block=$arBlock->GetByID($field['data_block']);
								if (isset($block['name']))
									if ($block['name'])
										{
											preprint($field);
											preprint($block);
											$elements=new DBlockElement($block);
											$elements->GetList();
											//�������� ��������������� ��������
											while ($pic[]=
										};
								
								//$not_exists[]=$type_id;
							};
					//��������� �� �������������
					$ok[]="�������������� �������� �������, ������";
					echo "��������������:";
					*/
				};
		};
?>
<BR>

<?
if (isset($ok))
	{
		foreach ($ok as $ok_text)
			{
				?>
				<div class="alert alert-success"><?=$ok_text?></div>
				<?
			};
		?>
		<?
	};
?>
<form method=post>
<input type=hidden name="form_go" value="512">
��������, ��� �������:<BR><BR>
<?
global $_CONFIG;
if ($_CONFIG['CACHE_SYSTEM']){
?>
<input checked type=checkbox name="clear_base" value="1"> &nbsp; �������� ��� ����
<BR>
<?};?>
<input checked type=checkbox name="clear_resize" value="1"> &nbsp; ������� ��� ��������
<BR>
<input checked type=checkbox name="clear_tmp" value="1"> &nbsp; ������� ��������� �����
<?/*<BR>
<input type=checkbox name="clear_orphan" value="1"> &nbsp; ������� �������������� �������� (������ �������, �� ��������� ���������� �����)
*/?>

</br><br><input name=submit type=submit value="�������� ���" style="width:40%;HEIGHT:28px;" class="btn btn-success">
</form>