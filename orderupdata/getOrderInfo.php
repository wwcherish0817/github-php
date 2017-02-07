<?php
    //response.setHeader("Access-Control-Allow-Origin", "*"); 
    header('content-type:text/ html;charset=utf8;application/json');
	header('Access-Control-Allow-Origin:*'); 
	header('Access-Control-Allow-Headers:content-type');
	date_default_timezone_set("PRC");
	include_once "tools.php";

    $getInfo = null;
    if (isset($_GET['getInfo']) && !empty($_GET['getInfo'])){
        $getInfo = $_GET['getInfo'];
    }
    else
    {
        echo "getInfo - null";
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
        echo "noe table of name {$gettablename}";
    }
    else{
        $jsoncode_getInfo = json_decode($getInfo,true);
        $everySql = array();
        $i = 0;
        foreach($jsoncode_getInfo as $value)
        {
            $everySqlStr;
            if($i == count($jsoncode_getInfo)-1){
                $everySqlStr = " playerId={$value["playerId"]} AND  channelId={$value["channelId"]} AND serverId={$value["serverId"]} ";
            }
            else
            {
                $everySqlStr= " playerId={$value["playerId"]} AND  channelId={$value["channelId"]} AND serverId={$value["serverId"]} OR";
            }
            $everySql[] =$everySqlStr;
            $i++;
        }
        $str = implode($everySql);
        $sql =  "SELECT * FROM {$gettablename} WHERE {$str} ";
        $result = $mysqli->query($sql);
        if($result->num_rows) {
            $arrItem = array();
            while ( $rows= $result->fetch_array(MYSQLI_ASSOC) ) {
                unset($rows["id"]);
                unset($rows["itemName"]);
                unset($rows["sdk_cbi"]);
                unset($rows["pt_to_time"]);
                unset($rows["pay_done_time"]);
                $arrItem[] = $rows  ;
            }
            echo json_encode($arrItem);
        }
        else
        {
            echo "no order info";
        }

    };
    $mysqli->close();
    exit();
?>