<? if (!defined('HCMS')) die(); ?>

<?

function PrintTree(&$result, $root, $prew_href) {
    ?>
    <ul>
        <?
        foreach ($result[$root] as $id => $item) {
            if ($item['redirect'])
                $url=$item['redirect'];
            else
                $url=$prew_href;
            ?>
            <li>
                <a <? if ($item['SELECTED']) { ?>class="active"<? }; ?> href=<?= $url ?>><?= $item['caption'] ?></a>
                <?
                if (isset($result[$item['id']]))
                    if (count($result[$item['id']]) > 0)
                        PrintTree($result, $item['id'], $prew_href . $item['name']);
                ?>
            </li>
            <?
        };
        ?>
    </ul>
    <?
}

;
?>

<? if (isset($result)) { ?>

    <? PrintTree($result, 0, "/"); ?>


<? }; ?>