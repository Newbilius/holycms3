<?php

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

;

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