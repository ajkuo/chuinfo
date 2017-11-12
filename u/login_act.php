<?php
	Session_start();
	require_once "../templates/base.php";
	require_once "../backend/chu_tool.php";
	
	header("Content-Type: application/json; charset=utf-8");
	
	$db_tool = new DB_Tool;
	$db = &$db_tool->get_DB();
	
	$SID = isset($_POST['SID']) ? input_filter(strtolower($_POST['SID'])) : '';
	$PW = isset($_POST['Password']) ? input_filter($_POST['Password']) : '';
	$PW = $PW = '' ? '' : SET_PASSWORD($SID, $PW);

	if(!empty($SID) && !empty($PW))
	{
		$sql = "SELECT No as UserNo, SID, Nickname, Permission, lock_status, lock_reason FROM `accounts` WHERE `sid` = ? AND `password` = ? AND 'del_status' = 0;";
		$data_arr = array($SID, $PW);

		$std = $db->prepare($sql);
		$std->execute ($data_arr);
		$rc = $std -> rowCount();
		$data = $std -> fetch(PDO::FETCH_ASSOC);
		

		if($rc>0){
			if($data['lock_status'] == 1)
			{
				$arr = array('status' => 0, 'msg' => urlencode('此帳號已被鎖定，原因為：' . $data['lock_reason']));
				login_log($SID, 0);
			}
			else
			{
				$_SESSION['UserNo'] = $data['UserNo'];
				$_SESSION['SID'] = $data['SID'];
				$_SESSION['Permission'] = $data['Permission'];
				$arr = array('status' => 1, 'msg' => urlencode('登入成功！'));

				login_log($SID, 1);
			}
		}
		else{
			$arr = array('status' => 0, 'msg' => urlencode('學號或密碼錯誤！'));

			login_log($SID, 0);
		}
	}
	else{
		$arr = array('status' => 0, 'msg' => urlencode('請輸入學號和密碼！'));
	}

	echo urldecode(json_encode($arr));
?>