<? if (!defined('HCMS')) die(); ?>
<form method=post>
<table align=center valign=center halign=center>
	<tr>
		<td>�����</td>
		<td><input type=text value="<?=$_POST['login']?>" name=login></td>
	</tr>
	<tr>
		<td>������</td>
		<td><input type=password value="" name=pass></td>
	</tr>
	<tr>
		<td colspan=2 align=center>
			<input name=submit type=submit value=�����>
		</td>
	</tr>
	<tr>
		<td colspan=2>
			<?
				if (isset($error))
				foreach ($error as $e)
					{
			?>
				<span style="color:red;">
					<?=$e?>
				</span><BR>
			<?
					};
			?>
		</td>
	</tr>
</table>
</form>