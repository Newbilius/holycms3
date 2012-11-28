<?
if (isset($result))
    if (count($result) > 0) {
        ?>
        <?
        foreach ($result as $item) {
            ?>
            <div style="width:200px;height:200px;float:left;padding-right:20px;padding-bottom: 10px;">
                <?
                if ($item['foto']) {
                    $img = new HolyImg($item['foto']);
                    $img->Resize(Array("height" => 200, "width" => 200));
                    ?>
                    <a rel="gal1" class="picture" href="<? echo $item['foto']; ?>"><? echo $img->DrawHref(); ?></a>
                <? };
                ?>
            </div>
            <?
        };
        ?>
    <? };
?>