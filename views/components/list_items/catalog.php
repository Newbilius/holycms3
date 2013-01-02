<?
if (isset($result))
    if (count($result) > 0) {
        //тэги
        $tags = new HolyTags($params['table'], "tags", "/tags_filter/#tag#");
        ?>
        <?
        foreach ($result as $item) {
            ?>
            <div style="width:200px;height:250px;float:left;padding-right:20px;padding-bottom: 10px;text-align:center">
                <?
                if ($item['foto']) {
                    $img = new HolyImg($item['foto']);
                    $img->Resize(Array("height" => 150, "width" => 200));
                    ?>
                    <a rel="gal1" href="<? echo ReplaceURL($params['url'], $item); ?>"><? echo $img->DrawHref(); ?></a>
                    <br>
                    <? echo $item['caption'] ?>
                    <br>
                    Цена: <? echo $item['cost'] ?> руб.<br>
                    <?
                    //тэги
                    $cnt = 0;
                    $tags_list = $tags->GetList($item);
                    foreach ($tags_list as $tag) {
                        $cnt++;
                        ?>
                        <? if ($cnt != 1) { ?>,<? }; ?>
                        <a href="<? echo $tag['url'] ?>"><? echo $tag['caption'] ?>(<? echo $tag['count'] ?>)</a><?
                        ?>
                    <? }; ?>
                    <? if ($item['spec1']) { ?><BR><b>спец-предложение!</b><? }; ?>

                <? };
                ?>
            </div>
            <?
        };
        ?>
    <? };
?>

<?
if (isset($paginator)){
    $paginator->Draw();
}
?>