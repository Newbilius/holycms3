<?php

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
 * @param bool $conver=false  <p>преобразовать windows-1251 в utf-8 данные</p>
 * @return array
 */
function PrepareXMLFromUrl($url, $convert = false) {
    $xml_data = file_get_contents($url);
    if ($convert) {
        $xml_data = iconv('windows-1251', 'utf-8', $xml_data);
        $xml_data = str_replace(Array('Windows-1251', 'windows-1251'), 'utf-8', $xml_data);
    };
    $xmlObj = new XmlToArray($xml_data);
    $arrayData = $xmlObj->createArray();
    return $arrayData;
}

;

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