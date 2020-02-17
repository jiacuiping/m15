<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 流年 <liu21st@gmail.com>
// +----------------------------------------------------------------------

// 应用公共文件

/**************************************************** DateTime Consollers ****************************************************/
/**
 * 获取时间戳
 * @param  [int]   	 $number 	数字
 * @param  [string]  $to 		单位
 * @return [int]				时间戳
 */

function GetTimestamp($number=0,$unit='day')
{
	return strtotime("$number $unit");
}


/**************************************************** Latitude and longitude Consollers ****************************************************/
/**
 * 根据起点坐标和终点坐标测距离
 * @param  [array]   $from 		[起点坐标(经纬度),例如:array(118.012951,36.810024)]
 * @param  [array]   $to 		[终点坐标(经纬度)]
 * @param  [bool]    $km        是否以公里为单位 false:米 true:公里(千米)
 * @param  [int]     $decimal   精度 保留小数位数
 * @return [string]  			距离数值
 */
function GetDistanceByLonLat($from,$to,$km=true,$decimal=2){
	sort($from);
	sort($to);
	$EARTH_RADIUS = 6370.996; // 地球半径系数
	
	$distance = $EARTH_RADIUS*2*asin(sqrt(pow(sin(($from[0]*pi()/180-$to[0]*pi()/180)/2),2)+cos($from[0]*pi()/180)*cos($to[0]*pi()/180)*pow(sin(($from[1]*pi()/180-$to[1]*pi()/180)/2),2)))*1000;
	
    if($km) $distance = $distance / 1000;

    return round($distance, $decimal);
}


/**************************************************** IdCard Consollers ****************************************************/

/**
 * 验证身份证号码
 * @param  [string]   $id_card 	身份证号码
 * @return [string]  			true/false
 */

function CheckIdCard($id_card){
    if(strlen($id_card) == 18)
        return EighteenIdCardCheck($id_card);
    elseif((strlen($id_card) == 15))
        return EighteenIdCardCheck(IdCardFifteenToEighteen($id_card));
    else
        return false;
}

// 计算身份证校验码
function IdCardVerifyNumber($idcard_base){
    if(strlen($idcard_base) != 17) return false;
    
    //加权因子
    $factor = array(7,9,10,5,8,4,2,1,6,3,7,9,10,5,8,4,2);
    //校验码对应值
    $verify_number_list = array('1','0','X','9','8','7','6','5','4','3','2');
    $checksum = 0;
    for($i = 0; $i < strlen($idcard_base); $i++){
        $checksum += substr($idcard_base,$i,1) * $factor[$i];
    }
    $mod = $checksum % 11;
    $verify_number = $verify_number_list[$mod];
    return $verify_number;
}
// 将15位身份证升级到18位
function IdCardFifteenToEighteen($idcard){
    if(strlen($idcard)!=15)
        return false;
    else
        // 如果身份证顺序码是996 997 998 999，这些是为百岁以上老人的特殊编码
    	$idcard = array_search(substr($idcard,12,3),array('996','997','998','999')) !== false ? substr($idcard,0,6).'18'.substr($idcard,6,9) : substr($idcard,0,6).'19'.substr($idcard,6,9);

    $idcard = $idcard.IdCardVerifyNumber($idcard);
    return $idcard;
}

// 18位身份证校验码有效性检查
function EighteenIdCardCheck($idcard){

    if(strlen($idcard)!=18) return false;

    $idcard_base=substr($idcard,0,17);

    return IdCardVerifyNumber($idcard_base) != strtoupper(substr($idcard,17,1)) ? false : true;
}



/**************************************************** IP Consollers ****************************************************/

function CheckIsIP($ipaddres)
{
    $preg = "/\A((([0-9]?[0-9])|(1[0-9]{2})|(2[0-4][0-9])|(25[0-5]))\.){3}(([0-9]?[0-9])|(1[0-9]{2})|(2[0-4][0-9])|(25[0-5]))\Z/";

    return preg_match($preg, $ipaddres) ? true : false;
}


function SubstrString($str,$number=50)
{
    $str = strip_tags($str);//去除html标签
    $pattern = '/\s/';//去除空白
    $content = preg_replace($pattern, '', $str);      
    $seodata = mb_substr($content, 0, $number);//截取汉字
    return $seodata;
}

/**************************************************** Mobile Consollers ****************************************************/

function CheckMobile($mobile)
{
    return preg_match("/^1[34578]\d{9}$/", $mobile) ? true : false;
}


/**************************************************** AliDaYu ****************************************************/
function sendSMS($mobile, $param = [])
{
    $templateCode = "SMS_172350876";
    $SignName = "M15生态服务平台";
    $TemplateParam = $param;
    $AccessKeyId = "";


}