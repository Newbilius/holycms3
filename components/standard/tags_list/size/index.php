<?
if (!defined('HCMS')) die();
if (!isset($_GET['tags'])) $_GET['tags']="";
//echo $tags_max_count;
//echo $different."<HR>";
$different_proc=$different/$tags_max_count;
//echo $different_proc;

	foreach ($tags as $tag=>$tag_count)
		{
		?>
		&nbsp;&nbsp;&nbsp;<span  style="font-size:<?=ceil($params['min_size']+$tag_count*$different_proc)?>px;<? if ($_GET['tags']==$tag) {?>font-weight:bold;<?}?>"><a class="menu_link" href="<?=$params['url']?><?=urlencode($tag)?>"><?=$tag?></a></span>&nbsp;&nbsp;&nbsp;
		<?
		};
?>