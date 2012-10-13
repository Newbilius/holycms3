<?
if ($_count >0) {
    $cost_full=0;
    ?>
    Товаров в корзине: <? echo $all_count;?>
    <br>
    Сумма в корзине: <? echo $all_summ;?>
    <br>
    <a href="<? echo $params['cart_url']?>">Перейти в корзину</a>
    <?
} else {
    ?>
    <a href="<? echo $params['cart_url']?>">Корзина пуста.</a>
    <?
}
?>