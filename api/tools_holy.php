<?php

/***
 * Скачивает через Curl файл и возвращает его содержимое.
 * 
 * @param string $url <p>имя файла</p>
 */

function FileDownloadCURL($url) {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_FAILONERROR, 1);
    curl_setopt($ch, CURLOPT_VERBOSE, 1);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_TIMEOUT, 90);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
    $result = curl_exec($ch);
    curl_close($ch);
    return $result;
}

/**
 * Преобразует XML-данные в массив
 * 
 * @param string $xml_data  <p>данные</p>
 * @return array
 */
function ParseXMLToArray($xml_data) {
    $xmlObj = new XmlToArray($xml_data);
    $arrayData = $xmlObj->createArray();
    return $arrayData;
}

;

/**
 * Скачивае по ссылке XML-файл, преобразуя его в массив
 * 
 * @param string $url  <p>ссылка для скачивания</p>
 * @param bool/array $convert=false  <p>преобразовать из кодировки $convert[0] в $convert[1] данные</p>
 *
 * @return array
 */
function PrepareXMLFromUrl($url, $convert = false) {
    $xml_data = file_get_contents($url);
    if (is_array($convert)) {
        $xml_data = iconv($convert[0], $convert[1], $xml_data);
        $xml_data = str_replace(Array($convert[0]), $convert[1], $xml_data);
    };
    $xmlObj = new XmlToArray($xml_data);
    $arrayData = $xmlObj->createArray();
    return $arrayData;
}

/**
 * Рекурсивно вызывает функцию iconv для всех элементов массива.
 *
 * @param string $in_charset <p>изначальная кодировка</p>
 * @param string $out_charset <p>кодировка на выходе</p>
 * @param array $arr <p>массив для преобразования</p>
 *
 * @return array
 */
function recursive_iconv($in_charset,$out_charset, $arr){ 
         if (!is_array($arr)){ 
             return iconv($in_charset, $out_charset, $arr); 
         } 
         $ret = $arr; 
         function array_iconv(&$val, $key, $userdata){ 
             $val = iconv($userdata[0], $userdata[1], $val); 
         } 
         array_walk_recursive($ret, "array_iconv", array($in_charset, $out_charset)); 
         return $ret; 
     }

/**
 * Возвращает IP пользователя
 * 
 * @return string
 */
function getIP() {
    if (isset($_SERVER['HTTP_X_REAL_IP']))
        return $_SERVER['HTTP_X_REAL_IP'];
    return $_SERVER['REMOTE_ADDR'];
}

;

/**
 * Функция преобразования текста в транслит
 * 
 * @param string $string  <p>преобразуемая строка</p>
 * 
 * @return string
 */
function rus2translit($string) {
    $converter = array(
        'а' => 'a', 'б' => 'b', 'в' => 'v',
        'г' => 'g', 'д' => 'd', 'е' => 'e',
        'ё' => 'e', 'ж' => 'zh', 'з' => 'z',
        'и' => 'i', 'й' => 'y', 'к' => 'k',
        'л' => 'l', 'м' => 'm', 'н' => 'n',
        'о' => 'o', 'п' => 'p', 'р' => 'r',
        'с' => 's', 'т' => 't', 'у' => 'u',
        'ф' => 'f', 'х' => 'h', 'ц' => 'c',
        'ч' => 'ch', 'ш' => 'sh', 'щ' => 'sch',
        'ь' => "_", 'ы' => 'y', 'ъ' => "_",
        'э' => 'e', 'ю' => 'yu', 'я' => 'ya',
        'А' => 'A', 'Б' => 'B', 'В' => 'V',
        'Г' => 'G', 'Д' => 'D', 'Е' => 'E',
        'Ё' => 'E', 'Ж' => 'Zh', 'З' => 'Z',
        'И' => 'I', 'Й' => 'Y', 'К' => 'K',
        'Л' => 'L', 'М' => 'M', 'Н' => 'N',
        'О' => 'O', 'П' => 'P', 'Р' => 'R',
        'С' => 'S', 'Т' => 'T', 'У' => 'U',
        'Ф' => 'F', 'Х' => 'H', 'Ц' => 'C',
        'Ч' => 'Ch', 'Ш' => 'Sh', 'Щ' => 'Sch',
        'Ь' => "_", 'Ы' => 'Y', 'Ъ' => "_",
        'Э' => 'E', 'Ю' => 'Yu', 'Я' => 'Ya',
        " " => "_", '"' => "_", "'" => "_", "," => "_", ";" => "_",
        ":" => "_", ")" => "_", "(" => "_", "?" => "_", "!" => "_",
    );
    $string = str_replace(Array("(", ")", ",", ";", ">", "<"), "_", $string);
    return strtr($string, $converter);
}

;

/**
 * Системная функция. Помещает содержимое массива $_FILES в $_POST, причем 
 * каждый элемент содержит свои переменные tmp_name,type и т.п
 * 
 * @param string $text  <p>текст ошибки</p>
 */
function POSTPreWork() {
    foreach ($_FILES as $name => $f)
        if (is_array($f['name'])) {
            $max = count($f['name']) - 1;
            for ($i = 0; $i <= $max; $i++) {
                $_POST[$name][] = array("name" => $f["name"][$i],
                    "type" => $f["type"][$i],
                    "tmp_name" => $f["tmp_name"][$i],
                    "error" => $f["error"][$i],
                    "size" => $f["size"][$i],
                );
            };
        } else {
            $_POST[$name] = $f;
        };
}

;

/**
 * Добавляет элемент в навигационную строку.
 * 
 * @param string $name  <p>название элемента</p>
 * @param string $url  <p>ссылка</p>
 */
function AddToBread($name, $url = "") {
    global $_global_bread;
    $_global_bread[] = Array($name, $url);
}

;

function PopUpImages($class = "picture") {
    ?>
    <script>
        $(document).ready(function() {
            $("a.<?= $class ?>").fancybox(
            {						
                "padding" : 2, // отступ контента от краев окна
                "imageScale" : false, // Принимает значение true - контент(изображения) масштабируется по размеру окна, или false - окно вытягивается по размеру контента. По умолчанию - TRUE
                "zoomOpacity" : false,	// изменение прозрачности контента во время анимации (по умолчанию false)
                "zoomSpeedIn" : 1000,	// скорость анимации в мс при увеличении фото (по умолчанию 0)
                "zoomSpeedOut" : 1000,	// скорость анимации в мс при уменьшении фото (по умолчанию 0)
                "zoomSpeedChange" : 1000, // скорость анимации в мс при смене фото (по умолчанию 0)
                "frameWidth" : 1000,	 // ширина окна, px (425px - по умолчанию)
                //"frameHeight" : 600, // высота окна, px(355px - по умолчанию)
                "overlayShow" : true, // если true затеняят страницу под всплывающим окном. (по умолчанию true). Цвет задается в jquery.fancybox.css - div#fancy_overlay 
                "overlayOpacity" : 0.8,	 // Прозрачность затенения 	(0.3 по умолчанию)
                "hideOnContentClick" :true, // Если TRUE  закрывает окно по клику по любой его точке (кроме элементов навигации). Поумолчанию TRUE		
                "centerOnScroll" : false // Если TRUE окно центрируется на экране, когда пользователь прокручивает страницу		
            });
        });
    </script>
    <?
}

function GetLangEngBlock($eng, $rus) {
    global $_OPTIONS;
    if ($_OPTIONS['LANG'] == "en")
        return $eng;
    else
        return $rus;
}

;

/**
 * Устанавливает мета-теги текущей страницы.
 * 
 * @param array $data  <p>параметры (все не обязательные):<br>
 * <b>caption2</b>,<b>title</b>  - заголовок страницы<br>
 * <b>keywords</b>  - ключевые слова<br>
 * <b>description</b>  - описание страницы (description)<br>
 * </p>
 */
function SetMetatags($data) {

    global $_selected_page;
    global $_OPTIONS;

    if (isset($data['caption2']))
        if ($data['caption2']) {
            $_OPTIONS['site_title'] = $data['caption2'];
            $_selected_page['caption2'] = $data['caption2'];
        };
    if (isset($data['title']))
        if ($data['title']) {
            $_OPTIONS['site_title'] = $data['title'];
            $_selected_page['caption2'] = $data['title'];
        };
    if (isset($data['keywords']))
        if ($data['keywords']) {
            $_OPTIONS['keywords'] = $data['keywords'];
            $_selected_page['keywords'] = $data['keywords'];
        };
    if (isset($data['description']))
        if ($data['description']) {
            $_OPTIONS['description'] = $data['description'];
            $_selected_page['description'] = $data['description'];
        };
//preprint($data);
}

;

/**
 * Подменяет значения в строке на значения из массива,<br>
 * в шаблоне можно использовать вставки вида #индекс_массива#<br>
 * <br>
 * <b>Пример:</b> #id# заменяется на значение $data['id']
 * 
 * @param string $url  <p>ссылка-шаблон</p>
 * @param array $data  <p>даные для замены</p>
 * @return string
 */
function ReplaceURL($url, $data) {
    foreach ($data as $num => $value)
        if (!is_array($value))
            $url = str_replace("#" . $num . "#", $value, $url);
	//@todo добавить обработку массивов, метки вида #переменная|номер/код в массиве#
    return $url;
}

/**
 * Устанавливает настройку
 * 
 * @param string $name  <p>имя настройки</p>
 * @param string $value  <p>её значение</p>
 */
function SetOptions($name, $value) {
    global $_OPTIONS;
    $_OPTIONS[$name] = $value;
}

/**
 * Производит редирект по адресу $url с помощью header'а (и, возможно, JS).
 * 
 * @param string $url  <p>адрес редиректа</p>
 * @param bool $js  <p>так же добавить код редиркета редирект на JavaScript</p>
 */
function Redirect($url, $js = false) {
    header('Location: ' . $url);
    if ($js) {
        ?>
        <script>
            window.location='<?= $url ?>';
        </script>
        <?
    }
}

/**
 * Возвращает адрес сервера
 * 
 * @return string
 */
function GetSiteURL() {
    return "http://" . $_SERVER['HTTP_HOST'];
}

/**
 * Генератор пароля
 * 
 * @param int $number  <p>число символов в пароле</p>
 * 
 * return string
 */
function PasswordGenerate($number) {
    $arr = array('a', 'b', 'c', 'd', 'e', 'f',
        'g', 'h', 'i', 'j', 'k', 'l',
        'm', 'n', 'o', 'p', 'r', 's',
        't', 'u', 'v', 'x', 'y', 'z',
        'A', 'B', 'C', 'D', 'E', 'F',
        'G', 'H', 'I', 'J', 'K', 'L',
        'M', 'N', 'O', 'P', 'R', 'S',
        'T', 'U', 'V', 'X', 'Y', 'Z',
        '1', '2', '3', '4', '5', '6',
        '7', '8', '9', '0', '_',);
    // Генерируем пароль
    $pass = "";
    for ($i = 0; $i < $number; $i++) {
        // Вычисляем случайный индекс массива
        $index = rand(0, count($arr) - 1);
        $pass .= $arr[$index];
    }
    return $pass;
}

/**
 * Возвращает форму слова в зависимости от колличества $count.
 * 
 * @param int $count <p>число элементов</p>
 * @param array $forms <p>Формы слова, пример {носок,носка,носков}</p>
 */
function GetWordForms($count,$forms)
{
    if ($count==1) return $forms[0];
    elseif (($count>1) && ($count<=4)) return $forms[1];
    else return $forms[2];
};

/**
 * Возвращет MIME тип файла.
 * 
 * @param string $file_name <p>путь к файлу.</p>
 */
function GetMIME($file_name) {
    if (function_exists("finfo_file")) {
        $finfo = finfo_open(FILEINFO_MIME_TYPE); // return mime type ala mimetype extension
        $mime = finfo_file($finfo, $file_name);
        finfo_close($finfo);
        return $mime;
    } else if (function_exists("mime_content_type")) {
        return mime_content_type($file_name);
    } else if (!stristr(ini_get("disable_functions"), "shell_exec")) {
        $file = escapeshellarg($file_name);
        $mime = shell_exec("file -bi " . $file);
        return $mime;
    } else {
        return "";
    }
}

/**
 * Возвращает полный адрес страницы по её id или name
 *
 * @param string/id $id  <p>id/name</p>
 *  
 * @return string
 */
function GetPageURL($id) {
    $url="";
    global $_OPTIONS;
    $sql=new DBlockElement($_OPTIONS['page_module']);
    
    $page=$sql->GetByID ($id);
    
    if ($page['parent']==0)
        {
            if ($page['redirect'])
                $url=$page['redirect'];
            else
                $url="/".$page['name'];
        }
        else
        {
            $page_array[]=$page['name'];
            
            while ($page['parent']!=0)
            {
                $page=$sql->GetByID ($page['parent']);
                $page_array[]=$page['name'];
            };
            
            $page_array=  array_reverse($page_array);
            $url=implode("/",$page_array);
        };
    
    return $url;
}
?>