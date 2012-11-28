<?
if (!defined('HCMS')) die();
foreach ($_global_bread as $i=>$gb)
	{
		if ($i!=0)
			{
			?>&nbsp;»&nbsp;<?
			};
		if (isset($gb[1])){?><a href="<?=$gb[1]?>"><?};?><?=$gb[0]?><? if (isset($gb[1])){?></a><?};
	};
?>