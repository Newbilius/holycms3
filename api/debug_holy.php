<?php

function PrePrint($dat = "") {
    if (isset($dat)) {
        echo "<pre>";

        print_r($dat);

        echo "</pre>";
    };
}

;

function pre_print($text) {
    preprint($text);
}

/**
 * ���������� �������� � debug-������� �� �������
 * 
 * @param string $name  <p>��� ��������</p>
 */
function DebugADD($name) {
    global $_DEBUG;
    if (!isset($_DEBUG[$name]))
        $_DEBUG[$name] = 0;
    $_DEBUG[$name]++;
}

;

/**
 * ��������� �������� � debug ������, ������������ ���������� �������.
 * 
 * @param string $name  <p>��� ��������</p>
 * @param string $value  <p>��������</p>
 */
function DebugAddValue($name, $value) {
    global $_DEBUG;
    DebugADD($name . " (�������)");
    $_DEBUG[$name][] = $value;
}

;

/**
 * ������� � ���-���� ����� � ������� �����.
 * 
 * @param string $text  <p>�����</p>
 */
function AddToLog($text) {
    global $_OPTIONS;
    global $_log_name;
    if (isset($_OPTIONS['log']))
        if ($_OPTIONS['log']) {
            $handle = fopen($_log_name, 'a');
            $text = date(DATE_RFC1036) . "\t" . $text . "\r\n";
            fwrite($handle, $text);
            fclose($handle);
        };
}

;

function AddToJournal($data) {
    global $H_USER;
    $user_ifno_holy = $H_USER->GetInfo();
    $data['user_id'] = $H_USER->GetID();
    $data['date_time'] = Array(0 => date("Y-m-d"), 1 => date("H"), 2 => date("i"), 3 => date("s"));
    $element = new DBlockElement('journal');
//$element->sql->debug=true;
    $element->Add($data);
}

;

function JournalAdd($data) {
    $data['action'] = "add";
    AddToJournal($data);
}

;

function JournalDelete($data) {
    $data['action'] = "delete";
    AddToJournal($data);
}

;

function JournalUpdate($data) {
    $data['action'] = "update";
    AddToJournal($data);
}

;

/**
 * ������� ��������� �� ������ � ��������� ����������.
 * 
 * @param string $text  <p>����� ������</p>
 */
function SystemAlert($text) {
    ?>
    <div style="padding:3px;border:2px solid red;color:red;margin:5px;text-align:center;">
        <?= $text ?>
    </div>
    <?
}
?>