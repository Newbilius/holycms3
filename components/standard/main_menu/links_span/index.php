<? if (!defined('HCMS')) die(); ?>

<? if (isset($result)) { ?>
    <?
    foreach ($result[0] as $id => $item) {
        if ($item['redirect'])
            $url = $item['redirect'];
        else
            $url = "/" . $item['name'];
        ?>
        <a <? if ($item['SELECTED']) { ?>class="active"<? }; ?> href=/<?= $url ?>><span><?= $item['caption'] ?></span></a>
        <?
    };
    ?>	
<? }; ?>