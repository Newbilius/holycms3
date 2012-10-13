<? if (!defined('HCMS')) die(); ?>
<BR>Страницы: 
<?
//PrePrint($params);
for ($i=1;$i<=$params['page_max'];$i++)
{
?>

<? if ($i==$params['page']) { ?> <b> <? };?>
<? if ($i!=$params['page']) { ?> <a href=<?=$params['base_link']?>&<?=$params['page_param']?>=<?=$i?>&page_count=<?=$params['count']?><? if (isset($_GET['parent'])){?>&parent=<?=$_GET['parent']?><?};?>> <? };?>

[&nbsp;<?=$i?>&nbsp;]

<? if ($i!=$params['page']) { ?> </a> <? };?>
<? if ($i==$params['page']) { ?> </b> <? };?>

<?
};
?>
<BR>
Всего найдено: <?=$params['max_count']?>