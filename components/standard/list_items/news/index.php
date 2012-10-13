<?
if (isset($result))
    if (count($result) > 0) {
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