<?php

/***
 * ��������� ����� Curl ���� � ���������� ��� ����������.
 * 
 * @param string $url <p>��� �����</p>
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
 * ����������� XML-������ � ������
 * 
 * @param string $xml_data  <p>������</p>
 * @return array
 */
function ParseXMLToArray($xml_data) {
    $xmlObj = new XmlToArray($xml_data);
    $arrayData = $xmlObj->createArray();
    return $arrayData;
}

;

/**
 * �������� �� ������ XML-����, ���������� ��� � ������
 * 
 * @param string $url  <p>������ ��� ����������</p>
 * @param bool $conver=false  <p>������������� windows-1251 � utf-8 ������</p>
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
 * ���������� IP ������������
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
 * ������� �������������� ������ � ��������
 * 
 * @param string $string  <p>������������� ������</p>
 * 
 * @return string
 */
function rus2translit($string) {
    $converter = array(
        '�' => 'a', '�' => 'b', '�' => 'v',
        '�' => 'g', '�' => 'd', '�' => 'e',
        '�' => 'e', '�' => 'zh', '�' => 'z',
        '�' => 'i', '�' => 'y', '�' => 'k',
        '�' => 'l', '�' => 'm', '�' => 'n',
        '�' => 'o', '�' => 'p', '�' => 'r',
        '�' => 's', '�' => 't', '�' => 'u',
        '�' => 'f', '�' => 'h', '�' => 'c',
        '�' => 'ch', '�' => 'sh', '�' => 'sch',
        '�' => "_", '�' => 'y', '�' => "_",
        '�' => 'e', '�' => 'yu', '�' => 'ya',
        '�' => 'A', '�' => 'B', '�' => 'V',
        '�' => 'G', '�' => 'D', '�' => 'E',
        '�' => 'E', '�' => 'Zh', '�' => 'Z',
        '�' => 'I', '�' => 'Y', '�' => 'K',
        '�' => 'L', '�' => 'M', '�' => 'N',
        '�' => 'O', '�' => 'P', '�' => 'R',
        '�' => 'S', '�' => 'T', '�' => 'U',
        '�' => 'F', '�' => 'H', '�' => 'C',
        '�' => 'Ch', '�' => 'Sh', '�' => 'Sch',
        '�' => "_", '�' => 'Y', '�' => "_",
        '�' => 'E', '�' => 'Yu', '�' => 'Ya',
        " " => "_", '"' => "_", "'" => "_", "," => "_", ";" => "_",
        ":" => "_", ")" => "_", "(" => "_", "?" => "_", "!" => "_",
    );
    $string = str_replace(Array("(", ")", ",", ";", ">", "<"), "_", $string);
    return strtr($string, $converter);
}

;

/**
 * ��������� �������. �������� ���������� ������� $_FILES � $_POST, ������ 
 * ������ ������� �������� ���� ���������� tmp_name,type � �.�
 * 
 * @param string $text  <p>����� ������</p>
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
 * ��������� ������� � ������������� ������.
 * 
 * @param string $name  <p>�������� ��������</p>
 * @param string $url  <p>������</p>
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
                "padding" : 2, // ������ �������� �� ����� ����
                "imageScale" : false, // ��������� �������� true - �������(�����������) �������������� �� ������� ����, ��� false - ���� ������������ �� ������� ��������. �� ��������� - TRUE
                "zoomOpacity" : false,	// ��������� ������������ �������� �� ����� �������� (�� ��������� false)
                "zoomSpeedIn" : 1000,	// �������� �������� � �� ��� ���������� ���� (�� ��������� 0)
                "zoomSpeedOut" : 1000,	// �������� �������� � �� ��� ���������� ���� (�� ��������� 0)
                "zoomSpeedChange" : 1000, // �������� �������� � �� ��� ����� ���� (�� ��������� 0)
                "frameWidth" : 1000,	 // ������ ����, px (425px - �� ���������)
                //"frameHeight" : 600, // ������ ����, px(355px - �� ���������)
                "overlayShow" : true, // ���� true �������� �������� ��� ����������� �����. (�� ��������� true). ���� �������� � jquery.fancybox.css - div#fancy_overlay 
                "overlayOpacity" : 0.8,	 // ������������ ��������� 	(0.3 �� ���������)
                "hideOnContentClick" :true, // ���� TRUE  ��������� ���� �� ����� �� ����� ��� ����� (����� ��������� ���������). ����������� TRUE		
                "centerOnScroll" : false // ���� TRUE ���� ������������ �� ������, ����� ������������ ������������ ��������		
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
 * ������������� ����-���� ������� ��������.
 * 
 * @param array $data  <p>��������� (��� �� ������������):<br>
 * <b>caption2</b>,<b>title</b>  - ��������� ��������<br>
 * <b>keywords</b>  - �������� �����<br>
 * <b>description</b>  - �������� �������� (description)<br>
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
 * ��������� �������� � ������ �� �������� �� �������,<br>
 * � ������� ����� ������������ ������� ���� #������_�������#<br>
 * <br>
 * <b>������:</b> #id# ���������� �� �������� $data['id']
 * 
 * @param string $url  <p>������-������</p>
 * @param array $data  <p>����� ��� ������</p>
 * @return string
 */
function ReplaceURL($url, $data) {
    foreach ($data as $num => $value)
        if (!is_array($value))
            $url = str_replace("#" . $num . "#", $value, $url);
	//@todo �������� ��������� ��������, ����� ���� #����������|�����/��� � �������#
    return $url;
}

/**
 * ������������� ���������
 * 
 * @param string $name  <p>��� ���������</p>
 * @param string $value  <p>� ��������</p>
 */
function SetOptions($name, $value) {
    global $_OPTIONS;
    $_OPTIONS[$name] = $value;
}

/**
 * ���������� �������� �� ������ $url � ������� header'� (�, ��������, JS).
 * 
 * @param string $url  <p>����� ���������</p>
 * @param bool $js  <p>��� �� �������� ��� ��������� �������� �� JavaScript</p>
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
 * ���������� ����� �������
 * 
 * @return string
 */
function GetSiteURL() {
    return "http://" . $_SERVER['HTTP_HOST'];
}

/**
 * ��������� ������
 * 
 * @param int $number  <p>����� �������� � ������</p>
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
    // ���������� ������
    $pass = "";
    for ($i = 0; $i < $number; $i++) {
        // ��������� ��������� ������ �������
        $index = rand(0, count($arr) - 1);
        $pass .= $arr[$index];
    }
    return $pass;
}

/**
 * ���������� ����� ����� � ����������� �� ����������� $count.
 * 
 * @param int $count <p>����� ���������</p>
 * @param array $forms <p>����� �����, ������ {�����,�����,������}</p>
 */
function GetWordForms($count,$forms)
{
    if ($count==1) return $forms[0];
    elseif (($count>1) && ($count<=4)) return $forms[1];
    else return $forms[2];
};

/**
 * ��������� MIME ��� �����.
 * 
 * @param string $file_name <p>���� � �����.</p>
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
 * ���������� ������ ����� �������� �� � id ��� name
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