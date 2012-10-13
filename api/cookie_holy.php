<?

/**
 * ����� ��� ������ � ������.
 */
class HolyCookie {

    protected $cname;

    /**
     * ���������� �������� ����
     * 
     * @param string $cname  <p>[�� ������������] �������� ���� ��� ��������</p>
     * 
     * @return HolyCookie
     */
    public function HolyCookie($cname = "holycms_cookie") {

        $this->cname = $cname;

        return $this;
    }

    /**
     * ���������� �������� ����
     * 
     * @param string $cname  <p>[�� ������������] �������� ���� ��� ��������</p>
     * 
     * @return HolyCookie
     */
    public function SetCookieName($cname) {
        $this->cname = $cname;

        return $this;
    }

    /**
     * ��������� ������� ���� � �������� $key �������� $value
     * 
     * @param string $key  <p>���� �������</p>
     * @param string $value  <p>�������� ��� ��������</p>
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
     * �������� ������ � ����
     * @param array $array  <p>������</p>
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
     * ������� ������� $key �� ������� ���� ��� �������
     * 
     * @param string $key  <p>���� �������</p>
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
     * ���������� ������� ���� ��� ������
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
     * ������� ������� ����
     * 
     * @return HolyCookie
     */
    public function Delete() {
        unset($_COOKIE[$this->cname]);
        setcookie($this->cname, "", time() - 3600);

        return $this;
    }

    //@todo ������� �������, ������������ ������ ������
    //��� ����� ����� ������� � ������� PHP
}

;
?>