<? if (isset($result)) { ?>

    <? //preprint($result); ?>

    <?
    if ($result['foto']) {
        $img = new HolyImg($result['foto']);
        $img->Resize(Array("height" => 80, "width" => 200));
        ?>
        <a class='picture' href="<? echo $result['foto']; ?>"><?
        echo $img->DrawHref(Array(
            "draw_inner" => "style='float:left;padding:4px;'"
        ));
        ?></a>
    <? }; ?>

    <? echo PrintDate("%d %b %Y", $result['sdate']) ?><br>
    <? echo $result['detail_text']; ?>

<? }; ?>