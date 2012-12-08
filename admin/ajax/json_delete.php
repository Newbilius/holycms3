<?

require_once(realpath(str_replace("\\","/",dirname(dirname(dirname(__FILE__)))."/engine.php")));
global $H_USER;
$user_info_holy = $H_USER->GetInfo();
if ($H_USER->GetID()) {
    
if (isset($_GET['delete_path'])) {
    $full_name = FOLDER_ROOT . $_GET['delete_path'];
    
    echo $full_name;
    
    if (file_exists($full_name)) {
        unlink($full_name);
    }
    else
        echo "как это?";
}
};
?>