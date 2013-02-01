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

/**
 * Обертка для класса PHPMailer, посылает HTML-письмо
 * Настраивется с помощью глобального массива
 * global $mail_smtp;
$mail_smtp = array(
    "host" => "smtp.yandex.ru", //smtp сервер
    "debug" => 0,                   //отображение информации дебаггера (0 - нет вообще)
    "auth" => true,                 //сервер требует авторизации
    "port" => 25,                    //порт (по-умолчанию - 25)
    "username" => "USER",//имя пользователя на сервере
    "password" => "PASS",//пароль
);
 * 
 * @param string/array $from_mail  <p>адрес отправителя или массив (имя, обратный адрес)</p>
 * @param string $to  <p>адрес получателя</p>
 * @param string $subject  <p>тема письма</p>
 * @param string $message  <p>текст письма</p>
 * @param string $send_charset  <p>[не обязательное] кодировка письма, по-умолчанию "windows-1251"</p>
 * @param array $attach <p>массив путей приложенных файлов</p>
 * 
 * @return HolyValidator
 */
function HolyMailSMTP($from_mail, $to, $subject, $message,$send_charset = "windows-1251",$attach=false) {
    global $mail_smtp;
    if (!is_array($from_mail)) {
        $tmp = $from_mail;
        unset($from_mail);
        $from_mail[0] = $tmp;
        $from_mail[1] = $tmp;
    };

    $mail = new PHPMailer(true);
    $mail->IsSMTP();
    try {
        $mail->Host = $mail_smtp['host'];
        $mail->SMTPDebug = $mail_smtp['debug'];
        $mail->SMTPAuth = $mail_smtp['auth'];
        $mail->Port = $mail_smtp['port'];
        $mail->Username = $mail_smtp['username'];
        $mail->Password = $mail_smtp['password'];
        $mail->CharSet = $send_charset;
        $mail->AddAddress($to);                //кому письмо
        $mail->SetFrom($from_mail[0], $from_mail[1]); //от кого (желательно указывать свой реальный e-mail на используемом SMTP сервере
        $mail->AddReplyTo($from_mail[0], $from_mail[1]);
        $mail->Subject = $subject;
        $mail->MsgHTML($message);
        if ($attach)
            $mail->AddAttachment($attach);
        $mail->Send();
        return true;
    } catch (phpmailerException $e) {
        return $e->errorMessage();
    } catch (Exception $e) {
        return $e->getMessage();
    }
}
?>