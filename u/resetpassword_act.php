<?php require_once "../backend/chu_tool.php"; ?>
<?php require_once "../templates/base.php"; ?>
<?php
	Session_start();

	if(isset($_SESSION['SID']))
	{
		Session_destroy();
	}

	
	header("Content-Type: application/json; charset=utf-8");

	$db_tool = new DB_Tool;
	$db = &$db_tool->get_DB();
	
	$SID = isset($_POST['SID']) ? input_filter(strtolower($_POST['SID'])) : '';
	$NewPassword = isset($_POST['NewPassword']) ? input_filter($_POST['NewPassword']) : '';
	$token = isset($_POST['token']) ? input_filter($_POST['token']) : '';

	if(!empty($SID) && !empty($NewPassword) && !empty($token))
	{
		$db_tool = new DB_Tool;
		$db = &$db_tool->get_DB();
		$user_no = $db_tool->get_value('accounts', 'No', 'SID = ?', array($SID));

		if(!empty($user_no))
		{
			$mail_info = $db_tool->get_value('mail_forget_password', 'mail_sendtime', 'SID = ? AND mail_token = ? AND status = 0 ORDER BY mail_sendtime DESC LIMIT 1', array($SID, $token));

			if(!empty($mail_info))
			{
				$date_prev = new DateTime($mail_info, new DateTimeZone('Asia/Taipei'));
				$date_now = new DateTime(null, new DateTimeZone('Asia/Taipei'));
				$time_sub = $date_prev -> diff($date_now);

				if($time_sub->y >= 0 && $time_sub->m >= 0 && $time_sub->d >= 3)
				{
					$arr = array('status' => 0, 'msg' => '此密碼重置信已失效，請重新申請。');
				}
				else
				{
					$NewPassword = SET_PASSWORD($SID, $NewPassword);

					$sql = "INSERT INTO accounts_pw_history(UserNo, SID, reason, UserIp, add_on) VALUES(?, ?, ?, ?, ?)";
					$std = $db->prepare($sql);
					$std->execute (array($user_no, $SID, "Change by FORGET.", GET_USER_IP(), GET_DATETIME()));

					$sql = "UPDATE accounts SET Password = ? WHERE No = ? AND SID = ? AND del_status = 0;";
					$std = $db->prepare($sql);
					$std->execute (array($NewPassword, $user_no, $SID));

					$sql = "UPDATE mail_forget_password SET status = 1 WHERE SID = ? AND mail_token = ?;";
					$std = $db->prepare($sql);
					$std->execute (array($SID, $token));

					$arr = array('status' => 1, 'msg' => '密碼變更完成，請重新登入。');
				}
			}
			else
			{
				$arr = array('status' => 0, 'msg' => urlencode('此密碼重置信無效，請以最新一封通知信為主。'));
			}
		}
		else
		{
			$arr = array('status' => 0, 'msg' => urlencode('發生了一些錯誤，如果持續發生，請與我們聯繫。'));
		}
	}
	else{
		$arr = array('status' => 0, 'msg' => urlencode('發生了一些錯誤，如果持續發生，請與我們聯繫。'));
	}

	echo urldecode(json_encode($arr));
?>