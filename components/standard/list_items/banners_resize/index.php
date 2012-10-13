<?
if (isset($result))
    if (count($result) > 0) {
        ?>

        <?
        foreach ($result as $item)
            if ($item['foto']) {
                $img = new HolyImg($item['foto']);
                $img->Resize(Array("height" => 400, "width" => 350));
                ?>
                <a href=<?= $item['url'] ?>><? echo $img->DrawHref(Array(
                    'draw_inner'=>"title='".$item['caption']."'",
                )); ?></a>
                <?
            };
        ?>

    <? };
?>