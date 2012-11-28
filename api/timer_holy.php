<?

/**
 * Класс для засечения временных промежутков.
 * 
 */
class CTimer {

    var $tstart;
    var $name;
    var $time;

/**
 * Создает таймер и тут же запускает его.
 * 
 * @param string $name <p>Имя таймера</p>
 * 
 */
    function CTimer($name) {
        //Считываем текущее время 
        $mtime = microtime();
        //Разделяем секунды и миллисекунды 
        $mtime = explode(" ", $mtime);
        //Составляем одно число из секунд и миллисекунд 
        $mtime = $mtime[1] + $mtime[0];
        //Записываем стартовое время в переменную 
        $this->tstart = $mtime;
        $this->name = $name;
    }

    
/**
 * Останавливает таймер и возвращает пройденное время, уже оформленное для вывода.
 * 
 */
    function Stop() {
        //Делаем все то же самое, чтобы получить текущее время 
        $mtime = microtime();
        $mtime = explode(" ", $mtime);
        $mtime = $mtime[1] + $mtime[0];
        //Записываем время окончания в другую переменную 
        $tend = $mtime;
        //Вычисляем разницу 
        $totaltime = ($tend - $this->tstart);
        $this->time = $totaltime;
        return $totaltime;
    }

/**
 * Встроенная функция для  вывода времени и названия таймера с оформлением.
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