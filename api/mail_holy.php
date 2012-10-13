<?php

if (!defined('HCMS'))
    die();

/**
 * ѕростейша€ обертка дл€ функции mail(), посылает HTML-письмо
 * 
 * @param string/array $from_mail  <p>адрес отправител€ или массив (им€, обратный адрес)</p>
 * @param string $to  <p>адрес получател€</p>
 * @param string $subject  <p>тема письма</p>
 * @param string $message  <p>текст письма</p>
 * @param string $send_charset  <p>[не об€зательное] кодировка письма, по-умолчанию "windows-1251"</p>
 * 
 * @return HolyValidator
 */
function HolyMail($from_mail, $to, $subject, $message, $send_charset = "windows-1251") {
    if (!is_array($from_mail)) {
        $tmp = $from_mail;
        unset($from_mail);
        $from_mail[0] = $tmp;
        $from_mail[1] = $tmp;
    };

    $subject = '=?' . $send_charset . '?B?' . base64_encode(($subject)) . '?=';
    $headers = "Content-type: text/html; charset=" . $send_charset . " \r\n";
    $headers .= "From: " . $from_mail[0] . " <" . $from_mail[1] . ">\r\n";


    $headers = str_replace("\r\n", "\n", $headers);
    $subject = str_replace("\r\n", "\n", $subject);
    $message = str_replace("\r\n", "\n", $message);

    mail($to, $subject, $message, $headers);
}

;
?>