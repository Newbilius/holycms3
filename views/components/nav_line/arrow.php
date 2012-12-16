<?
if (!defined('HCMS')) die();
foreach ($result as $i=>$gb)
	{
		if ($i!=0)
			{
			?>&nbsp;»&nbsp;<?
			};
		if (isset($gb[1])){?><a href="<?=$gb[1]?>"><?};?><?=strip_tags($gb[0])?><? if (isset($gb[1])){?></a><?};
	};
?>