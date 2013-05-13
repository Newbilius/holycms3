<?php
class Component_auth_user_form_social extends Component {

    protected function GetDefaults() {
        return array(
            "template" => "default",
            "module_url" => false,
            "cabinet_url" => "/cabinet/",
        );
    }

    protected function PrepareCache() {
        $this->params['cache'] = false;
        return false;
    }

    protected function GetParamsValidators() {
        return array();
    }

    protected function Action() {
        $result = array();
        if ($this->params['module_url'] == false)
            SystemAlertFatal("Необходимо задать путь для ajax-запроса при регистрации через социальные сети!");
        else {
            $result['reg_url'] = $this->params['module_url'];
            $result['ajax_url'] = urlencode("http://".$_SERVER['HTTP_HOST'].$this->params['module_url']);            
        }
        $result['reg_ok'] = false;
        if (isset($_POST['token'])){
            if (!isset($_POST['error'])) {
                $result['reg_ok'] = true;
            }
        }
        if (isset($_POST['error'])) {
            $error[] = $_POST['error'];
            $result['reg_ok'] = true;
        }
        if ($result['reg_ok']) {
            $user_data_src = file_get_contents('http://ulogin.ru/token.php?token=' . $_POST['token'] . '&host=' . $_SERVER['HTTP_HOST']);
            $user_data = json_decode($user_data_src, true);
            if (isset($user_data['error'])) 
                if ($user_data['error']){
                $error[] = $user_data['error'];
            }
        };
        
        $user = HolyUser::getInstance();
         
        if ($user->IsAuth()) {
            $result['list_of_socials']=$user->GetInfo();
            if (isset($_GET['delete_social'])){
                $soc_array=$result['list_of_socials']['socials'];
                foreach ($soc_array as $_soc_id=>$_soc){
                    if ($_soc==$_GET['delete_social']){
                        unset($soc_array[$_soc_id]);
                    }
                }
                $user->Update(Array(
                    'socials'=>$soc_array
                ));
                $result['redirect_url']=$this->params['cabinet_url'];
            }
        };
        
        if (isset($error)) {
            $result['errors'] = $error;
        } else {
            if ($result['reg_ok']) {
                if (!$user->IsAuth()) {
                    //регистрация/авторизация
                    $data=$user->GetUser(Array(
                        Array("socials","LIKE","%".$user_data['identity']."%"),
                    ));
                    $data2=$user->GetUser(Array(
                        Array("email","=",$user_data['email']),
                    ));
                    if (isset($data2['id']))
                        if (!isset($data['id']))
                        if ($data2['id']){
                            $error[]="пользователь с таким email'ом уже зарегистрирован!";
                            $result['errors'] = $error;
                        }
                        if (!isset($error)){
                    if ($data==NULL){
                        $result['message']="регистрация нового пользователя";
                        $new_pass = PasswordGenerate(8);
                        $data_add=array();
                        $data_add['email']=$user_data['email'];
                        $data_add['socials']=$user_data['identity'];
                        if (isset($user_data['first_name'])){
                            $data_add['fio']=$user_data['first_name'];
                        };
                        if (isset($user_data['last_name'])) {
                            if ($data_add['fio'] == "")
                                $data_add['fio'] = $user_data['last_name'];
                            else
                                $data_add['fio'] = $data_add['fio']." " . $user_data['last_name'];
                        };
                        $data_add['caption']=$user_data['email'];
                        $user->AddUser($user_data['email'], $new_pass, $data_add);
                        $data = $user->GetUser(Array(
                            Array("email", "=", $user_data['email']),
                            Array("socials", "LIKE", "%" . $user_data['identity'] . "%"),
                                ));
                        $user->AuthByID($data['id']);
                        $result['redirect_url']=$this->params['cabinet_url'];
                    }else
                    {
                        $user->AuthByID($data['id']);
                        $result['message']="авторизация пользователя существующего";
                        $result['redirect_url']=$this->params['cabinet_url'];
                    }
                        };
                    
                }else{
                    $info = $user->GetInfo();
                    $_socials_tmp=$info["socials"];
                    foreach ($_socials_tmp as $_social){
                        $_socials[$_social]=$_social;
                    };
                    $_socials[$user_data['identity']]=$user_data['identity'];
                    $user->Update(Array("socials"=>$_socials));
                    $result['message']="привязка соц. сети к аккаунту завершено";
                    $result['redirect_url']=$this->params['cabinet_url'];
                }
            }
        }
        return $result;
    }

}

?>
