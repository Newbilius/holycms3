<?php

class Component_auth_user_form extends Component {

    protected function GetDefaults() {
        return array(
            "template" => "default",
            "cabinet_url" => "/",
        );
    }

    protected function GetParamsValidators() {
        return array();
    }

    protected function Action() {
        $result=array();
        $user = HolyUser::getInstance();
        if (!isset($_POST['login']))
            $_POST['login'] = "";
        if (!isset($_POST['pass']))
            $_POST['pass'] = "";
        if (!isset($this->params['cabinet_url']))
            $this->params['cabinet_url'] = "/";
        if (isset($_POST['submit'])) {
            if (isset($_POST['login']))
                if ($_POST['login'] == "")
                    $error[] = "Не введён логин";
            if (isset($_POST['pass']))
                if ($_POST['pass'] == "")
                    $error[] = "Не введён пароль";
        };
        if (($_POST['login'] != "") && ($_POST['pass'] != ""))
            if (!$user->Auth($_POST['login'], $_POST['pass']))
                $error[] = "Неверный пароль или логин";
        if ($user->IsAuth())
            Redirect($this->params['cabinet_url'], true);

        if (isset($error)) {
            $result['errors'] = $error;
        }
        return $result;
    }

}

?>
