<?php

    function strToHex($string)//字符串转十六进制
    {
        $hex="";
        for($i=0;$i<strlen($string);$i++)
            $hex.=dechex(ord($string[$i]));
        $hex=strtoupper($hex);
        return $hex;
    }

    function hexToStr($hex)//十六进制转字符串
    {
        $string="";
        for($i=0;$i<strlen($hex)-1;$i+=2)
            $string.=chr(hexdec($hex[$i].$hex[$i+1]));
        return  $string;
    }
	function unicodeString($str, $encoding=null) { 
    return preg_replace_callback('/\\\\u([0-9a-fA-F]{4})/u', create_function('$match', 'return mb_convert_encoding(pack("H*", $match[1]), "utf-8", "UTF-16BE");'), $str);
	}

    function BKDRHash($str) {
     $seed = 131; 
    // 31 131 1313 13131 131313 etc.. $hash = 0; 
    $cnt = strlen($str); 
    $hash = null;
    for($i = 0; $i < $cnt; $i++) { 
        $hash = ((floatval($hash * $seed) & 0x7FFFFFFF) + ord($str[$i])) & 0x7FFFFFFF; 
    } 
    return ($hash & 0x7FFFFFFF); 
    } ;//1471979560 echo BKDRHash('asdfasdfasdf123'); echo BKDRHash('ggsonic')
?>