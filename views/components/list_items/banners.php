<?
if (isset($result))
    if (count($result) > 0) {
        ?>

        <?
        foreach ($result as $item) {
            ?>
            <a href=<?= $item['url'] ?>><img border=0 src="<?= $item['foto'] ?>" alt="<?= $item['caption'] ?>"></a>
            <?
        };
        ?>

    <? };
?>