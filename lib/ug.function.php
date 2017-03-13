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
function mid_ug($string, $start = 0, $length = null, $after = '...'){
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
    $start = ($start > 0) ? $start * 2 : $start;
    $length = ($length > 0) ? $length * 2 : $lenth;
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

/*
* E-mail to :kawak2@126.com;
* date : 2016-8-23 11:16 PM (Hoten);
*/ 

/**
*   维吾尔语ULY与UG之间互相转换
*   @param $str string 转换的值
*   @param $type bool true 最终的值 uly , false 维语拉丁字母 ug 维吾尔语
*   @return string 
*/
function ug_to_uly($str, $type = true){
    // uyghurche herip
    $UG=array('ئ','ا','ە','ې','ى','و','ۇ','ۈ','ۆ','ب','ت','س','ش','ق','ف','ج','چ','خ','م','ن','ر','ز','ك','ل','ڭ','ژ','پ','ي','ھ','ۋ','غ','د','گ','،','؟');
    // latinceh herip 
    $ULY  = array('','a','e','e','i','o','u','o','u','b','t','s','sh','q','f','j','ch','x','m','n','r','z','k','l','ng','j','p','y','h','w','gh','d','g',',','?');
    if($type==true){
    	$val = str_replace($UG, $ULY, $str); 
    }else{
        $val = $str;
    }
    return $val;
}

/**
*	NTU -> Number to uyghur
*	arap raqimini uyghurche kona yeziqqa ozgartish fonkisiyesi
*	数字转换大写字母【维】
*	@param $num number 转换的数字
*	@return string 转换的维文大写数字
*/
function NTU($num, $after = false){
	$num = explode('.', $num);
	if(count($num) > 1) {
		$minnum = $num[1];
		$num = $num[0];	
	}else{
		$num = $num[0];
	}
	if(strlen($num) > 15){
		return false;
	}
	$after = ($after) ? 'يۈەن' : null;
	$numlen = strlen($num);
	$i=0;$a = '';
	for($b=0;$b < $numlen;$b++){
		$nums = strrev($num);
		$a .= substr($nums,$i,1);
		if(strlen($a) == 3){
			$nume[] = $a;
			$a = '';
		}elseif(($numlen)-1 == $b) {
			$nume[] = $a;
		}$i++;	
	}
	// ----------------
	$model = array(0 => 'يۈز',1 => 'مىڭ',2 => 'مىليون',3=>'مىليارد',4 => 'ترىليون');
	foreach ($nume as $key => $val) {
		$val = strrev($val);
		if(strlen($val) == 3){
			$result[] = getthree($val);
		}elseif(strlen($val) == 2){
			$result[] = gettwo($val);
		}elseif(strlen($val) == 1){
			$result[] = getone($val);
		}
	}
	// -----------------------------
	krsort($result);$results ='';
	foreach ($result as $key => $val) {
		if($val != ''){
			$results .= $val.' '.$model[$key].' ';
		}
	}
	$before = substr($results, 0,strlen($results)-7);
	if(isset($minnum)){
		$result = $before.' پۈتۈن '.minnum($minnum);
	}else{
		$result = $before;
	}
	return $result.$after;
}
function minnum($minnum){
	$minnum = substr($minnum, 0,3);
	$min = array('0' => 'ئوندىن','00' =>'يۈزدىن','000' => 'مىڭدىن');
	foreach ($min as $key => $val) {
		if(strlen($minnum) == strlen($key)) {
			$return = $val.' '.NTU($minnum);
		}
	}
	return $return;
}
function getthree($num){
	$length = 3;
	if(strlen($num) > $length-1){
		$num = substr($num, -$length);
	}
	$model = Model();
	$minnumber = Minnumber();
	foreach($model as $key => $val) {
		$numlen = strlen($num);
		if(strlen($key) == $numlen) {
			if(substr($num,0,1) > 0) {
				$result = $minnumber[substr($num,0,1)].' '.$val.' '.gettwo($num);
			}elseif(substr($num,1,1) > 0){
				$result = gettwo($num);
			}else{
				$result = getone($num);
			}
		}
	}
	return $result;
}
function gettwo($num){
	$length = 2;
	if(strlen($num) > $length-1){
		$num = substr($num, -$length);
	}
	$area = Area();
	foreach($area as $key => $val) {
		if(strlen($key) == strlen($num)) {
			if(substr($num, 0,1) > 0) {
				$result = $area[substr($num, 0,1).'0'].' '.getone($num);
			}else{
				$result = getone($num);
			}
		}
	}
	return $result;
}
function getone($num){
	if(strlen($num) > 0){
		$num = substr($num, -1);
	}
	$minnumber = Minnumber();
	if(strlen($num) > 0){
		$result = $minnumber[substr($num, -1)];
	}
	return $result;
}
function Model(){
	$model = array(100 => 'يۈز',1000 => 'مىڭ',1000000 => 'مىليون',1000000000=>'مىليارد',1000000000000 => 'ترىليون');
	return $model;
}
function Minnumber(){
	$minnumber = array(1=>'بىر',2=>'ئىككى',3=>'ئۈچ',4=>'تۆت',5=>'بەش',6=>'ئالتە',7=>'يەتتە',8=>'سەككىز',9 => 'توققۇز',0 => '');
	return $minnumber;
}
function Area(){
	$area = array(10 => 'ئون',20=>'يىگىرمە',30=>'ئوتتۇز',40=>'قىررىق',50=>'ئەللىك',60=>'ئاتمىش',70=>'يەتمىش',80=>'سەكسەن',90=>'توقسان','00'=>'');
	return $area;
}

/**
* 20i6-1-19 自写
* 查询字符串是否纯维语
* @param int $str 查询字符串（维吾尔语）
* @param bool $harip 是否返回分开字母
* @return mexid  维吾尔语 1,非维吾尔语 0
*/
function Is_ug($str, $harip = false){
    $ug = array (
            'a'=>'ئ',
            'b'=>'ب',
            'd'=>'د',
            'e'=>'ە',
            'ee'=>'ې',
            'f'=>'ف',
            'g'=>'گ',
            'h'=>'ھ',
            'i'=>'ى',
            'j'=>'ج',
            'k'=>'ك',
            'l'=>'ل',
            'm'=>'م',
            'n'=>'ن',
            'o'=>'و',
            'p'=>'پ',
            'q'=>'ق',
            'r'=>'ر',
            's'=>'س',
            't'=>'ت',
            'u'=>'ۇ',
            'v'=>'ژ',
            'w'=>'ۋ',
            'x'=>'خ',
            'y'=>'ي',
            'ch'=>'چ',
            'ng'=>'ڭ',
            'gh'=>'غ',
            'sh'=>'ش',
            'oo'=>'ۆ',
            'uu'=>'ۈ',
            'z'=>'ز',
            'aa'=>'ا'
);
    $b = 0;$ok = 0;
    for($i = 2; $i <= strlen($str); $i=$i+2){
        if(in_array(substr($str,$b,2), array_values($ug))){
            $re[]=substr($str,$b,2);
            $ok=$ok + 1;   
        }
        $b=$b+$i-$b;
    }
    if(strlen($str) / 2 == $ok){
        if($harip == true){
            return $re;
        }
        return true;
    }else{
        return false;
    }
}
