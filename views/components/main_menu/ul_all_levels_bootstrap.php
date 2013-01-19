<? if (!defined('HCMS')) die(); ?>

<?

function TestSelectedLevels(&$result, $root, $old_root = 0) {
    foreach ($result[$root] as $id => $item) {
        if ($item['SELECTED']) {
            foreach ($result[$old_root] as $id2 => &$item2) {
                $item2['SELECTED'] = false;
            }
        }
        if (isset($result[$item['id']]))
            if (count($result[$item['id']]) > 0)
                TestSelectedLevels($result, $item['id'], $root);
    };
}

function PrintTree(&$result, $root, $prew_href) {
    ?>
    <ul class="nav nav-list" style="padding-left:20px;margin-bottom:4px;">
        <?
        foreach ($result[$root] as $id => $item) {
            if ($item['redirect'])
                $url = $item['redirect'];
            else
                $url = $prew_href . "/" . $item['name'];
            ?>
            <li <? if ($item['SELECTED']) { ?>class="active"<? }; ?>>
                <a href=<?= $url ?>><?= $item['caption'] ?></a>
                <?
                if (isset($result[$item['id']]))
                    if (count($result[$item['id']]) > 0)
                        PrintTree($result, $item['id'], $url);
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

<?
if (isset($result)) {
    //убираем признак выбранности у всех уровней, кроме последнего
    TestSelectedLevels($result, 0)
    ?>
    <div class="well sidebar-nav" style="margin-right:20px;">
        <? PrintTree($result, 0, ""); ?>
    </div>

<? }; ?>