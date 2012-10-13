<?php
/**
 * Класс для обработки графичских файлов.
 * 
 */
class HolyImg {

    private $path;          //изначальный путь к файлы
    private $url;
    private $add_path;      //постфикс нового пути
    private $complete_url;  //полная ссылка к готовому файлу
    private $complete_path;
    private $already;       //
    private $width_old;     //ширина изначальной картинки
    private $height_old;    //высота изначальной
    private $width;         //ширина картинки
    private $height;        //высота картинки
    private $picture;       //объект картинки
    private $action_list;   //список действий
    private $file_type;     //тип файла
    private $water_pic;
    private $water_path;
    private $water_params;

/**
 * Добавляет на картинку водяной знак
 * 
 * @param array $img <p>путь до картинки от корня сайта (без DOCUMENT_ROOT)</p>
 * @param array $params <p>параметры:<br>
 * transparent - прозрачность (по-умолчанию 50%)
 * position_v - позиция картинки по вертикали (center [по умолчанию], top, bottom)
 * position_h - позиция картинки по горизонтали (center [по умолчанию], right, left)
 * </p>
 * 
 * @return HolyImg
 */
    function AddWaterMark($img, $params = array()) {
        unset($this->water_path);
        unset($this->water_pic);

        if (!isset($params['position_v']))
            $params['position_v'] = "center";
        if (!isset($params['position_h']))
            $params['position_h'] = "center";
        if (!isset($params['transparent']))
            $params['transparent'] = 50;

        $this->water_params = $params;

        if (is_a($img, "HolyImg")) {
            //@fix добавить поведение
            //если передали объект
        } else {
            //если передали путь
            $this->add_path.=md5($img);
            $this->url_add.="&water=" . $img;
            $this->water_path = $_SERVER['DOCUMENT_ROOT'] . $img;
        };

        $this->add_path.="_water_" . $params['position_h'] . "_" . $params['position_v'] . "_" . $params['transparent'];

        $this->action_list[] = "WaterMarkGO";
        return $this;
    }

    private function WaterMarkGO() {
        $parts = pathinfo($this->water_path);
        $file_type = strtolower($parts['extension']);
        $file_name = $this->water_path;

        switch ($file_type) {
            case "jpeg": case "jpg": $this->water_pic = imagecreatefromjpeg($file_name);
                break;
            case "gif": $this->water_pic = imagecreatefromgif($file_name);
                break;
            case "png": $this->water_pic = imagecreatefrompng($file_name);
                break;
            case "bmp": $this->water_pic = imagecreatefrombmp($file_name);
                break;
        };

        if (($this->width == 0) || ($this->height == 0)) {
            $this->width = imagesx($this->picture);
            $this->height = imagesy($this->picture);
        };
        //@todo вынести в свойства класса

        $water_width = imagesx($this->water_pic);
        $water_height = imagesy($this->water_pic);
        if (($water_width > $this->width) || ($water_height > $water_height)) {
            $water_height_old = $water_height;
            $water_width_old = $water_width;
            if ($water_width > $water_height) {
                $water_width = $this->width;
                $water_height = $water_height_old * $water_width / $water_width_old;
            } else {
                $water_height = $this->height;
                $water_width = $water_width_old * $water_height / $water_height_old;
            }
            $watermark2 = @imagecreatetruecolor($water_width, $water_height);

            imagecopyresampled($watermark2, $this->water_pic, 0, 0, 0, 0, $water_width, $water_height, $water_width_old, $water_height_old);
            $this->water_pic = $watermark2;
        }
        $out = imageCreateTrueColor($this->width, $this->height);

        imageCopyMerge($out, $this->picture, 0, 0, 0, 0, $this->width, $this->height, 100);

        $dst_x = 0;
        $dst_y = 0;

        switch ($this->water_params['position_h']) {
            case "left":
                $dst_x = 0;
                break;
            case "right":
                $dst_x = $this->width - $water_width;
                break;
            default://center
                $dst_x = ($this->width - $water_width) / 2;
        }

        switch ($this->water_params['position_v']) {
            case "top":
                $dst_y = 0;
                break;
            case "bottom":
                $dst_y = $this->height - $water_height;
                break;
            default://center
                $dst_y = ($this->height - $water_height) / 2;
        }

        imageCopyMerge($out, $this->water_pic, $dst_x, $dst_y, 0, 0, $water_width, $water_height, $this->water_params['transparent']);

        $this->picture = $out;
    }

/**
 * Изменяет размер под для выписывание в указанные размеры.
 * 
 * @param array $size <p>размеры: 'height' - новая высота,'width' - новая ширина. Можно указать только один размер - второй будет подсчитан пропорционально. </p>
 * 
 * @return HolyImg
 */
    function Resize($size = Array()) {
        if (!isset($size['height']))
            $size['height'] = 0;
        if (!isset($size['width']))
            $size['width'] = 0;
        $size['height'] = intval($size['height']);
        $size['width'] = intval($size['width']);
        if (!$size['height'])
            $size['height'] = 0;
        if (!$size['width'])
            $size['width'] = 0;

        $height = $size['height'];
        $width = $size['width'];

        $this->width_old = $this->width;
        $this->height_old = $this->height;
        $this->width = $width;
        $this->height = $height;
        $this->add_path.=$width . "_" . $height;
        $this->action_list[] = "ResizeGO";
        return $this;
    }

/**
 * Изменяет размер под квадрат, что не влезет - отсекается
 * 
 * @param int $site <p>размер стороны квадрата</p>
 * 
 * @return HolyImg
 */
    function ResizeSquare($size) {
        $size = intval($size);
        if ($size == 0)
            $size = 200;
        $height = $size['height'];
        $width = $size['width'];

        $this->width_old = $this->width;
        $this->height_old = $this->height;
        $this->width = $size;
        $this->height = $size;
        $this->add_path.=$size . "_" . $size . "_sq_mode";
        $this->action_list[] = "ResizeSquareGO";

        $this->url_add.="&square=1";

        return $this;
    }

    private function LoadFile($file_name = "") {
        if ($file_name == "")
            $file_name = $this->path;

        switch ($this->file_type) {
            case "jpeg": case "jpg": $this->picture = imagecreatefromjpeg($file_name);
                break;
            case "gif": $this->picture = imagecreatefromgif($file_name);
                break;
            case "png": $this->picture = imagecreatefrompng($file_name);
                break;
            case "bmp": $this->picture = imagecreatefrombmp($file_name);
                break;
        };

        if (!$this->picture)
            return null;
        return $this;
    }

    private function ResizeSquareGO() {

        if (($this->width_old == 0) || ($this->height_old == 0)) {
            $this->width_old = imagesx($this->picture);
            $this->height_old = imagesy($this->picture);
        }

        $new_width = $this->width;
        $new_height = $this->height;
        //подгоняем к известной ширине и высоте
        $height_old = $this->height_old;
        $width_old = $this->height_old;
        if ($width_old > $height_old)
            $width_old = $height_old;
        if ($height_old > $width_old)
            $height_old = $width_old;

        $this->width = intval($this->width);
        $this->height = intval($this->height);

        $new_img = @imagecreatetruecolor($this->width, $this->height) or die("Cant create image ");

        if ($new_img) {
            $this->picture_old = $this->picture;
            imageAlphaBlending($new_img, false);
            imageSaveAlpha($new_img, true);
            imagecopyresampled($new_img, $this->picture, 0, 0, 0, 0, $this->width, $this->height, $width_old, $height_old);
            $this->picture = $new_img;
        }

        return $this;
    }

    private function ResizeGO() {
        if (($this->width_old == 0) || ($this->height_old == 0)) {
            $this->width_old = imagesx($this->picture);
            $this->height_old = imagesy($this->picture);
        }
        if ($this->width == 0)
            $this->width = $this->width_old;
        if ($this->height == 0)
            $this->height = $this->height_old;
        $new_width = $this->width;
        $new_height = $this->height;

        if ($new_width == 0) {
            //жмем по известной высоте
            $this->width = $this->width_old * $this->height / $this->height_old;
        } elseif ($new_height == 0) {
            //жмем по известной ширине
            $this->height = $this->height_old * $this->width / $this->width_old;
        } else {
            //подгоняем к известной ширине и высоте
            if ($this->height_old > $this->width_old) {
                $this->width = $this->width_old * $this->height / $this->height_old;
                if ($this->width > $this->width) {
                    $this->width = $this->width;
                    $this->height = $this->height_old * $this->width / $this->width_old;
                }else
                    $this->height = $this->height;
            }else {
                $this->height = $this->height_old * $this->width / $this->width_old;
                if ($this->height > $this->height) {
                    $this->height = $this->height;
                    $this->width = $this->width_old * $this->height / $this->height_old;
                }else
                    $this->width = $this->width;
            }
        };

        $this->width = intval($this->width);
        $this->height = intval($this->height);
        $new_img = @imagecreatetruecolor($this->width, $this->height);

        if ($new_img) {
            $this->picture_old = $this->picture;
            imageAlphaBlending($new_img, false);
            imageSaveAlpha($new_img, true);
            imagecopyresampled($new_img, $this->picture, 0, 0, 0, 0, $this->width, $this->height, $this->width_old, $this->height_old);
            $this->picture = $new_img;
        }
        return $this;
    }

/**
 * Конструктор.
 * 
 * @param strign $fname <p>имя файла от корня сайта (DOCUMENT_ROOT подставится самостоятельно)</p>
 * 
 * @return HolyImg
 */
    function HolyImg($fname) {
        $this->url = $fname;
        if (strpos($fname, 'http') === false)
            if (strpos($fname, $_SERVER['DOCUMENT_ROOT']) === FALSE)
                $fname = $_SERVER['DOCUMENT_ROOT'] . $fname;

        if (!file_exists($fname))
            return null;

        $this->path = $fname;
        $this->already = false;
        $this->url_add = "";
        $this->add_path = "";
        $action_list = array();

        return $this;
    }

    private function IsNeedToGO() {
        $new_path = MD5($this->path . $this->add_path);
        if ($this->path) {
            $parts = pathinfo($this->path);
            $this->file_type = strtolower($parts['extension']);
            $fold1 = substr($new_path, 0, 2);
            $fold2 = substr($new_path, 2, 2);
            $parts['filename'] = $fold1 . "/" . $fold2 . "/" . $parts['filename'];

            $this->complete_url = "/upload/resize_cache/" . $parts['filename'] . "_" . $this->add_path . ".png";
            $this->complete_path = $_SERVER['DOCUMENT_ROOT'] . $this->complete_url;

            if (file_exists($this->complete_path)) {
                $this->already = true;
                $this->file_type = "png";
                return false;
            };

            if (!is_array($this->action_list))
                $this->action_list = array();

            if (count($this->action_list) == 0) {
                $this->already = true;
                $this->complete_url = $this->url;
                $this->complete_path = $this->path;
                return false;
            };
        };

        return true;
    }

/**
 * Выполняет все выбранные преобразования.
 * 
 * @return HolyImg
 */
    private function GO() {
        if ($this->IsNeedToGO()) {
            $this->LoadFile();

            if (is_array($this->action_list))
                if (count($this->action_list) > 0)
                    foreach ($this->action_list as $action)
                        $this->$action();
        };
        $this->action_list = array();

        return $this;
    }

/**
 * Возвращает ссылку на картинки.
 * 
 * @param bool $now <p>[не обязательное] При false (по умолчанию) картинка генерится асинхронно (если кэш не создан - дается ссылка на файл-посредник resize.php, а прямая ссылка будет выдаваться при следующем выводе картинки), при true - картинка снчала сгенерится, сохранится в кэш, и лишь потом функция завершиться и выдаст ссылку.</p>
 */
    function GetURL($now = false) {
        if ($this->IsNeedToGO()) {
            if ($now) {
                $this->Save();
                return $this->complete_url;
            } else {
                if ($this->path != "")
                    return "/engine/resize.php?file_name=" . $this->path . "&w=" . $this->width . "&h=" . $this->height . $this->url_add;
                else
                    return "";
            };
        }
        else
            return $this->complete_url;
    }

/**
 * Выводит ссылку на текущую картинки.
 * 
 * @param array $params <p>Параметры (все не обязательные)<BR>
 * draw_before - выводить ДО ссылки<br>
 * draw_after - выводить ПОСЛЕ ссылки<br>
 * draw_inner - выводить ВНУТРИ ссылки (стили, rel и.т.д)<br>
 * now - обрабатывать картинку синхронно<br>
 * </p>
 */
    function DrawHref($params = array()) {
        if (!isset($params['draw_before']))
            $params['draw_before'] = "";
        if (!isset($params['draw_after']))
            $params['draw_after'] = "";
        if (!isset($params['draw_inner']))
            $params['draw_inner'] = "";
        if (!isset($params['now']))
            $params['now'] = false;
        ?><?= $params['draw_before'] ?><img src="<? echo $this->GetURL($params['now']) ?>" <? echo $params['draw_inner'] ?>><?= $params['draw_after'] ?><?
    }

    protected function CreateDirs() {
        $full_path = $_SERVER['DOCUMENT_ROOT'] . "/upload/resize_cache/";
        if (!file_exists($full_path))
            mkdir($full_path);
        $new_path = explode("resize_cache", $this->complete_path);
        $new_path = $new_path[1];
        $new_path = explode("/", $new_path);
        unset($new_path[count($new_path) - 1]);
        $new_path = RemoveEmptyFromArray($new_path, false);

        foreach ($new_path as $add_to_path) {
            $full_path.=$add_to_path . "/";
            if (!file_exists($full_path))
                mkdir($full_path);
        }
        return $this;
    }

/**
 * Сохраняет файл.
 * 
 * @return HolyImg
 */
    function Save() {
        $this->GO();
        if (!$this->already) {
            $this->CreateDirs();
            imagepng($this->picture, $this->complete_path);
            $this->already = true;
        };
        return $this;
    }

/**
 * Выводить картинку на вывод, устанавливая соответствующий заголовок.
 * 
 * @return HolyImg
 */
    function Draw() {
        $this->GO();
        if ($this->already) {
            $this->LoadFile($this->complete_path);
            header("Content-type: image/png");
            imagepng($this->picture);
        } else
        if (!$this->picture)
            echo "ups";else {
            header("Content-type: image/png");
            imagepng($this->picture);
        }

        return $this;
    }

}

function ImageCreateFromBMP($filename) {
    if (!$f1 = fopen($filename, "rb"))
        return FALSE;
    $FILE = unpack("vfile_type/Vfile_size/Vreserved/Vbitmap_offset", fread($f1, 14));
    if ($FILE['file_type'] != 19778)
        return FALSE;
    $BMP = unpack('Vheader_size/Vwidth/Vheight/vplanes/vbits_per_pixel' .
            '/Vcompression/Vsize_bitmap/Vhoriz_resolution' .
            '/Vvert_resolution/Vcolors_used/Vcolors_important', fread($f1, 40));
    $BMP['colors'] = pow(2, $BMP['bits_per_pixel']);
    if ($BMP['size_bitmap'] == 0)
        $BMP['size_bitmap'] = $FILE['file_size'] - $FILE['bitmap_offset'];
    $BMP['bytes_per_pixel'] = $BMP['bits_per_pixel'] / 8;
    $BMP['bytes_per_pixel2'] = ceil($BMP['bytes_per_pixel']);
    $BMP['decal'] = ($BMP['width'] * $BMP['bytes_per_pixel'] / 4);
    $BMP['decal'] -= floor($BMP['width'] * $BMP['bytes_per_pixel'] / 4);
    $BMP['decal'] = 4 - (4 * $BMP['decal']);
    if ($BMP['decal'] == 4)
        $BMP['decal'] = 0;
    $PALETTE = array();
    if ($BMP['colors'] < 16777216) {
        $PALETTE = unpack('V' . $BMP['colors'], fread($f1, $BMP['colors'] * 4));
    }
    $IMG = fread($f1, $BMP['size_bitmap']);
    $VIDE = chr(0);
    $res = imagecreatetruecolor($BMP['width'], $BMP['height']);
    $P = 0;
    $Y = $BMP['height'] - 1;
    while ($Y >= 0) {
        $X = 0;
        while ($X < $BMP['width']) {
            if ($BMP['bits_per_pixel'] == 24)
                $COLOR = unpack("V", substr($IMG, $P, 3) . $VIDE);
            elseif ($BMP['bits_per_pixel'] == 16) {
                $COLOR = unpack("n", substr($IMG, $P, 2));
                $COLOR[1] = $PALETTE[$COLOR[1] + 1];
            } elseif ($BMP['bits_per_pixel'] == 8) {
                $COLOR = unpack("n", $VIDE . substr($IMG, $P, 1));
                $COLOR[1] = $PALETTE[$COLOR[1] + 1];
            } elseif ($BMP['bits_per_pixel'] == 4) {
                $COLOR = unpack("n", $VIDE . substr($IMG, floor($P), 1));
                if (($P * 2) % 2 == 0)
                    $COLOR[1] = ($COLOR[1] >> 4); else
                    $COLOR[1] = ($COLOR[1] & 0x0F);
                $COLOR[1] = $PALETTE[$COLOR[1] + 1];
            }
            elseif ($BMP['bits_per_pixel'] == 1) {
                $COLOR = unpack("n", $VIDE . substr($IMG, floor($P), 1));
                if (($P * 8) % 8 == 0)
                    $COLOR[1] = $COLOR[1] >> 7;
                elseif (($P * 8) % 8 == 1)
                    $COLOR[1] = ($COLOR[1] & 0x40) >> 6;
                elseif (($P * 8) % 8 == 2)
                    $COLOR[1] = ($COLOR[1] & 0x20) >> 5;
                elseif (($P * 8) % 8 == 3)
                    $COLOR[1] = ($COLOR[1] & 0x10) >> 4;
                elseif (($P * 8) % 8 == 4)
                    $COLOR[1] = ($COLOR[1] & 0x8) >> 3;
                elseif (($P * 8) % 8 == 5)
                    $COLOR[1] = ($COLOR[1] & 0x4) >> 2;
                elseif (($P * 8) % 8 == 6)
                    $COLOR[1] = ($COLOR[1] & 0x2) >> 1;
                elseif (($P * 8) % 8 == 7)
                    $COLOR[1] = ($COLOR[1] & 0x1);
                $COLOR[1] = $PALETTE[$COLOR[1] + 1];
            }
            else
                return FALSE;
            imagesetpixel($res, $X, $Y, $COLOR[1]);
            $X++;
            $P += $BMP['bytes_per_pixel'];
        }
        $Y--;
        $P+=$BMP['decal'];
    }
    fclose($f1);
    return $res;
}

;
?>