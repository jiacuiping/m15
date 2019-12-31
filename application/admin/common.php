<?php

use app\admin\model\Menu;


    //获取菜单
    function GetMenu()
    {
        $menuModel = new Menu();

        //组合条件
        // $ids = $groupModel->GetOneData(array('group_id'=>$group_id))['group_menus'];
        // if($ids != 'all')
        //     $where[''] = array('menu_id','in',$ids);
        $where['menu_status'] = 1;
        $where['menu_parent'] = 0;

        $menus = $menuModel->GetListByPage($where);

        $html = "<ul class='layui-nav layui-nav-tree' lay-shrink='all' id='LAY-system-side-menu' lay-filter='layadmin-system-side-menu'>";

        foreach ($menus as $key => $value) {

            $item = $menuModel->GetListByPage(array('menu_parent'=>$value['menu_id'],'menu_status'=>1));

            if(!empty($item)){
                
                $html .= "  <li data-name='home' class='layui-nav-item'>".
                                "<a href='javascript:;' lay-tips='".$value['menu_name']."' lay-direction='2'>".
                                    $value['menu_icon'].
                                    "<cite>".$value['menu_name']."</cite>".
                                "</a>".
                                "<dl class='layui-nav-child'>";

                foreach ($item as $k => $v) {   //".$v['menu_url']."
                    $html .= "<dd data-name='console'>".
                                    "<a lay-href='".url($v['menu_url'])."'>".$v['menu_name']."</a>".
                                "</dd>";
                }

                $html .= "</dl></li>";
                
            }else{
                $html .= "<li data-name='get' class='layui-nav-item'>".
                            "<a href='".$value['menu_url']."' lay-href=' lay-tips='".$value['menu_name']."' lay-direction='2'>".
                            $value['menu_icon'].
                                "<cite>".$value['menu_name']."</cite>".
                            "</a>".
                        "</li>";
            }
        }

        $html .= "</ul>";

        return $html;
    }

    //密码加密
    function EncryptionPassword($password)
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyz';
        $login_encrypt_str = ''; 
        for ($i = 0; $i < 5; $i++) { 
            $login_encrypt_str .= $characters[rand(0, strlen($characters) - 1)]; 
        }

        $password = substr(base64_encode(substr(sha1(substr(md5($password.$login_encrypt_str),8,16)),10,20)),6,16);

        return array('password'=>$password,'login_encrypt_str'=>$login_encrypt_str);
        // rtrim(mcrypt_decrypt(MCRYPT_RIJNDAEL_256, md5($key), base64_decode($string), MCRYPT_MODE_CBC, md5(md5($key))), "12");
        // base64_encode(mcrypt_encrypt(MCRYPT_RIJNDAEL_256, md5($key), $string, MCRYPT_MODE_CBC, md5(md5($key))));
    }

    //密码验证
    function PasswordCheck($checkpassword, $truepassword, $str)
    {
    	return substr(base64_encode(substr(sha1(substr(md5($checkpassword.$str),8,16)),10,20)),6,16) === $truepassword ? 1 : 0;
    }

    //对象转数组
    function ObjectToArray($obj)
    {
        $obj = (array)$obj;
        foreach ($obj as $k => $v) {
            if (gettype($v) == 'resource')
                return;
            
            if (gettype($v) == 'object' || gettype($v) == 'array')
                $obj[$k] = (array)ObjectToArray($v);
        }
        return $obj;
    }

  //判断设备类型
  function ismobile() {
      
      if (isset ($_SERVER['HTTP_X_WAP_PROFILE'])) return true;    // 如果有HTTP_X_WAP_PROFILE则一定是移动设备
      
      if (isset ($_SERVER['HTTP_CLIENT']) &&'PhoneClient'==$_SERVER['HTTP_CLIENT']) return true;   //此条摘自TPM智能切换模板引擎，适合TPM开发
      
      if (isset ($_SERVER['HTTP_VIA'])) return stristr($_SERVER['HTTP_VIA'], 'wap') ? true : false;   //如果via信息含有wap则一定是移动设备,部分服务商会屏蔽该信息

      //判断手机发送的客户端标志,兼容性有待提高
      if (isset ($_SERVER['HTTP_USER_AGENT'])) {
          $clientkeywords = array(
              'nokia','sony','ericsson','mot','samsung','htc','sgh','lg','sharp','sie-','philips','panasonic','alcatel','lenovo','iphone','ipod','blackberry','meizu','android','netfront','symbian','ucweb','windowsce','palm','operamini','operamobi','openwave','nexusone','cldc','midp','wap','mobile'
          );
          //从HTTP_USER_AGENT中查找手机浏览器的关键字
          if (preg_match("/(" . implode('|', $clientkeywords) . ")/i", strtolower($_SERVER['HTTP_USER_AGENT']))) return true;
      }

      //协议法，因为有可能不准确，放到最后判断
      if (isset ($_SERVER['HTTP_ACCEPT'])) {
          // 如果只支持wml并且不支持html那一定是移动设备
          // 如果支持wml和html但是wml在html之前则是移动设备
          if ((strpos($_SERVER['HTTP_ACCEPT'], 'vnd.wap.wml') !== false) && (strpos($_SERVER['HTTP_ACCEPT'], 'text/html') === false || (strpos($_SERVER['HTTP_ACCEPT'], 'vnd.wap.wml') < strpos($_SERVER['HTTP_ACCEPT'], 'text/html')))) return true;
      }
      return false;
   }

?>
