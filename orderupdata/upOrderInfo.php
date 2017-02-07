<?php
    //response.setHeader("Access-Control-Allow-Origin", "*"); 
    header('content-type:text/ html;charset=utf8;application/json');
	header('Access-Control-Allow-Origin:*'); 
	header('Access-Control-Allow-Headers:content-type');
	date_default_timezone_set("PRC");
	include_once "tools.php";

    $getType = null;
    if (isset($_GET['upType']) && !empty($_GET['upType'])){
        $getType = $_GET['upType'];
    }
    else
    {
        echo "upType - null";
        exit();
        return;
    }
    $getOrderId = null;
    if (isset($_GET['orderId']) && !empty($_GET['orderId'])){
        $getOrderId = $_GET['orderId'];
    }
    else
    {
        echo "orderId - null";
        exit();
        return;
    }
    $getPlayerId = null;
    if (isset($_GET['playerId']) && !empty($_GET['playerId'])){
        $getPlayerId = $_GET['playerId'];
    }
    else
    {
        echo "playerId - null";
        exit();
        return;
    }
    $getchannelId = null;
    if (isset($_GET['channelId']) && !empty($_GET['channelId'])){
        $getchannelId = $_GET['channelId'];
    }
    else
    {
        echo "channelId - null";
        exit();
        return;
    }
    $getServerId = null;
    if (isset($_GET['serverId']) && !empty($_GET['serverId'])){
        $getServerId = $_GET['serverId'];
    }
    else
    {
        echo "serverId - null";
        exit();
        return;
    }
    $now = time();
    $gettablename = "ordertabel".date('Ym', $now);
    $mysqli = new mysqli("127.0.0.1", "root", "", "orderdb");
    mysqli_query($mysqli,"set names 'utf8'");
    if ($mysqli->connect_errno) {
        printf("Connect failed: %s\n", $mysqli->connect_error);
        $mysqli->close();
        exit();
    }
    $sql = "SHOW TABLES LIKE "."'". $gettablename."'";
    $result = $mysqli->query($sql);
    if(!$result->num_rows) {
        echo "no table of name {$gettablename}";
    }
    else{
        $sql = "SELECT * FROM  {$gettablename} WHERE order_to_sdk_tcd='".$getOrderId."' and playerId='".$getPlayerId."' and serverId='".$getServerId."' and channelId='".$getchannelId."' LIMIT 1";
        $result = $mysqli->query($sql);
        if(!$result->num_rows){
            echo "no order info!";
        }
        else
        {
            $isHaveType = false;
            while ( $rows= $result->fetch_array(MYSQLI_ASSOC) ) {
                if($rows["give_item_st"] == $getType){
                    $isHaveType = true;
                }
            }
            if(!$isHaveType){
                $sql = "UPDATE  {$gettablename} SET give_item_st='".$getType."' WHERE order_to_sdk_tcd='".$getOrderId."' and playerId='".$getPlayerId."' and serverId='".$getServerId."' and channelId='".$getchannelId."'";
                $result = $mysqli->query($sql);
                if($result){
                    echo "SUCCESS";
                }
            }
            else
            {
                echo "Already type={$getType}";
            }
        }
    };
    $mysqli->close();
    exit();
?>