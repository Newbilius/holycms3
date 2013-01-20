<?
if (isset($result))
    if (count($result) > 0) {
        ?>
        <?
        foreach ($result as $item) {
            ?>
            <div style="width:100;min-height:100px;float:left;padding-right: 5px;">
                <?
                if ($item['foto']) {
                    $img = new HolyImg($item['foto']);
                    $img->Resize(Array("height" => 200, "width" => 200));
					?>
                <a href="<? echo ReplaceURL($params['url'], $item); ?>"><? echo $img->DrawHref(); ?></a><br>
                <?};
                ?>
                <a href="<? echo ReplaceURL($params['url'], $item); ?>">
                    <? echo $item['caption'] ?></a><BR>
                    Цена:<? echo $item['cost'] ?><BR>
            </div>
            <?
        };
        ?>
    <? };
?>