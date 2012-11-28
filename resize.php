<?PHP
require_once("engine.php");
$pic= new HolyImg($_GET['file_name']);


if (!isset($_GET['w']))
    $_GET['w']=0;
$_GET['w']=intval($_GET['w']);
if (!isset($_GET['h']))
    $_GET['h']=0;
$_GET['h']=intval($_GET['h']);

if (($_GET['w']!=0) || ($_GET['h']!=0))
{
    
if (isset($_GET['square']))
    $pic->ResizeSquare($_GET['w']);
else
    $pic->Resize(Array(
        "width"=>$_GET['w'],
        "height"=>$_GET['h'],
    ));
};

if (isset($_GET['water']))
    {
        $params=array();
        if (isset($_GET["transparent"]))
            $params["transparent"]=intval($_GET["transparent"]);
        if (isset($_GET["position_h"]))
            $params["position_h"]=$_GET["position_h"];
        if (isset($_GET["position_v"]))
            $params["position_v"]=$_GET["position_v"];
        $pic->AddWaterMark($_GET['water'],$params);
    }
    

$pic->Save();
$pic->Draw();
?>