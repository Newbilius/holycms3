<?
if (isset($result))
    if (count($result) > 0) {
        ?>
        <?
        foreach ($result as $item) {
            ?>
            <div style="width:200px;height:200px;float:left;padding-right:20px;padding-bottom: 10px;text-align:center;">
                <?
                if ($item['foto']) {
                    $img = new HolyImg($item['foto']);
                    $img->Resize(Array("height" => 200, "width" => 200));
                    ?>
                    <a href="<? echo ReplaceURL($params['url'], $item); ?>"><? echo $img->DrawHref(); ?><br>
                    <? echo $item['caption'];?>
                    </a>
                <? };
                ?>
            </div>
            <?
        };
        ?>
    <? };
?>