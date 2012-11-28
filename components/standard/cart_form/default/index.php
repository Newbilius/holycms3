<?
echo $_selected_page['detail_text'];

$_POST = clear_array($_POST);

$user = HolyUser::getInstance();
if ($user->IsAuth()) {
    $info=$user->GetInfo();
    $_POST['fio']=$info['fio'];
    $_POST['email']=$info['email'];
    $_POST['text']=$info['address'];
};

if (isset($_POST['go'])) {
    $valid = new HolyValidator(Array(
                "fio" => "Имя",
                "email" => "e-mail",
                "text" => "текст",
            ));

    $valid->AddRule("not_empty", 'fio')
            ->AddRule("not_empty", 'email')
            ->AddRule("email", 'email')
            ->AddRule("not_empty", 'text');

    $valid->Check($_POST);

    $errors = $valid->Complete();

    if ($errors === true) {

        $back_email = "info@" . $_SERVER['HTTP_HOST'];
        $mail_text = "ФИО:" . $_POST["fio"] . "<BR>";
        $mail_text.="E-mail:" . $_POST["email"] . "<BR>";
        $mail_text.="Данные для доставки:<BR>" . $_POST["text"] . "<BR>";

        $cookie_work = new HolyCookie($params['cookie_var']);
        $array_of_items = $cookie_work->GetArray();

        $filter = $params['filter'];

        $res = new DBlockElement($params['table']);
        if (!isset($params['debug']))
            $params['debug'] = false;
        if (!isset($params['order']))
            $params['order'] = "sort ASC";
        $res->sql->debug = true;
        $res->GetList($filter, $params['order']);

        $all_summ = 0;
        $all_count = 0;
        while ($result1 = $res->GetNext()) {
            $result[] = $result1;
            $all_summ+=$array_of_items[$result1['id']] * $result1[$params['cost_var']];
            $all_count+=$array_of_items[$result1['id']];
        };

        if ($all_count > 0) {
            $mail_text.='<br>
    <table border="1" width="100%">
        <tr align="center">
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
        </tr>';


            foreach ($result as $item) {
                $cost_this = $params['items_count'][$item['id']] * $item['cost'];
                $url = "http://" . $_SERVER['HTTP_HOST'] . ReplaceURL($params['url'], $item);
                ;
                $mail_text.='
        <tr>
            <td>
                <a target="_blank" href="' . $url . '">
                ' . $item['caption'] . '
                </a>
            </td>
            <td>
                ' . $item['cost'] . '
            </td>
            <td>
                ' . $params['items_count'][$item['id']] . '
            </td>
            <td>
                ' . $cost_this . '
            </td>
        </tr>';
            };


            $mail_text.='</table><br>';
            $mail_text.="Общая сумма: " . $all_summ;
        }


        global $_OPTIONS;

        HolyMail($back_email, $_OPTIONS['mail'], "Создан заказ", $mail_text);

        $mail_text = "Поздравляю, ваш заказ принят! Скоро с вами свяжутся!";

        HolyMail($back_email, $_POST["email"], "Ваш заказ принят!", $mail_text);

        unset($_POST);
        $_POST = array();
        if ($params['back_cart_url'])
            Redirect($params['back_cart_url'] . "?complete=1", true);
        $cookie_work->Delete();
        echo "<p style='color:green'>сообщение отправлено успешно</p>";
    } else {
        foreach ($errors as $error) {
            ?>
            <p style="color:red;"><? echo $error; ?></p>
            <?
        }
    }
}

$_POST = fill_empty_array($_POST, Array(
    "fio",
    "email",
    "text"
        ));
?>

<form method="post">
    Ваше имя:<br>
    <input name="fio" value="<? echo $_POST['fio'] ?>" style="width: 300px;"><br>

    E-mail:<br>
    <input name="email" value="<? echo $_POST['email'] ?>" style="width: 300px;"><br>

    Адрес и дополнительные данные:<br>
    <textarea name="text" style="width: 300px;"><? echo $_POST['text'] ?></textarea><br> 

    <input type="submit" value="Заказть">
    <input type="hidden" name="go" value="1">
</form>
