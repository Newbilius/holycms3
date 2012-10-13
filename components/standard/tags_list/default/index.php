<?
if (!isset($_GET['tags'])) $_GET['tags']="";
//pre_print($tags);
//echo $tags_max_count;
	foreach ($tags as $tag=>$tag_count)
		{
		?>
		&nbsp;&nbsp;&nbsp;<span  <? if ($_GET['tags']==$tag) {?>style="font-weight:bold;"<?}?>><a class="menu_link" href="<?=$params['url']?><?=urlencode($tag)?>"><?=$tag?> (<?=$tag_count?>)</a></span>&nbsp;&nbsp;&nbsp;
		<?
		};
?>