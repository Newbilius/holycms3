<?php

if (!defined('HCMS'))
    die();

/**
 * Простейшая обертка для функции mail(), посылает HTML-письмо
 * 
 * @param string/array $from_mail  <p>адрес отправителя или массив (имя, обратный адрес)</p>
 * @param string $to  <p>адрес получателя</p>
 * @param string $subject  <p>тема письма</p>
 * @param string $message  <p>текст письма</p>
 * @param string $send_charset  <p>[не обязательное] кодировка письма, по-умолчанию "utf-8"</p>
 * 
 * @return HolyValidator
 */
function HolyMail($from_mail, $to, $subject, $message, $send_charset = "utf-8") {
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