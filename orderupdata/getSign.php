<?php
    //response.setHeader("Access-Control-Allow-Origin", "*"); 
    header('content-type:text/ html;charset=utf8;application/json');
	header('Access-Control-Allow-Origin:*'); 
	header('Access-Control-Allow-Headers:content-type');
	date_default_timezone_set("PRC");
	include_once "tools.php";
	
    $now = time();
    $gettablename = "ordertabel".date('Ym', $now);
    //sdk发过来的APP内容
    $sdk_app = null;
   if (isset($_GET['app']) && !empty($_GET['app'])){
        $sdk_app = $_GET['app'];
    }
    else
    {
        echo "app - null";
        exit();
        return;
    }
    //sdk发过来的cbi内容*/
    $sdk_cbi = null;

   if (isset($_GET['cbi']) && !empty($_GET['cbi'])){
        $sdk_cbi = $_GET['cbi'];
    }
    else
    {
        echo "cbi - null";
        exit();
        return;
    }

    $sdk_ct = null;
    if (isset($_GET['ct']) && !empty($_GET['ct'])){
        $sdk_ct = $_GET['ct'];
    }
    else
    {
        echo "ct - null";
        exit();
        return;
    }
    $sdk_fee = null;
    if (isset($_GET['fee']) && !empty($_GET['fee'])){
        $sdk_fee = $_GET['fee'];
    }
    else
    {
        echo "fee - null";
        exit();
        return;
    }
    $sdk_pt = null;
    if (isset($_GET['pt']) && !empty($_GET['pt'])){
        $sdk_pt = $_GET['pt'];
    }
    else
    {
        echo "pt - null";
        exit();
        return;
    }
    $sdk_to_id = null;
    if (isset($_GET['sdk']) && !empty($_GET['sdk'])){
        $sdk_to_id = $_GET['sdk'];
    }
    else
    {
        echo "sdk - null";
        exit();
        return;
    }
    $sdk_to_ssid = null;
    if (isset($_GET['ssid']) && !empty($_GET['ssid'])){
        $sdk_to_ssid = $_GET['ssid'];
    }
    else
    {
        echo "ssid - null";
        exit();
        return;
    }
    $sdk_st = null;
    if (isset($_GET['st']) && !empty($_GET['st'])){
        $sdk_st = $_GET['st'];
    }
    else
    {
        echo "st - null";
        exit();
        return;
    }
    $sdk_tcd = null;
    if (isset($_GET['tcd']) && !empty($_GET['tcd'])){
        $sdk_tcd = $_GET['tcd'];
    }
    else
    {
        echo "tcd - null";
        exit();
        return;
    }
    $sdk_uid = null;
    if (isset($_GET['uid']) && !empty($_GET['uid'])){
        $sdk_uid = $_GET['uid'];
    }
    else
    {
        echo "uid - null";
        exit();
        return;
    }
    $sdk_ver = null;
    if (isset($_GET['ver']) && !empty($_GET['ver'])){
        $sdk_ver = $_GET['ver'];
    }
    else
    {
        echo "ver - null";
        exit();
        return;
    }



    if($sdk_app==null &&
        $sdk_ct==null &&
        $sdk_fee==null &&
        $sdk_pt==null &&
        $sdk_to_id==null &&
        $sdk_to_ssid==null &&
        $sdk_st ==null &&
        $sdk_tcd==null &&
        $sdk_uid==null &&
        $sdk_ver==null &&
        $sdk_sign==null &&
        $sdk_cbi==null
    )
    {
        echo "Error parameter!";
        exit();
        return;
    }

    $miyao = date('Ymd', $now);
    $geturl = $_SERVER["QUERY_STRING"];
    //$geturl = strstr($geturl, "&sign", true);
    //echo $geturl ."<br/>";

    $geturl = urldecode($geturl);
   // echo "geturl :" . $geturl . "\n";
    $createQm = strToHex(md5($geturl.$miyao));
   echo $createQm;
    exit();

?>