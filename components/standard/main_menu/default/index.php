<? if (!defined('HCMS')) die(); ?>

<? if (isset($result)) { ?>
    <ul>
        <?
        foreach ($result[0] as $item) {
            if ($item['redirect'])
            $url = $item['redirect'];
        else
            $url = "/" . $item['name'];
            ?>
            <li>
                <? if ($item['SELECTED']) { ?><b><? }; ?>
                    <a href='<? echo $url;?>'><?= $item['caption'] ?></a>
                    <? if ($item['SELECTED']) { ?></b><? }; ?>
            </li>
            <?
        };
        ?>
    </ul>
<? }; ?>