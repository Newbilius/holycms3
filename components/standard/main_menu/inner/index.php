<? if (!defined('HCMS')) die(); ?>

<? if (isset($result)) { ?>
    <?
    foreach ($result[0] as $id => $item) {
        $class = "";
        if ($id == count($result) - 1)
            $class = "last";
        if ($item['redirect'])
            $url = $item['redirect'];
        else
            $url = "/" . $item['name'];
        ?>
        <li class="<?= $class ?>">
            <? if ($item['SELECTED']) { ?><b><? }; ?>
                <a href=<?= $url ?>><?= $item['caption'] ?></a>
                <? if ($item['SELECTED']) { ?></b><? }; ?>
        </li>
        <?
    };
    ?>
<? }; ?>