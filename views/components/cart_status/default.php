<?
if ($result['count'] >0) {
    $cost_full=0;
    ?>
    Товаров в корзине: <? echo $result['count'];?>
    <br>
    Сумма в корзине: <? echo $result['summ'];?>
    <br>
    <a href="<? echo $params['cart_url']?>">Перейти в корзину</a>
    <?
} else {
    ?>
    <a href="<? echo $params['cart_url']?>">Корзина пуста.</a>
    <?
}
?>