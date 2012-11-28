<?

/**
 * Класс для работы с куками.
 */
class HolyCookie {

    protected $cname;

    /**
     * Запоминает название куки
     * 
     * @param string $cname  <p>[не обязательное] название куки для операций</p>
     * 
     * @return HolyCookie
     */
    public function HolyCookie($cname = "holycms_cookie") {

        $this->cname = $cname;

        return $this;
    }

    /**
     * Запоминает название куки
     * 
     * @param string $cname  <p>[не обязательное] название куки для операций</p>
     * 
     * @return HolyCookie
     */
    public function SetCookieName($cname) {
        $this->cname = $cname;

        return $this;
    }

    /**
     * Добавляет текущую куку в параметр $key значение $value
     * 
     * @param string $key  <p>ключ массива</p>
     * @param string $value  <p>значение для хранения</p>
     * 
     * @return HolyCookie
     */
    public function AddToArray($key, $value) {
        if (isset($_COOKIE[$this->cname]))
            $_COOKIE[$this->cname] = @unserialize($_COOKIE[$this->cname]);
        else
            $_COOKIE[$this->cname] = array();
        if (!is_array($_COOKIE[$this->cname]))
            $_COOKIE[$this->cname] = array();

        $_COOKIE[$this->cname][$key] = $value;

        $_tmp_value = serialize($_COOKIE[$this->cname]);
        setcookie($this->cname, $_tmp_value, 0, "/");

        return $this;
    }

    /**
     * Помещает массив в куку
     * @param array $array  <p>массив</p>
     * 
     * @return HolyCookie
     */
    public function SetArray($array) {
        $_COOKIE[$this->cname] = $array;

        $_tmp_value = serialize($_COOKIE[$this->cname]);
        setcookie($this->cname, $_tmp_value, 0, "/");

        return $this;
    }

    /**
     * Удаляет элемент $key из текущей куки как массива
     * 
     * @param string $key  <p>ключ массива</p>
     * @return HolyCookie
     */
    public function DeleteFormArray($key) {
        if (isset($_COOKIE[$this->cname])) {
            if (!is_array($_COOKIE[$this->cname]))
                $_COOKIE[$this->cname] = @unserialize($_COOKIE[$this->cname]);
        }
        else
            $_COOKIE[$this->cname] = array();

        if (!is_array($_COOKIE[$this->cname]))
            $_COOKIE[$this->cname] = array();

        unset($_COOKIE[$this->cname][$key]);

        $_tmp_value = serialize($_COOKIE[$this->cname]);
        setcookie($this->cname, $_tmp_value, 0, "/");

        return $this;
    }

    /**
     * Возвращает текущую куку как массив
     * 
     * @return array
     */
    public function GetArray() {
        if (isset($_COOKIE[$this->cname])) {
            if (!is_array($_COOKIE[$this->cname]))
                $_COOKIE[$this->cname] = @unserialize($_COOKIE[$this->cname]);
        }
        else
            $_COOKIE[$this->cname] = array();
        if (!is_array($_COOKIE[$this->cname]))
            $_COOKIE[$this->cname] = array();

        return $_COOKIE[$this->cname];
    }

    /**
     * Удаляет текущую куку
     * 
     * @return HolyCookie
     */
    public function Delete() {
        unset($_COOKIE[$this->cname]);
        setcookie($this->cname, "", time() - 3600);

        return $this;
    }

    //@todo добавть функцию, возвращающую список ключей
    //или найти такую функцию в готовых PHP
}

;
?>