<?

/**
 * ����� ��� ��������� ������
 */
class HolyValidator {

    protected $rules;
    protected $errors;
    protected $field_names;

    /**
     * ���� ��������� ����� �� ���������� � ������� - ��������� ������
     * 
     * @param array $field_names  <p>������ ���� "��� ����"=>"������� ����"</p>
     */
    //@todo ���������� �� ��������� - ��������� ��� ����� � �������� � i18-����
    public function HolyValidator($field_names) {
        $this->ok = false;
        $this->errors = array();
        $this->field_names = $field_names;
    }

    protected function Rule_unique($array, $key, $options) {
        $ok = true;

        if (isset($array[$key]))
            if ($array[$key]) {
                $res0 = new DBlockElement($options['table']);
                //todo ����������
                //$options['field']."='".mysql_real_escape_string($array[$key])."'"
                $tmp = $res0->GetOne(Array($options['field'] => $array[$key]));
                if (isset($tmp))
                    if (isset($tmp['id']))
                        if ($tmp['id'])
                            $ok = false;
            }

        if (!$ok)
            $this->errors[] = "����� �������� ���� " . $this->field_names[$key] . " ��� ����������.";
    }

    protected function Rule_equal($array, $key, $options) {
        $ok = false;
        if (isset($array[$key]))
            if (isset($array[$options]))
                if ($array[$options] === $array[$key])
                    $ok = true;

        if (!$ok)
            $this->errors[] = "�� ������������� �������� � ����� " . $this->field_names[$key] . " � " . $this->field_names[$options];
    }

    protected function Rule_email($array, $key, $options) {
        $ok = true;
        if (isset($array[$key])) {
            $ok = filter_var(filter_var($array[$key], FILTER_SANITIZE_EMAIL), FILTER_VALIDATE_EMAIL);
        }
        else
            $ok = false;

        if (!$ok)
            $this->errors[] = "�������� email � ���� " . $this->field_names[$key];
    }

    protected function Rule_not_empty($array, $key, $options) {
        $ok = true;
        if (isset($array[$key])) {
            if (!$array[$key])
                $ok = false;
        }
        else
            $ok = false;

        if (!$ok)
            $this->errors[] = "�� ��������� ���� " . $this->field_names[$key];
    }

    /**
     * ��������� ������� ��� ��������
     * 
     * @param string $rule  <p>��� ������� ��� ���������</p>
     * @param string $name  <p>��� ���� ��� ���������</p>
     * @param array $options  <p>������ �������������� �����</p>
     * 
     * @return HolyValidator
     */
    public function AddRule($rule, $name, $options = array()) {
        $this->rules[] = Array(
            "name" => $name,
            "rule" => $rule,
            "options" => $options,
        );
        return $this;
    }

    /**
     * ��������� ������ �� ��������� ��������.
     * 
     * @param array $array  <p>������ ��� ���������</p>
     */
    public function Check($array) {
        if (count($this->rules) > 0) {
            foreach ($this->rules as $rule) {
                //�������� �����, ����������� � ��������� ��������
                $function_name = "Rule_" . $rule['rule'];
                $this->$function_name($array, $rule['name'], $rule['options']);
            }
        }
    }

    /**
     * ���������� true, ���� ��������� �������� ������ ������ � ������ ������, ���� ���.
     * 
     * @return array/bool
     */
    public function Complete() {
        if (count($this->errors) === 0)
            return true;
        else
            return $this->errors;
    }

}

?>