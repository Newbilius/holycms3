<?
//-----------------------------
// Класс для генерации RSS
// Автор: Дмитрий Моисеев
// dima.am@mail.ru
// http://www.siteszone.ru
// 25.06.2011
// ошибки исправлены 28.05.2012
// Допускается использование, модификация и распространение в любом виде при сохранении ссылки на автора.
//-----------------------------

class SmallRSS {

    var $eof = "\r\n";
    var $data;

    /**
     * Конструктор.
     * 
     * @param string $title  <p>название ленты</p>
     * @param string $link  <p>ссылка на сайт ленты</p>
     * @param string $descr  <p>описание ленты</p>
     */
    function SmallRSS($title = "", $link = "", $descr = "") {

        $this->data = '<?xml version="1.0" encoding="utf-8"?>' . $this->eof;
        $this->data = $this->data . '<rss version="2.0" xmlns:dc="http://purl.org/dc/elements/1.1/" xmlns:content="http://purl.org/rss/1.0/modules/content/">' . $this->eof;

        $this->data.='<channel>' . $this->eof;
        $this->data.='<title>' . $title . '</title>' . $this->eof;
        $this->data.='<link>' . $link . '</link>' . $this->eof;
        $this->data.='<description>' . $descr . '</description>' . $this->eof;
    }

    /**
     * Добавляет элемент в ленту
     * 
     * @param string $title  <p>название элемента</p>
     * @param string $link  <p>ссылка на оригинал элемента</p>
     * @param string $des  <p>описание элемента</p>
     * @param string $date  <p>дата элемента (в sql-формате)</p>
     */
    function Add($title, $link = "", $des = "", $date = "") {
        $this->data.='<item>' . $this->eof;

        $this->data.='<title>' . $title . '</title>' . $this->eof;
        $this->data.='<link>' . $link . '</link>' . $this->eof;
        $this->data.='<description>' . $des . '</description>' . $this->eof;
        $this->data.='<pubDate>' . date("r", strtotime($date)) . '</pubDate>' . $this->eof;
        $this->data.='<guid>' . $link . '</guid>' . $this->eof;

        $this->data.='</item>' . $this->eof;
    }

    /**
     * Заканчивает формирование и выводит получившуюся ленту.
     */
    function Complete() {
        $this->data.='</channel>';
        $this->data.='</rss>';
        header('Content-type: application/rss+xml');
        return $this->data;
    }

}

?>