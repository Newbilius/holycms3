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
    protected $uid;
    var $read;
    var $add;
    var $edit;
    var $delete;

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
        $this->table = $table;
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
     * Возвращает <b>true</b>, если пользователь имеет админские права
     * 
     * @return boolean
     */
    public function IsAdmin() {
        return $this->inform['block_control'];
    }

    /**
     * Возвращает <b>true</b>, если пользователь имеет право добавлять элементы или папки в блок <b>$name</b>
     * 
     * @param string $name <p>имя блока данных/таблицы</p>
     * @return boolean
     */
    public function CanAdd($name) {
        if (in_array($name, $this->add))
            return true;
        return false;
    }

    /**
     * Возвращает <b>true</b>, если пользователь имеет право удалять элементы или папки из блока <b>$name</b>
     * 
     * @param string $name <p>имя блока данных/таблицы</p>
     * @return boolean
     */
    public function CanDelete($name) {
        if (in_array($name, $this->delete))
            return true;
        return false;
    }

    /**
     * Возвращает <b>true</b>, если пользователь имеет право редактировать элементы или папки в блоке <b>$name</b>
     * 
     * @param string $name <p>имя блока данных/таблицы</p>
     * @return boolean
     */
    public function CanEdit($name) {
        if (in_array($name, $this->edit))
            return true;
        return false;
    }

    /**
     * Возвращает <b>true</b>, если пользователь имеет право просматривать элементы или папки в блоке <b>$name</b>
     * 
     * @param string $name <p>имя блока данных/таблицы</p>
     * @return boolean
     */
    public function CanRead($name) {
        if (in_array($name, $this->read))
            return true;
        return false;
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
        $dat = $this->sql->SelectOnce($this->login_name . "='" . $user . "' AND uid LIKE'%" . mysql_real_escape_string($pass) . "%'");
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
        if ($this->ID != 0) {
            if ($set_uid) {
                $pass = MD5(uniqid(time(), true) . time());
                $uid_list = explode(";", $dat['uid']);
                $uid_list[] = $pass;
                if (count($uid_list) > 5)
                    unset($uid_list[0]);
                $save_uid = implode(";", $uid_list);
                setcookie($this->cookie_prefix . '_login', $user, time() + 90000, "/");
                setcookie($this->cookie_prefix . '_pass', $pass, time() + 90000, "/");
                $_COOIKE[$this->cookie_prefix . '_login'] = $user;
                $_COOIKE[$this->cookie_prefix . '_pass'] = $pass;
                $this->sql->Update("id=" . $this->ID, Array(
                    "uid" => $save_uid
                ));
                $this->inform['uid'] = $save_uid;
            };
            //получаем информацию о группе      
            $users_groups_rs = new DBlockElement("system_user_groups");
            if (!isset($dat['group']))
            {
                $this->read=array();
                $this->add=array();
                $this->edit=array();
                $this->delete=array();
            }else{
            $users_groups = $users_groups_rs->GetOne("id=" . $dat['group']);
            $this->read = explode(";", $users_groups['read']);
            $this->add = explode(";", $users_groups['add']);
            $this->edit = explode(";", $users_groups['edit']);
            $this->delete = explode(";", $users_groups['delete']);
            };
            $this->uid = $pass;
        };

        return $this->GetID();
    }

    /**
     * Создает нового пользователя
     * 
     * @param string $user  <p>логин</p>
     * @param string $pass  <p>пароль</p>
     * @param array $params  <p>дополнительные параметры</p>
     */
    function AddUser($login, $pass, $params = array()) {
        $new_user_data = Array(
            "name" => $login,
            $this->login_name => $login,
            $this->pass_name => MD5($pass),
        );
        $new_user_data = array_merge($new_user_data, $params);
        $this->sql->Insert($new_user_data);
    }

    /**
     * Обновить выбранные данные пользователя
     * 
     * @param array $data  <p>данные</p>
     * @param integer $id  <p>id пользователя (не обязаельное - если не указать, обрабатывается текущий)</p>
     */
    function Update($data, $id = 0) {
        $tmp = new DBlockElement($this->table);
        if ($id == 0) {
            $tmp->Update($this->ID, $data, false);
        } else {
            $tmp->Update($this->ID, $data, false);
        }
        $this->ID = 0;
        $this->IsAuth();
    }

    /**
     * Выйти с сайта
     */
    function Logout() {
        AddToLog("Пользователь " . $_COOKIE[$this->cookie_prefix . '_login'] . " (" . $this->ID . ") ВЫШЕЛ из системы администрирования.");

        $uid_list = explode(";", $this->inform['uid']);

        foreach ($uid_list as $num => $_uid) {
            if ($_uid == $this->uid) {
                unset($uid_list[$num]);
            };
        }

        $uid_list_save = implode(";", $uid_list);
        $this->sql->Update("id=" . $this->ID, Array(
            "uid" => $uid_list_save
        ));
        $this->ID = 0;
        setcookie($this->cookie_prefix . '_login', $_COOKIE[$this->cookie_prefix . '_login'], time() - 90000, "/");
        setcookie($this->cookie_prefix . '_pass', $_COOKIE[$this->cookie_prefix . '_pass'], time() - 90000, "/");
        $_COOKIE[$this->cookie_prefix . '_login'] = "";
        $_COOKIE[$this->cookie_prefix . '_pass'] = "";
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

}

;
?>