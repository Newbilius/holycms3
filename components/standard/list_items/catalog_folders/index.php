<?
if (isset($result))
    if (count($result) > 0) {
        ?>
        <ul>
            <?
            foreach ($result as $item) {
                ?>
                <li>
                    <a rel="gal1" href="<? echo ReplaceURL($params['url'], $item); ?>"><? echo $item['caption']; ?></a>
                </li>
                <?
            };
            ?>
        </ul>
    <? };
?>