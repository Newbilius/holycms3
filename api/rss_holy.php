<?
//-----------------------------
// ����� ��� ��������� RSS
// �����: ������� �������
// dima.am@mail.ru
// http://www.siteszone.ru
// 25.06.2011
// ������ ���������� 28.05.2012
// ����������� �������������, ����������� � ��������������� � ����� ���� ��� ���������� ������ �� ������.
//-----------------------------

class SmallRSS {

    var $eof = "\r\n";
    var $data;

    /**
     * �����������.
     * 
     * @param string $title  <p>�������� �����</p>
     * @param string $link  <p>������ �� ���� �����</p>
     * @param string $descr  <p>�������� �����</p>
     */
    function SmallRSS($title = "", $link = "", $descr = "") {

        $this->data = '<?xml version="1.0" encoding="windows-1251"?>' . $this->eof;
        $this->data = $this->data . '<rss version="2.0" xmlns:dc="http://purl.org/dc/elements/1.1/" xmlns:content="http://purl.org/rss/1.0/modules/content/">' . $this->eof;

        $this->data.='<channel>' . $this->eof;
        $this->data.='<title>' . $title . '</title>' . $this->eof;
        $this->data.='<link>' . $link . '</link>' . $this->eof;
        $this->data.='<description>' . $descr . '</description>' . $this->eof;
    }

    /**
     * ��������� ������� � �����
     * 
     * @param string $title  <p>�������� ��������</p>
     * @param string $link  <p>������ �� �������� ��������</p>
     * @param string $des  <p>�������� ��������</p>
     * @param string $date  <p>���� �������� (� sql-�������)</p>
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
     * ����������� ������������ � ������� ������������ �����.
     */
    function Complete() {
        $this->data.='</channel>';
        $this->data.='</rss>';
        header('Content-type: application/rss+xml');
        return $this->data;
    }

}

?>