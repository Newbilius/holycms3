<? if (!defined('HCMS')) die(); ?>

<?

function PrintCatalogItemsFolderTree(&$result, $root, $params) {
    ?>
    <ul>
        <?
        foreach ($result as $id => $item)
            if ($item['parent'] == $root) {
                ?>
                <li>
                    <a href=<? echo ReplaceURL($params['url'], $item); ?>><?= $item['caption'] ?></a>
                    <?
                    PrintCatalogItemsFolderTree($result, $item['id'], $params);
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
    <? PrintCatalogItemsFolderTree($result, 0, $params); ?>


<? }; ?>