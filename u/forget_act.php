<?php require_once "../backend/chu_tool.php"; ?>
<?php check_to_content('/c/course_list.php'); ?>
<?php require_once "../templates/base.php"; ?>
<?php
	header("Content-Type: application/json; charset=utf-8");
	if(isset($_POST['SID']) && !empty($_POST['SID']))
	{
		$SID = input_filter(strtolower($_POST['SID'])); //這個SID是學號

		$db_tool = new DB_Tool;
		$db = &$db_tool->get_DB();
		$exist = $db_tool->get_value('accounts', 'count(*)', 'SID = ?', array($SID));

		if($exist == 1)
		{
			$mail_info = $db_tool->get_value('mail_forget_password', 'mail_sendtime', 'SID = ? AND status = 0 ORDER BY mail_sendtime DESC LIMIT 1', array($SID));

			if(!empty($mail_info))
			{
				$date_prev = new DateTime($mail_info, new DateTimeZone('Asia/Taipei'));
				$date_now = new DateTime(null, new DateTimeZone('Asia/Taipei'));
				$time_sub = $date_prev -> diff($date_now);

				if($time_sub->y == 0 && $time_sub->m == 0 && $time_sub->d == 0 && $time_sub->h < 1)
				{
					$arr = array('status' => 0, 'msg' => '您已申請過，若一小時後仍未收到再行申請，謝謝。');
				}
				else
				{
					$mail_sendtime = GET_DATETIME();
					$mail_token = SET_MAILTOKEN($SID, $mail_sendtime);

					$sql = "INSERT INTO mail_forget_password(SID, mail_token, mail_sendtime, UserIp, UserAgent, add_on) VALUES(?, ?, ?, ?, ?, ?);";
					$std = $db->prepare($sql);
					$std->execute (array($SID, $mail_token, $mail_sendtime, GET_USER_IP(), GET_USER_AGENT(), GET_DATETIME()));

					send_forget_message($SID, $mail_token);

					$arr = array('status' => 1, 'msg' => '密碼通知信已寄出，請至校內信箱查看，如仍未收到，請直接與我們聯繫。');
				}
			}
			else
			{
				$mail_sendtime = GET_DATETIME();
				$mail_token = SET_MAILTOKEN($SID, $mail_sendtime);

				$sql = "INSERT INTO mail_forget_password(SID, mail_token, mail_sendtime, UserIp, UserAgent, add_on) VALUES(?, ?, ?, ?, ?, ?);";
				$std = $db->prepare($sql);
				$std->execute (array($SID, $mail_token, $mail_sendtime, GET_USER_IP(), GET_USER_AGENT(), GET_DATETIME()));

				send_forget_message($SID, $mail_token);

				$arr = array('status' => 1, 'msg' => '密碼通知信已寄出，請至校內信箱查看。');
			}
		}
		else
		{
			$arr = array('status' => 0, 'msg' => '此學號尚未註冊。');
		}
	}
	else
	{
			$arr = array('status' => 0, 'msg' => '系統發生了一些錯誤，如果持續發生，請與我們聯絡。');
	}

	echo urldecode(json_encode($arr));

	function send_forget_message($SID, $mail_token) {

		$content = "<h3>※ 此為系統自動發送，請勿回覆此郵件 ※</h3><br><p>哈囉，親愛的同學（B10110086）您好：<br><br>此為 中華大學資訊網 忘記密碼通知信，<br><br>如果<strong>您本人未忘記密碼，請勿點選下列連結，並立刻與我們聯繫</strong>，以確保您的帳號安全。<br><br><br>	若為您本人申請，請點選下列網址重新設定密碼：<br><br><a href='https://chuinfo.com/u/resetpassword.php?sid=$SID&amp;token=$mail_token' target='_blank'>https://chuinfo.com/u/resetpassword.php?sid=$SID&amp;token=$mail_token</a><br><br><br>如果有任何建議或疑問，<br><br>歡迎隨時透過<a href='https://www.facebook.com/chuinfo/' target='_blank'>Facebook 粉絲專頁</a>與我們聯繫。<br><br><br>再次感謝您的使用，<br><br>期望您有個充實而美好的大學時光。<br><br><br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;中華大學資訊網　敬上<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href='https://www.chuinfo.com/' target='_blank'>https://www.chuinfo.com/</a><br><br><br><i>如果您沒有在本網站進行註冊，請盡快與我們聯繫，並忽略此封信件，謝謝。<br><br></i></p><h3>※ 此為系統自動發送，請勿回覆此郵件 ※</h3><br><br>";

		$recevier = strtoupper($SID) . ' <' . strtolower($SID) . "@chu.edu.tw>";

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
		curl_setopt($ch, CURLOPT_USERPWD, 'api:API_KEY');
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
		curl_setopt($ch, CURLOPT_URL, 'API_URL');
		curl_setopt($ch, CURLOPT_POSTFIELDS, 
	                array('from' => '中華大學資訊網 <no-reply@chuinfo.com>',
	                      'to' => $recevier,
	                      'subject' => '中華大學資訊網 忘記密碼通知信',
	                      'html' => $content));
		$result = curl_exec($ch);
		curl_close($ch);
		return $result;
	}
?>