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
    $sdk_sign = null;
    if (isset($_GET['sign']) && !empty($_GET['sign'])){
        $sdk_sign = $_GET['sign'];
    }
    else
    {
        echo "sign - null";
        exit();
        return;
    }

    $cbi_itemId = null;
    $cbi_itemName = null;
    $cbi_playerId = null;
    $cbi_channelId = null;
    $cbi_serverId = null;
    $cbi_areaId = null;
    if($sdk_cbi !=null){
        $jsonObj = json_decode($sdk_cbi,true);
        //var_dump($jsonObj["ItemId"]);
        if(!empty($jsonObj["ItemId"]))
        {
            $cbi_itemId = $jsonObj["ItemId"];
        }
        if(!empty($jsonObj["ItemName"]))
        {
            $cbi_itemName = $jsonObj["ItemName"];
        }
        if(!empty($jsonObj["playerId"]))
        {
            $cbi_playerId = $jsonObj["playerId"];
        }
        if(!empty($jsonObj["channelId"]))
        {
            $cbi_channelId = $jsonObj["channelId"];
        }
        if(!empty($jsonObj["serverId"]))
        {
            $cbi_serverId = $jsonObj["serverId"];
        }
        if(!empty($jsonObj["areaId"]))
        {
            $cbi_areaId = $jsonObj["areaId"];
        }

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
        $cbi_itemId==null &&
        $cbi_itemName==null &&
        $cbi_playerId==null &&
        $cbi_channelId==null &&
        $cbi_serverId==null &&
        $cbi_areaId==null
    )
    {
        echo "Error parameter!";
        exit();
        return;
    }

    $miyao = date('Ymd', $now);
    $geturl = $_SERVER["QUERY_STRING"];
    $geturl = strstr($geturl, "&sign", true);
    $geturl = urldecode($geturl);
    $createQm = strToHex(md5($geturl.$miyao));
    if($sdk_sign != $createQm ){
        //验证失败
        echo "error:illegal get";
        exit();
        return;
    }
    $mysqli = new mysqli("127.0.0.1", "root", "", "orderdb");
    mysqli_query($mysqli,"set names 'utf8'");
    if ($mysqli->connect_errno) {
        printf("Connect failed: %s\n", $mysqli->connect_error);
        //$mysqli->close();
        exit();
    }
    $sql = "SHOW TABLES LIKE "."'". $gettablename."'";
    $result = $mysqli->query($sql); 

    if(!$result->num_rows){
        //没有表创建表  
		//id(auto),
		//playerId 用户id
		//uid_to_skd  付费用户在渠道平台上的唯一标记
		//channelId 购买渠道id
		//serverId 服务器id
		//areaId 购买区域id
		//itemId 购买物品id
		//itemName  购买物品名字
		//pay_money 购买金额
		//order_id_ch 自定义的订单id, playerId+请求时间(毫秒断最后5位)
		//stutes 状态,
		//pay_done_time  完成时间,(毫秒)
		//give_item_st 发放状态 1-未发放,2-cp服务器已经处理过了 3-已经发过奖
		//pt_to_time  //付费时间，订单创建服务器UTC时间戳（毫秒）
		//channel_to_sdk_Id 渠道在易接服务器的ID,
		//order_to_sdk_ssid  订单在渠道平台上的流水号
		//order_to_sdk_tcd 订单在易接服务器上的订单号
		//sdk_ver  协议版本号，目前为“1”
		//sdk_app, 十六进制字符串形式的应用ID (易接发过来的)
		//sdk_cbi  (字符串)在客户端由CP应用指定的额外参数，比如可以传游戏中的道具ID、游戏中的用户ID等//(STR:itemId,itemName,playerId,serverId,channelId(购买渠道id),areaId),

        $sql = "CREATE TABLE IF NOT EXISTS"." " .$gettablename." (
           id INT(10) UNSIGNED NOT NULL AUTO_INCREMENT ,
		   playerId  VARCHAR(128) NOT NULL ,
		   uid_to_skd  VARCHAR(128) NOT NULL ,
		   channelId VARCHAR(128) NOT NULL ,
		   serverId VARCHAR(128) NOT NULL ,
		   areaId VARCHAR(128) NOT NULL ,
		   itemId VARCHAR(128) NOT NULL ,
		   itemName VARCHAR(128) NOT NULL ,
		   pay_money INT(11) UNSIGNED NOT NULL,
		   order_id_ch VARCHAR(128) NOT NULL ,
		   stutes INT(11) UNSIGNED NOT NULL,
		   pay_done_time  VARCHAR(128) NOT NULL ,
		   give_item_st INT(11) NOT NULL ,
		   pt_to_time VARCHAR(128) NOT NULL ,
		   channel_to_sdk_Id VARCHAR(128) NOT NULL ,
		   order_to_sdk_ssid VARCHAR(128) NOT NULL UNIQUE,
		   order_to_sdk_tcd  VARCHAR(128) NOT NULL UNIQUE,
		   sdk_ver VARCHAR(10) NOT NULL ,
		   sdk_app VARCHAR(128) NOT NULL ,
		   sdk_cbi VARCHAR(256) NOT NULL ,
            PRIMARY KEY(id), 
            INDEX id(id)
        )ENGINE=MyISAM DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;";
        $result = $mysqli->query($sql);

    }

    //先搜索这个订单是否已经存在.
    $sql = "SELECT * FROM  " .$gettablename." WHERE uid_to_skd='".$sdk_uid."' and order_to_sdk_ssid='".$sdk_to_ssid."' and order_to_sdk_tcd='".$sdk_tcd."' LIMIT 1";
    $result = $mysqli->query($sql);
    if ($result->num_rows) {
        /*
        $sql = "UPDATE $gettablename SET stutes='".$sdk_st."',pay_done_time=".$sdk_ct." WHERE uid_to_skd='".$sdk_uid."' and order_to_sdk_ssid='".$sdk_to_ssid."' and order_to_sdk_order='".$sdk_tcd."'";
        $result = $mysqli->query($sql);
        if ($result) {
            echo "SUCCESS";
        }else{
            printf("Connect failed: %s\n", $mysqli->connect_error);
        }*/
        echo "SUCCESS";
    }
    else{
        $payTime = date('YmdHis', $now);
        $pay_done_time = date('Y-m-d H:i:s', $sdk_ct);
        $sdk_pt_time =  date('Y-m-d H:i:s', $sdk_pt);
        $order_id_ch = strval($cbi_playerId.$now);
        $sql = "INSERT INTO $gettablename(playerId,
        uid_to_skd,
        channelId,
        serverId,
        areaId,
        itemId,
        itemName,
        pay_money,
        order_id_ch,
        stutes,
        pay_done_time,
        give_item_st,
        pt_to_time,
        channel_to_sdk_Id,
        order_to_sdk_ssid,
        order_to_sdk_tcd,
        sdk_ver,
        sdk_app,
        sdk_cbi)VALUES('".$cbi_playerId."',
        '".$sdk_uid."',
        '".$cbi_channelId."',
        '".$cbi_serverId."',
        '".$cbi_areaId."',
        '".$cbi_itemId."',
        '".$cbi_itemName."',
        '".$sdk_fee."',
        '".$order_id_ch."',
        '".$sdk_st."',
        '".$pay_done_time."',
        '1',
        '".$sdk_pt_time."',
        '".$sdk_to_id."',
        '".$sdk_to_ssid."',
        '".$sdk_tcd."',
        '".$sdk_ver."',
        '".$sdk_app."',
        '".$sdk_cbi."')";
        $result = $mysqli->query($sql);
        if($result)
        {
            echo "SUCCESS";
        }
        else
        {

            //printf("Connect failed: %s\n", $mysqli->connect_error);
        }

    }
    $mysqli->close();
    exit();
?>