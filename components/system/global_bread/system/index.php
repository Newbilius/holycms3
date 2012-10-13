<ul class="breadcrumb">


<?
if (!defined('HCMS')) die();
foreach ($_global_bread as $i=>$gb)
	{?>
	<li><?
		if ($i!=0)
			{
			?>
			<span class="divider">/</span>
			<?
			};
		if (isset($gb[1])){?><a class=ajax href="<?=$gb[1]?>"><?};?>
		
		<?=$gb[0]?>
		
		<? if (isset($gb[1])){?></a><?};
	?></li><?
	};
?>
</ul>