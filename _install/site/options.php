<?php
define("FOLDER_ROOT", $_SERVER["DOCUMENT_ROOT"]);
define("FOLDER_UPLOAD", $_SERVER["DOCUMENT_ROOT"] . "/upload/");
define("URI_UPLOAD", "/upload/");
define("FOLDER_IMAGE", $_SERVER["DOCUMENT_ROOT"] . "/upload/pics/");
define("URI_IMAGE", "/upload/pics/");
define("FOLDER_FILES", $_SERVER["DOCUMENT_ROOT"] . "/upload/files/");
define("URI_FILES", "/upload/files/");
define("FOLDER_SITE", $_SERVER["DOCUMENT_ROOT"] . "/site/");
define("FOLDER_ADMIN", $_SERVER["DOCUMENT_ROOT"] . "/engine/admin/");
define("URI_ADMIN", "/engine/admin/");
define("URI_ENGINE", "/engine/");
define("FOLDER_ENGINE", $_SERVER["DOCUMENT_ROOT"] . "/engine/");

/*
//если хотите прервать дальнейшее выполнение функции обновление/вставки/удаления/следующей функции
//используйте return NULL;

//порядов вызова
//Before для блока -> Before общий -> действие ->After для блока ->After общий

function DBlockBeforeUpdate($_before_data, $values){
    return $values;
}
function DBlockBeforeUpdate_код_блока($_before_data, $values){
    return $values;
}
function DBlockAfterUpdate($_before_data, $values){
    return $values;
}
function DBlockAfterUpdate_код_блока($_before_data, $values){
    return $values;
}

function DBlockBeforeDelete($_before_data){
    return $_before_data
}
function DBlockBeforeDelete_код_блока($_before_data){
    return $_before_data
}
function DBlockAfterDelete($_before_data){
    return $_before_data
}
function DBlockAfterDelete_код_блока($_before_data){
    return $_before_data
}

function DBlockBeforeAdd($values){
    return $values;
}
function DBlockBeforeAdd_(код_блока$values){
    return $values;
}
function DBlockAfterAdd($values){
    return $values;
}
function DBlockAfterAdd_код_блока($values){
    return $values;
}
*/
?>