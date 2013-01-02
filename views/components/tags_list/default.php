<?
if (!defined('HCMS'))
    die();

$cnt = 0;

foreach ($result['list'] as $tag) {
    $cnt++;
    ?>
    <? if ($cnt != 1) { ?>,<? }; ?>
    <a href="<? echo $tag['url'] ?>"><? echo $tag['caption'] ?> (<? echo $tag['count'] ?>)</a><?
    ?>
<? }; ?>