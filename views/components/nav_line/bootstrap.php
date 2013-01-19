<?
if (!defined('HCMS'))
    die();
?>
<ul class="breadcrumb">
    <?
    foreach ($result as $i => $gb) {
        if ($i != 0) {
            ?><span class="divider">/</span><?
    };
        ?>
    <li><? if (isset($gb[1])) { ?><a href="<?= $gb[1] ?>"><? }; ?>
            <?= strip_tags($gb[0]) ?>
            <? if (isset($gb[1])) { ?></a><? }; ?>
    </li>
    <?
};
?>
</ul>