<?

/**
 * ����� ��� ��������� ��������� �����������.
 * 
 */
class CTimer {

    var $tstart;
    var $name;
    var $time;

/**
 * ������� ������ � ��� �� ��������� ���.
 * 
 * @param string $name <p>��� �������</p>
 * 
 */
    function CTimer($name) {
        //��������� ������� ����� 
        $mtime = microtime();
        //��������� ������� � ������������ 
        $mtime = explode(" ", $mtime);
        //���������� ���� ����� �� ������ � ����������� 
        $mtime = $mtime[1] + $mtime[0];
        //���������� ��������� ����� � ���������� 
        $this->tstart = $mtime;
        $this->name = $name;
    }

    
/**
 * ������������� ������ � ���������� ���������� �����, ��� ����������� ��� ������.
 * 
 */
    function Stop() {
        //������ ��� �� �� �����, ����� �������� ������� ����� 
        $mtime = microtime();
        $mtime = explode(" ", $mtime);
        $mtime = $mtime[1] + $mtime[0];
        //���������� ����� ��������� � ������ ���������� 
        $tend = $mtime;
        //��������� ������� 
        $totaltime = ($tend - $this->tstart);
        $this->time = $totaltime;
        return $totaltime;
    }

/**
 * ���������� ������� ���  ������ ������� � �������� ������� � �����������.
 * 
 */
    function Draw() {
        ?>
        <div style="border:1px solid red;">
            <?
            echo $this->name . " " . $this->time;
            ?>
            <BR></div>
        <?
    }

}

;
?>