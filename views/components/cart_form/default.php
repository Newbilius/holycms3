<?
global $_selected_page;
$_selected_page['detail_text'];
?>
<br>
<?
if (is_array($errors)) {
    foreach ($errors as $_error) {
        ?>
        <br><span style="color:red;"><? echo $_error; ?></span>
        <?
    }
};
?>
<br><br>
<form method="post">
    Ваше имя:<br>
    <input name="fio" value="<? echo $form_values['fio'] ?>" style="width: 300px;"><br>

    E-mail:<br>
    <input name="email" value="<? echo $form_values['email'] ?>" style="width: 300px;"><br>

    Адрес и дополнительные данные:<br>
    <textarea name="text" style="width: 300px;"><? echo $form_values['text'] ?></textarea><br> 

    <input type="submit" value="Заказать">
    <input type="hidden" name="go" value="1">
</form>