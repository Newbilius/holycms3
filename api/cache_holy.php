<?

/**
 * ����� ��� ������ � ������������ ������.
 * 
 * ������ �������������:
 * $cache_component = new HolyCacheOut($key, $params['cache_time'], $params['block']);
 * $result_cache = $cache_component->StartCacheOut();
 * if ($result_cache) {
 * [...]
 * $cache_component->EndCacheOut();
 * };
 */
class HolyCacheOut {

    var $key;
    var $sql;
    var $time;
    var $temp_data;
    var $module;

    /**
     * �������������� �����.
     * 
     * @param string $key  <p>�������������� ���� - ���������� ����</p>
     * @param int $time  <p>����� ����� ���� �� ������������. ��-��������� - 90 ������.</p>
     * @param string $module  <p>���������� �����, ������������ ������. ���� ��������� � ����� �� ������ - ��� ��������� ������ � ������� ��� ����� ���������.</p>
     */
    function HolyCacheOut($key = "", $time = 90, $module = "TEMP") {
        global $_CONFIG;
        if ($key == "")
            $key = MD5(time());

        if ($_CONFIG['CACHE_SYSTEM']) {
            $this->key = $key . "_" . $module;
            $this->module = $module;
            $this->time = $time;
            $this->sql = new HolySQL('system_cache');
        };
    }

    /**
     * ������ ���������� �����
     * 
     * @return bool ����� �� �������� ������ �� ������� (true) ��� �� ���� (false)
     */
    function StartCacheOut() {
        global $_CONFIG;
        if ($_CONFIG['CACHE_SYSTEM']) {
            $tmpdata = $this->sql->SelectOnce("id='" . $this->key . "'", 'id');

            if ((isset($tmpdata['id'])) && (time() - $tmpdata['time'] < $this->time)) {
                echo $tmpdata['value'];
                return false;
            } else {
                ob_start();
                return true;
            };
        };
    }

    /**
     * ��������� ������ � ��������� ������ � ����.
     * 
     */
    function EndCacheOut() {
        $this->temp_data = ob_get_contents();
        ob_end_clean();
        echo $this->temp_data;

        global $_CONFIG;
        if ($_CONFIG['CACHE_SYSTEM']) {
            $this->sql->Delete("id='" . $this->key . "'");
            $this->sql->Insert(array(
                "id" => $this->key,
                "value" => $this->temp_data,
                "time" => time(),
                "module" => $this->module,
            ));
        };
    }

    /**
     * �������� ���� ��� �� �����
     * 
     */
    function ClearFull() {
        global $_CONFIG;
        if ($_CONFIG['CACHE_SYSTEM'])
            $this->sql->Delete("1");
    }

    /**
     * �������� ���, ��������� � ������ �������
     * 
     */
    function Clear() {
        global $_CONFIG;
        if ($_CONFIG['CACHE_SYSTEM'])
            $this->sql->Delete("module='" . $this->module . "' OR module='TEMP'");
    }

}

;
?>