<?php
/**
 * Класс для работы с пользователями сайта и администраторами
 */
class DUser extends DBlock {

    var $ID;
    var $inform;
    var $pass_name;
    var $login_name;
    var $cookie_prefix;
    var $table;

    /**
     * Определяем базовые настройки
     * 
     * @param bool $first  <p>сразу после создания объекта попытаться залогиниться по кукам</p>
     * @param string $table  <p>таблица с пользователями</p>
     * @param string $login_name  <p>поле с логинами</p>
     * @param string $pass_name  <p>поле с паролями</p>
     * @param string $cookie_prefix  <p>префикс сайтовых кук</p>
     * 
     * @return DUser
     */
    function DUser($first = 0, $table = "users", $login_name = "login", $pass_name = "pass", $cookie_prefix = "holy") {
        $this->cookie_prefix = $cookie_prefix;
        $this->login_name = $login_name;
        $this->pass_name = $pass_name;
        $this->table=$table;
        $this->ID = 0;

        $this->sql = new HolySQL($table);

        if ($first) {
            if (!isset($_COOKIE[$this->cookie_prefix . '_login']))
                $_COOKIE[$this->cookie_prefix . '_login'] = "";
            if (!isset($_COOKIE[$this->cookie_prefix . '_pass']))
                $_COOKIE[$this->cookie_prefix . '_pass'] = "";
            if (($_COOKIE[$this->cookie_prefix . '_login'] != "") && ($_COOKIE[$this->cookie_prefix . '_pass'] != ""))
                $this->ID = $this->Auth($_COOKIE[$this->cookie_prefix . '_login'], $_COOKIE[$this->cookie_prefix . '_pass']);
        };

        return $this;
    }

    /**
     * Проверяет, залогинен ли пользователь. Если да, возвращает его ID, если нет - false
     * 
     * @return bool/int
     */
    public function IsAuth() {
        if ($this->ID)
            return $this->ID;
        else
            $this->ID = $this->Auth($_COOKIE[$this->cookie_prefix . '_login'], $_COOKIE[$this->cookie_prefix . '_pass']);
        if ($this->ID)
            return $this->ID;
        return false;
    }

    /**
     * Возвращает ID пользователя
     * 
     * @return int
     */
    function GetID() {
        return $this->ID;
    }

    /**
     * Возвращает все поля пользователя
     * 
     * @return array
     */
    function GetInfo() {
        return $this->inform;
    }

    /**
     * Пытается залогиниться. Возвращает false или ID пользователя.
     * 
     * @param string $user  <p>логин</p>
     * @param string $pass  <p>пароль</p>
     * 
     * @return array/bool
     */
    function Auth($user, $pass) {

        $user = mysql_real_escape_string($user);
        $set_uid = false;
        $dat = $this->sql->SelectOnce($this->login_name . "='" . $user . "' AND uid='" . mysql_real_escape_string($pass) . "'");
        $this->inform = $dat;
        if (!isset($dat['id'])) {
            $dat = $this->sql->SelectOnce($this->login_name . "='" . $user . "' AND " . $this->pass_name . "='" . MD5($pass) . "'");
            $this->inform = $dat;
            if (!isset($dat['id'])) {
                $dat['id'] = 0;
            } else {
                $set_uid = true;
                AddToLog("Пользователь " . $this->login_name . " (" . $dat['id'] . ") ВОШЕЛ в систему администрирования.");
            };
        };
        $this->ID = $dat['id'];
        if ($this->ID != 0)
            if ($set_uid) {
                $pass = MD5(uniqid(time(), true) . time());
                setcookie($this->cookie_prefix . '_login', $user, time() + 90000, "/");
                setcookie($this->cookie_prefix . '_pass', $pass, time() + 90000, "/");
                $_COOIKE[$this->cookie_prefix . '_login'] = $user;
                $_COOIKE[$this->cookie_prefix . '_pass'] = $pass;
                //$this->sql->debug=true;
                $this->sql->Update("id=" . $this->ID, Array(
                    "uid" => $pass
                ));
            };
//preprint($this);
        return $this->GetID();
    }

    /**
     * Создает нового пользователя
     * 
     * @param string $user  <p>логин</p>
     * @param string $pass  <p>пароль</p>
     * @param array $params  <p>дополнительные параметры</p>
     */
    function AddUser($login, $pass,$params=array()) {
        $new_user_data = Array(
            "name"=>$login,
            $this->login_name => $login,
            $this->pass_name => MD5($pass),
        );
        $new_user_data=  array_merge($new_user_data,$params);
        $this->sql->Insert($new_user_data);
    }

    /**
     * Обновить выбранные данные
     * 
     * @param array $data  <p>данные</p>
     */
    
    function Update($data)
    {
        $tmp=new DBlockElement($this->table);
        $tmp->Update($this->ID, $data,false);
        $this->ID=0;
        $this->IsAuth();
    }
    /**
     * Выйти с сайта
     */
    function Logout() {
        AddToLog("Пользователь " . $_COOKIE[$this->cookie_prefix . '_login'] . " (" . $this->ID . ") ВЫШЕЛ из системы администрирования.");
        setcookie($this->cookie_prefix . '_login', $_COOKIE[$this->cookie_prefix . '_login'], time() - 90000, "/");
        setcookie($this->cookie_prefix . '_pass', $_COOKIE[$this->cookie_prefix . '_pass'], time() - 90000, "/");
        $_COOKIE[$this->cookie_prefix . '_login'] = "";
        $_COOKIE[$this->cookie_prefix . '_pass'] = "";
        $this->ID = 0;
    }

}


/**
 * Класс-оберта для работы с пользователями сайта (НЕ администраторами)
 */
class HolyUser extends DUser {

    private static $instance = null;

    public static function getInstance() {
        if (is_null(self::$instance)) {
            self::$instance = new HolyUser ();
        }

        return self::$instance;
    }

    private function __construct() {
        parent::DUser(1, "site_users", "email", "password", "holy_site_user");
    }

    private function __clone() {
        
    }

};
?>