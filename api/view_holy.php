<?

/**
 * Класс для работы с отображениями.
 */
class View {
    
    protected $params;
    protected $full_path;
    
    static public function Factory($path){
        $view=new View($path);
        return $view;
    }
    
    public function View($path){
        $this->params=array();
        if (file_exists(FOLDER_SITE."views/".$path.".php")){
            $this->full_path=FOLDER_SITE."views/".$path.".php";
        }elseif (file_exists(FOLDER_ENGINE."views/".$path.".php")){
            $this->full_path=FOLDER_ENGINE."views/".$path.".php";
        };
        return $this;
    }
    
    public function Set($name,$value){
        $this->params[$name]=$value;
        return $this;
    }
    
    public function Get($name){
        if (isset($this->params[$name]))
            return $this->params[$name];
        else
            return null;
    }
    
    public function Draw(){
        extract($this->params);
        include_once($this->full_path);
        return $this;
    }
}
?>