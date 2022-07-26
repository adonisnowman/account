<?php

use Phalcon\Http\Response;


class IndexController extends BaseController
{
    public static $arrContextOptions = [];

    public function initialize()
    {
        $some_name = session_name("some_name");
        session_set_cookie_params(0, '/', $_SERVER['HTTP_HOST']);
        session_start();

        self::$arrContextOptions = array(
            "ssl" => array(
                "verify_peer" => false,
                "verify_peer_name" => false,
            ),
        );
    }
    public function mongoAction(){

        $data['UserName'] = "adonis";
        
        $Mongo = new MongoAdonis($this->MongoDB);
        
        $Mongo::insert("admin.Test",$data);
        $request = $Mongo::find("admin.Test",$data);

        var_dump($request);
        
    }

    public function infoAction()
    {
        $Return = _Views::Init();
        $Return['ReDirect'] = "signEmail";
        $Return['Token'] = Tools::getToken();
        // echo _Views::RedirectAdmin($Return);
        // exit;
        Tools::emailSend("adonisnowman@gmail.com", "signEmail", _Views::RedirectAdmin($Return));
        if (!empty($_GET['token'])) Tools::checkToken($_GET['token']);
    }

    public function indexAction()
    {

        if (!empty($_GET['Token'])) Tools::checkToken($_GET['Token']);


        //預設模板讀取
        $Return = _Views::Init();
        if (Tools::getIp() == Tools::ServerIp() || in_array(Tools::getIp(),  _Accounts::AllowIps())) $Return['header'] = _Views::RedirectAdmin(["ReDirect" => "Home_header"]);

        else $Return['header'] = _Views::RedirectAdmin(["ReDirect" => "User_header"]);

        if (!empty($_SESSION[Tools::getIp()]['ReDirect']))
            $Return['ReDirect'] = $_SESSION[Tools::getIp()]['ReDirect'];
        else if (Tools::getIp() == Tools::ServerIp() || in_array(Tools::getIp(),  _Accounts::AllowIps())) $Return['ReDirect'] = "sign-in";
        else $Return['ReDirect'] = "UserSign";





        //預設 新增修改 模板讀取
        $Return['Input_Nav'] = _Views::RedirectAdmin(["ReDirect" => "Input_Nav"]);

        //列表頁面
        $Item['ViewsPath'] = "admin";
        $RedirectAdmin = RedirectAdmin::getListByItem($Item);

        foreach ($RedirectAdmin as $item) {
            $Return[$item['ReDirect']] = _Views::RedirectAdmin(["ReDirect" => $item['ReDirect']]);
        }


        if (!empty($Return['ReDirect']))
            $Echo = _Views::RedirectAdmin($Return);
        if (!empty($_SESSION[Tools::getIp()]['History'])) {
            $Return['ReDirect'] = $_SESSION[Tools::getIp()]['History'];
            $History = _Views::RedirectAdmin($Return);
        }



        if (!empty($Echo)) echo $Echo;
        else if (!empty($History)) echo $History;
        else {
            unset($_SESSION[Tools::getIp()]['ReDirect']);
            $Return['ReDirect'] = "UserSign";
            echo _Views::RedirectAdmin($Return);
        }
    }
}
