<?php
    //response.setHeader("Access-Control-Allow-Origin", "*"); 
    header('content-type:text/ html;charset=utf8;application/json');
	header('Access-Control-Allow-Origin:*'); 
	header('Access-Control-Allow-Headers:content-type');
	date_default_timezone_set("PRC");
	include_once "tools.php";

    $now = time();
    $gettablename = "ordertabel".date('Ym', $now);

    $mysqli = new mysqli("127.0.0.1", "root", "", "orderdb");
    mysqli_query($mysqli,"set names 'utf8'");
    if ($mysqli->connect_errno) {
        printf("Connect failed: %s\n", $mysqli->connect_error);
        //$mysqli->close();
        exit();
    }
    $sql = "SHOW TABLES LIKE "."'". $gettablename."'";
    $result = $mysqli->query($sql);

    if(!$result->num_rows) {

    }
    else{
        $sql = "SELECT * FROM ".$gettablename." WHERE `give_item_st`=1";
        $result = $mysqli->query($sql);
        if($result->num_rows){
            $arrItem = array();
            while ( $rows= $result->fetch_array(MYSQLI_ASSOC) ) {
                unset($rows["sdk_cbi"]);
                unset($rows["sdk_app"]);
                unset($rows["itemName"]);
                unset($rows["pt_to_time"]);
                unset($rows["pay_done_time"]);
                $arrItem[] = $rows  ;
            }
            echo json_encode($arrItem);
        }
    };
    $mysqli->close();
    exit();
?>