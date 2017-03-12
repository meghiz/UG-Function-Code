<?php
/*
    Date : 201504;
    Group : OTD Design Group
    Programmer : Meghiz
    Url: http://www.meghiz.com
    E-mail: kawak2@126.com
*/

/**
* 截取维语字母 去掉乱码 [自写]
* @param string $string  要截取的维吾尔字母
* @param int $start 截取的开始位置 默认值 0 
* @param int $length 截取的结束位置 默认值空表示 从开始位置到尾
* @param string $after 要不要省略表示符 《...》如果 $length 空 没有添加此符
* @return String
*/
function Trim_ug($string, $length = null, $start = 0, $after = '...'){
    if($length == null and $length == 0){
        $length = strlen($string);
        $after = '';
    }
    if($length >= strlen($string) and $start == 0){
            return $string;
    }
    $str = explode(' ', $string);
    if(!preg_match('/^[0-9]{1,5}$/', $length/2)){
        $length = $length + 1;
    }
    if($start > 0){
        if(!preg_match('/^[0-9]{1,5}$/', $start/2)){
            $start = $start + 1;
        }
    }
    if($start >= 2 and $start <= strlen($string) -2){
        for($i = 0;$i <= count($str) - 1; $i++){
            if($start >= strlen($str[$i])){
                $start = $start - strlen($str[$i]);
                unset($str[$i]);
            }else{
                $str[$i] = substr($str[$i], $start);
                $start = 0;
                $i = count($str)-1;
            }
        }
    }
    $str = implode(" ", $str);
    $str = explode(' ', $str);
    $result = array();
    for($i = 0;$i <= count($str)-1;$i++){
        if($length >= strlen($str[$i])){
            if(Is_ug($str[$i])){
                // echo "ok";
                $result[]= $str[$i];
                $length = $length - strlen($str[$i]);
            }else{
                if(!preg_match('/^[0-9]{1,5}$/', strlen($str[$i])/2)){
                    // echo "no";
                    $result[]= $str[$i];
                    $length = $length - strlen($str[$i]) -1;
                }else{
                    $result[]= $str[$i];
                    $length = $length - strlen($str[$i]);
                }
            }
            
        }else{
            $result[] = substr($str[$i], 0,$length);
            $length = 0;
            $i = count($str)-1;
        }
    }
    $result = implode(" ", $result);
    return $result.$after;
}
