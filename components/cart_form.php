<?php

class Component_cart_form extends Component {

    protected $result_array = true;

    protected function GetDefaults() {
        return array(
            "filter" => array(),
            "cache" => false,
            "cache_time" => 90,
            "cache_key" => null,
            "item_url" => "",
            "cost_var" => "",
            "cookie_var" => "catalog_items",
            "list_items_template" => "cart_template",
            "form_template" => "",
            "back_cart_url" => "",
            "form_component" => "",
            "debug" => false,
            "order" => "caption ASC",
            "template" => "default",
        );
    }

    protected function PrepareCache() {
        return false;
    }

    
    protected function Action() {
        $_POST = clear_array($_POST);

        $errors = true;

        $result = array();
        $user = HolyUser::getInstance();

        if ($user->IsAuth()) {
            $info = $user->GetInfo();
            $_POST['fio'] = $info['fio'];
            $_POST['email'] = $info['email'];
            $_POST['text'] = $info['address'];
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

                $cookie_work = new HolyCookie($this->params['cookie_var']);
                $array_of_items = $cookie_work->GetArray();

                $filter = $this->params['filter'];

                $res = new DBlockElement($this->params['table']);

                $res->sql->debug = $this->params['debug'];
                
                $res->GetList($filter, $this->params['order']);

                $all_summ = 0;
                $all_count = 0;
                while ($result1 = $res->GetNext()) {
                    $result[] = $result1;
                    $all_summ+=$array_of_items[$result1['id']] * $result1[$this->params['cost_var']];
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
                        $cost_this = $this->params['items_count'][$item['id']] * $item['cost'];
                        $url = "http://" . $_SERVER['HTTP_HOST'] . ReplaceURL($this->params['url'], $item);
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
                ' . $this->params['items_count'][$item['id']] . '
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
                if ($this->params['back_cart_url'])
                    Redirect($this->params['back_cart_url'] . "?complete=1", true);
                $cookie_work->Delete();
                echo "<p style='color:green'>сообщение отправлено успешно</p>";
            };
        }

        $result['errors'] = $errors;

        $_POST = fill_empty_array($_POST, Array(
            "fio",
            "email",
            "text"
                ));
        $result['form_values'] = $_POST;

        return $result;
    }

}
?>
