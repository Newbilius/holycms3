<?

require_once($_SERVER['DOCUMENT_ROOT'] . "/engine/engine.php");
global $H_USER;
$user_info_holy = $H_USER->GetInfo();
if ($H_USER->GetID()) {
    
if (isset($_GET['delete_path'])) {
    $full_name = $_SERVER['DOCUMENT_ROOT'] . $_GET['delete_path'];
    
    echo $full_name;
    
    if (file_exists($full_name)) {
        unlink($full_name);
    }
    else
        echo "как это?";
}
};
?>