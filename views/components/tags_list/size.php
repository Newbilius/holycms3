<?
if (!defined('HCMS'))
    die();

$cnt = 0;

foreach ($result['list'] as $tag) {
    $cnt++;
    $different_proc = ($tag['count'] / $result['count']) * $params['coefficient'];
    ?>
    <? if ($cnt != 1) { ?>,<? }; ?>
    <a style="font-size:<?= ceil($params['min_size'] + $result['count'] * $different_proc) ?>px;" href="<? echo $tag['url'] ?>"><? echo $tag['caption'] ?></a><?
    ?>
<? }; ?>