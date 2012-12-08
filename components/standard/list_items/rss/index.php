<?
$rss = new SmallRSS($params['title'], $params['link'], $params['description']);

if (isset($result))
    if (count($result) > 0) {
        foreach ($result as $item) {
            $rss->Add($item['caption'], ReplaceURL($params['url'], $item), $item['preview_text'], $item['sdate']);
            //echo ReplaceURL($params['url'], $item);
        }
    }
    
echo $rss->Complete();
/*
?>
<table width="100%" border="0">
    <?
    foreach ($result as $item) {
        ?>
        <tr>
            <td valign="top">
                <?
                if ($item['foto']) {
                    $img = new HolyImg($item['foto']);
                    $img->Resize(Array("height" => 80, "width" => 200));
                    ?>
                    <a href="<? echo ReplaceURL($params['url'], $item); ?>"><? echo $img->DrawHref(); ?></a>
                <? };
                ?>
            </td>
            <td valign="top">
                    <? echo PrintDate("%d %b %Y", $item['sdate']) ?>
                <a href="<? echo ReplaceURL($params['url'], $item); ?>">
                <? echo $item['caption'] ?></a><BR>
    <? echo $item['preview_text'] ?>
                <BR><BR>
            </td>
        </tr>
        <?
    };
    ?>
</table>
<? };
?>
 * 
 */?>