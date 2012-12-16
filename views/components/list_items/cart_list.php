<?
if (count($result) > 0) {
    $cost_full=0;
    ?>
<form method="post">
    <input type="hidden" name="recalc" value="1">
    <table border="1" width="100%">
        <tr align="center">
            <td>
                Фото
            </td>
            <td>
                Название
            </td>
            <td>
                Цена
            </td>
            <td>
                Число
            </td>
            <td>
                Сумма
            </td>
            <td>
                Удалить
            </td>
        </tr>
        <?
        foreach ($result as $item) {
            $cost_this=$params['items_count'][$item['id']]*$item['cost'];
            $cost_full+=$cost_this;
            ?>
        <tr>
            <td width="150">
              <?
              if ($item['foto']) {
              $img = new HolyImg($item['foto']);
              $img->Resize(Array("height" => 150, "width" => 150));
              ?>
               <a rel="gal1" class="picture" href="<? echo $item['foto']; ?>">
                  <? echo $img->DrawHref(); ?>
               </a>
                <?}else{?>
                нет
                <?};?>
            </td>
            <td>
                <a target="_blank" href="<? echo ReplaceURL($params['url'], $item); ?>">
                <? echo $item['caption'];?>
                </a>
            </td>
            <td>
                <? echo $item['cost'];?>
            </td>
            <td>
                <input name="item[<? echo $item['id'];?>]" value="<? echo $params['items_count'][$item['id']];?>" style="width:100px;">
            </td>
            <td>
                <? echo $cost_this?>
            </td>
            <td align="center">
                <a href="?delete=<?echo $item['id'];?>">
                    [X]
                </a>
            </td>
        </tr>
            <?
        };
        ?>
    </table>
    
    <br><input type="submit" value="Пересчитать">
</form>

<br>
Сумма: <b><? echo $cost_full;?></b>
    <?
} else {
    ?>
    Корзина пуста.
    <?
}
?>