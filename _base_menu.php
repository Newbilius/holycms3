<?
//избранные блоки
$_menu_best['before'] = array();
$_menu_best['after'] = array();

$_standard_element_fields = Array(
    array(
        "caption" => "Дата",
        "code" => "sdate",
        "type" => "ddatetime",
        "multi" => false,
        "folder" => false,
        "not_in_list" => false,
        "meta" => false,
    ),
    array(
        "caption" => "Краткий текст",
        "code" => "preview_text",
        "type" => "wysiwyg_text",
        "multi" => false,
        "folder" => false,
        "not_in_list" => true,
        "meta" => false,
    ),
    array(
        "caption" => "Детальный текст",
        "code" => "detail_text",
        "type" => "wysiwyg_html",
        "multi" => false,
        "folder" => false,
        "not_in_list" => true,
        "meta" => false,
    ),
    array(
        "caption" => "Цена",
        "code" => "cost",
        "type" => "double_integ",
        "multi" => false,
        "folder" => false,
        "not_in_list" => false,
        "meta" => false,
    ),
    array(
        "caption" => "Фото",
        "code" => "foto",
        "type" => "image",
        "multi" => false,
        "folder" => false,
        "not_in_list" => false,
        "meta" => false,
    ),
    array(
        "caption" => "Дополнительные фото",
        "code" => "foto_multi",
        "type" => "image",
        "multi" => true,
        "folder" => false,
        "not_in_list" => true,
        "meta" => false,
    ),
    array(
        "caption" => "Спец-предложение",
        "code" => "spec1",
        "type" => "checkbox",
        "multi" => false,
        "folder" => false,
        "not_in_list" => false,
        "meta" => false,
    ),
);

$_top_menu['utilits'] = array("caption" => "Утилиты", "parent" => "");
$_top_menu['options'] = array("caption" => "Настройки", "parent" => "");

$_top_menu['file_manager'] = Array(
    "url" => "/engine/admin/file_manager.php",
    "caption" => "Файловый менеджер",
    "admin_right" => true,
    "parent" => "utilits",
);
$_top_menu['sql_backup'] = Array(
    "url" => "/engine/admin/sql_backup.php",
    "caption" => "Управление бэкапами",
    "admin_right" => false,
    "parent" => "utilits",
);
$_top_menu['journal'] = Array(
    "url" => "/engine/admin/journal.php",
    "caption" => "Журнал административных операций",
    "admin_right" => false,
    "parent" => "utilits",
);
$_top_menu['phpinfo'] = Array(
    "url" => "/engine/admin/php_info.php",
    "caption" => "Информация о PHP",
    "admin_right" => false,
    "parent" => "utilits",
);
$_top_menu['options_simple'] = Array(
    "url" => "/engine/admin/options.php",
    "caption" => "Опции сайта",
    "parent" => "options",
);
$_top_menu['options_seo'] = Array(
    "url" => "/engine/admin/options_seo.php",
    "caption" => "SEO опции сайта",
    "parent" => "options",
);
$_top_menu['options_groups_access'] = Array(
    "url" => "/engine/admin/options_groups_access.php",
    "caption" => "Настройки доступа",
    "admin_right" => true,
    "parent" => "options",
);
$_top_menu['line1'] = Array(
    "caption" => "-",
    "parent" => "options",
);
$_top_menu['clear_cache'] = Array(
    "url" => "/engine/admin/clear_cache.php",
    "caption" => "Очистка кэша",
    "parent" => "options",
);
$_top_menu['line2'] = Array(
    "caption" => "-",
    "parent" => "utilits",
);
$_top_menu['export_structure_xml'] = Array(
    "url" => "/engine/admin/export_structure_xml.php",
    "caption" => "Экспорт в XML структуры",
    "parent" => "utilits",
);
$_top_menu['import_structure_xml'] = Array(
    "url" => "/engine/admin/import_structure_xml.php",
    "caption" => "Импорт структуры из XML",
    "parent" => "utilits",
);
$_top_menu['line3'] = Array(
    "caption" => "-",
    "parent" => "utilits",
);
$_top_menu['export_data_sql'] = Array(
    "url" => "/engine/admin/export_data_sql.php",
    "caption" => "Экспорт данных в SQL",
    "parent" => "utilits",
);
$_top_menu['import_data_sql'] = Array(
    "url" => "/engine/admin/import_data_sql.php",
    "caption" => "Импорт структуры из SQL",
    "parent" => "utilits",
);
//меню на первой левой закладке
$_menu1['before'] = array();
$_menu1['after'] = array(
);

//меню на второй левой закладке
$_menu2['before'] = array();
$_menu2['after'] = array(
    Array("/engine/admin/types.php", "Типы полей"),
);
?>