<?php
	require_once "DB_Tool.php";

/**********************************************
 * 　　　　　　　　　　　　　　　　　　　　　 *
 * 　　　　　　　　通用函式　　　　　　　　　 *
 * 　　　　　　　　　　　　　　　　　　　　　 *
 **********************************************/

	function input_filter($str,$filter = FILTER_UNSAFE_RAW ,$options = null){
		if(gettype($str)=='array'){
			foreach ($str as $key => $value) {
				$value = iconv('utf-8','utf-8//IGNORE',$value);
				if($options){
					$value =  filter_var($value, $filter, $options);
				}else{
					$value =  filter_var($value, $filter);
				}
				$str[$key] = $value;
			}
		}else{
			$str = iconv('utf-8','utf-8//IGNORE',$str);
			if($options){
				$str =  filter_var($str, $filter, $options);
			}else{
				$str =  filter_var($str, $filter);
			}
		}
		return $str;
	}

	//取得使用者IP
	function GET_USER_IP(){

		if (!empty($_SERVER["HTTP_CLIENT_IP"])){
	    	$ip = $_SERVER["HTTP_CLIENT_IP"];
		}
		elseif(!empty($_SERVER["HTTP_X_FORWARDED_FOR"])){
		    $ip = $_SERVER["HTTP_X_FORWARDED_FOR"];
		}
		else{
		    $ip = $_SERVER["REMOTE_ADDR"];
		}	

		return $ip;
	}

	//取得使用者的系統及瀏覽器(不一定正確)
	function GET_USER_AGENT(){
		$useragent = $_SERVER['HTTP_USER_AGENT'];
		return $useragent;
	}

	//取得使用者的來源(不一定正確)
	function GET_USER_REFERER(){
		$userreferer = $_SERVER['HTTP_REFERER'];
		return $userreferer;
	}
	
	function GET_DATETIME()
	{
		date_default_timezone_set("Asia/Taipei");
		return date("Y-m-d H:i:s",time());
	}

	//取得雜湊字串(32碼)
	function HASH_STR_32($str){
		$salt1 = "自訂字串";
		$salt2 = "自訂字串";
		
		$en_str = $salt1 . $str . $salt2;

		$en_str = openssl_digest($en_str, 'sha256');
		$en_str = md5($en_str);

		return $en_str;
	}

	//取得雜湊字串(64碼)
	function HASH_STR_64($str){
		$salt1 = "自訂字串";
		$salt2 = "自訂字串";
		$en_str = $salt1 . $str . $salt2;
		$en_str = md5(openssl_digest($en_str, 'sha256'));

		$salt3 = "自訂字串";
		$salt4 = "自訂字串";
		$en_str = $en_str . md5(openssl_digest($salt3 . $en_str . $salt4, 'sha256'));

		return $en_str;
	}


	//在多維陣列($array)內比對是否存在傳入的$value，若存在回傳true，否則false
	function deep_in_array($value, $array) { 
		foreach($array as $item) { 
			if(!is_array($item)) { 
				if ($item == $value) {
					return true;
				} else {
					continue; 
				}
			} 

			if(in_array($value, $item)) {
				return true; 
			} else if(deep_in_array($value, $item)) {
				return true; 
			}
		} 
		return false; 
	}

	//在多維陣列內尋找是否存在$value，若存在則回傳該值的索引值 + 1 (index + 1)
	function index_in_array($value, $array) { 
		$i = 1;
		foreach($array as $item) { 
			if(!is_array($item)) { 
				if ($item == $value) {
					return $i;
				} else {
					$i++;
					continue; 
				}
			} 

			if(in_array($value, $item)) {
				return $i;
			} else if(deep_in_array($value, $item)) {
				return $i;
			}

			$i++;
		} 

		return 0; 
	}

	//在多維陣列內尋找是否存在$value，若存在則刪除，並回傳陣列，不存在則回傳false
	function del_array_val($value, $array){
		$i = index_in_array($value, $array);
		if($i != 0){
			unset($array[$i - 1]);
			$array = array_values($array);
			return $array;
		}
		else
			return false;
	}




/**********************************************
 * 　　　　　　　　　　　　　　　　　　　　　 *
 * 　　　　　中華大學資訊網專用函式　　　　　 *
 * 　　　　　　　　　　　　　　　　　　　　　 *
 **********************************************/

	//檢查是否有登入(SESSION['SID'])，若無則導向至登入頁面(需要放在網頁輸出任何內容之前)
	function check_to_login()
	{
		Session_start();
		
		$root = 'http://' . $_SERVER['HTTP_HOST'];

		if(!isset($_SESSION['SID']) || empty($_SESSION['SID']) || !isset($_SESSION['UserNo']) || empty($_SESSION['UserNo']) || !isset($_SESSION['Permission']))
		{
			$url = $root . '/u/login.php';
			header("Location: $url");
			exit();
		}
		else if($_SESSION['Permission'] < 1)
		{
			$url = $root . '/u/resend.php';
			header("Location: $url");
			exit();
		}
	}

	//檢查是否有登入(SESSION['SID'])，若有則導向至指定頁面(需要放在網頁輸出任何內容之前)
	function check_to_content($path)
	{
		Session_start();
	
		$root = 'http://' . $_SERVER['HTTP_HOST'];

		if(isset($_SESSION['SID']) && !empty($_SESSION['SID']) && isset($_SESSION['UserNo']) && !empty($_SESSION['UserNo']))
		{
			$url = $root . $path;
			header("Location: $url");
			exit();
		}
	}

	//導向至指定頁面(需要放在網頁輸出任何內容之前)
	function turn_to_url($path)
	{
		$root = 'http://' . $_SERVER['HTTP_HOST'];

		$url = $root . $path;
		header("Location: $url");
		exit();
	}

	function login_log($sid, $status)
	{
		$db_tool = new DB_Tool;
		$db = &$db_tool->get_DB();

		$sql = "INSERT INTO log_login(SID, UserIp, UserAgent, UserReferer, Status, add_on) VALUES(?, ?, ?, ?, ?, ?);";
		$std = $db->prepare($sql);
		$std->execute(array($sid, GET_USER_IP(), GET_USER_AGENT(), GET_USER_REFERER(), $status, GET_DATETIME()));
	}

	function course_detail_log($UserNo, $GroupNo)
	{
		$db_tool = new DB_Tool;
		$db = &$db_tool->get_DB();

		$sql = "INSERT INTO log_course_detail(UserNo, GroupNo, UserIp, UserAgent, UserReferer, add_on) VALUES(?, ?, ?, ?, ?, ?);";
		$std = $db->prepare($sql);
		$std->execute(array($UserNo, $GroupNo, GET_USER_IP(), GET_USER_AGENT(), GET_USER_REFERER(), GET_DATETIME()));
	}

	function course_search_log($sid, $deptNo, $type, $day, $keyword)
	{
		$db_tool = new DB_Tool;
		$db = &$db_tool->get_DB();

		$sql = "INSERT INTO log_course_search(SID, DeptNo, Type, ClassTime, Keyword, add_on) VALUES(?, ?, ?, ?, ?, ?);";
		$std = $db->prepare($sql);
		$std->execute(array($sid, $deptNo, $type, $day, $keyword, GET_DATETIME()));
	}

	//用在轉換emoji，讓使用者輸入資料的地方使用
	function  emojiEncode ($str) { 
	    if (!is_string( $str )) return  $str ;
	     if (! $str || $str == 'undefined' ) return  '' ;
	    $text = json_encode($str); //暴露出unicode 
	    $text = preg_replace_callback( "/(\\\u[ed][0-9a-f]{3})/i" , function ( $str ) { 
	        return addslashes(addslashes($str[0]));
	    }, $text ); //將emoji的unicode留下，其他不動，這裡的正則比原答案增加了d，因為我發現我很多emoji實際上是\ud開頭的，反而暫時沒發現有\ue開頭。
	    return json_decode($text);
	}

	function emojiDecode($str){
	    $text = json_encode($str); //暴露出unicode
	    if(preg_match("/(\\\u[ed][0-9a-f]{3})/i", $str))
	    {
		    $text = preg_replace_callback('/\\\\\\\\\\\\\\\\/i',function($str){
		        return '\\';
		    },$text); //将两条斜杠变成一条，其他不动
	    }
	    return json_decode($text);
	}

//
//　==================== 會員專區 ====================
//


	//用於設定新會員密碼 
	function SET_PASSWORD($sid, $pw){

		$new_pw = $pw;

		for($i = 0; $i < 5; $i++){
			$new_pw = GET_HASH_PW($sid, $new_pw);
		}

		return $new_pw;
	}

	function SET_MAILTOKEN($sid, $time){
		$str = $sid . $time;
		return HASH_STR_64($str);
	}

	//將密碼明文進行加密，以取得128碼密文
	function GET_HASH_PW($sid, $pw){

		//先藉由MD5雜湊法將帳號及密碼變形為64碼的密文
		$sid = GET_MD5_HASH_FOR_PW($sid);
		$pw = GET_MD5_HASH_FOR_PW($pw);

		//作為帳號加密過程中的salt
		$salt1 = '自訂字串';
		$salt2 = '自訂字串';
		$salt3 = '自訂字串';
		$salt4 = '自訂字串';
		
		//作為密碼加密過程中的salt
		$salt5 = '自訂字串';
		$salt6 = '自訂字串';
		$salt7 = '自訂字串';
		$salt8 = '自訂字串';

		//將帳號加密（sha256）產生64碼密文 : 並將密碼作為salt參入其中
		$en_ur = $salt1 . $sid . $salt2;
		$en_ur = $en_ur . openssl_digest($salt3 . $pw . $salt4, 'sha256');
		$en_ur = openssl_digest($en_ur, 'sha256');

		//進行密碼加密（sha256）產生64碼密文 : 並將帳號作為salt參入其中
		$en_pw = $salt5 . $pw . $salt6;
		$en_pw = $en_pw . openssl_digest($salt7 . $sid . $salt8, 'sha256');
		$en_pw = openssl_digest($en_pw, 'sha256');

		//將加密後的帳號及密碼拆散並重新排列，再分兩組進行加密（sha256）後組合為最後的密碼
		$str = openssl_digest(substr($en_pw, 0, 32) . substr($en_ur, 32, 32), 'sha256');
		$str = $str . openssl_digest(substr($en_pw, 32, 32) . substr($en_ur, 0, 32), 'sha256');

		return $str;
	}

	//用md5雜湊法將字串加密，以取得64碼密文
	function GET_MD5_HASH_FOR_PW($str){
		$str = 'c7b9' . $str . 'a1e6' . strrev($str) . 'd0f2';
		$s1 = substr($str, 0, strlen($str)/4);
		$s2 = substr($str, strlen($str)/4, strlen($str)/4);
		$s3 = substr($str, (strlen($str)/4) * 2, strlen($str)/4);
		$s4 = substr($str, (strlen($str)/4) * 3, strlen($str)/4);

		$new_str = $s4 . strrev($s3) . $s1 . strrev($s2) . $s4 . strrev($s1) . $s3;
		$new_str = md5($s1 . $new_str . $s4) . md5($s3 . $new_str . $s2);

		return $new_str;
	}

	function GET_HASH_ID($no, $id){
		$salt1 = "自訂字串";
		$salt2 = "自訂字串";

		$str = $salt1 . $id . $salt2 . $no;

		return md5($str);
	}

//
//　==================================================
//

?>