<?
if ($_count >0) {
    $cost_full=0;
    ?>
    ������� � �������: <? echo $all_count;?>
    <br>
    ����� � �������: <? echo $all_summ;?>
    <br>
    <a href="<? echo $params['cart_url']?>">������� � �������</a>
    <?
} else {
    ?>
    <a href="<? echo $params['cart_url']?>">������� �����.</a>
    <?
}
?>