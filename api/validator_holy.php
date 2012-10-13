<?

/**
 *  ласс дл€ валидации данных
 */
class HolyValidator {

    protected $rules;
    protected $errors;
    protected $field_names;

    /**
     * ≈сли указанные ключи не существуют в массиве - создаютс€ пустые
     * 
     * @param array $field_names  <p>массив вида "код пол€"=>"навание пол€"</p>
     */
    //@todo избавитьс€ от параметра - перенести все фразы и названи€ в i18-файл
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
                //todo нехорошооо
                //$options['field']."='".mysql_real_escape_string($array[$key])."'"
                $tmp = $res0->GetOne(Array($options['field'] => $array[$key]));
                if (isset($tmp))
                    if (isset($tmp['id']))
                        if ($tmp['id'])
                            $ok = false;
            }

        if (!$ok)
            $this->errors[] = "“акое значение пол€ " . $this->field_names[$key] . " уже существует.";
    }

    protected function Rule_equal($array, $key, $options) {
        $ok = false;
        if (isset($array[$key]))
            if (isset($array[$options]))
                if ($array[$options] === $array[$key])
                    $ok = true;

        if (!$ok)
            $this->errors[] = "Ќе соответствуют значени€ в пол€х " . $this->field_names[$key] . " и " . $this->field_names[$options];
    }

    protected function Rule_email($array, $key, $options) {
        $ok = true;
        if (isset($array[$key])) {
            $ok = filter_var(filter_var($array[$key], FILTER_SANITIZE_EMAIL), FILTER_VALIDATE_EMAIL);
        }
        else
            $ok = false;

        if (!$ok)
            $this->errors[] = "Ќеверный email в поле " . $this->field_names[$key];
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
            $this->errors[] = "Ќе заполнено поле " . $this->field_names[$key];
    }

    /**
     * ƒобавл€ет правило дл€ проверки
     * 
     * @param string $rule  <p>им€ правила дл€ валидации</p>
     * @param string $name  <p>им€ пол€ дл€ валидации</p>
     * @param array $options  <p>массив дополнительных опций</p>
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
     * ѕровер€ет массив по выбранным правилам.
     * 
     * @param array $array  <p>массив дл€ валидации</p>
     */
    public function Check($array) {
        if (count($this->rules) > 0) {
            foreach ($this->rules as $rule) {
                //вызывает метод, одноименный с выбранным правилом
                $function_name = "Rule_" . $rule['rule'];
                $this->$function_name($array, $rule['name'], $rule['options']);
            }
        }
    }

    /**
     * ¬озвращает true, если последн€€ проверка прошла упешно и массив ошибок, если нет.
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