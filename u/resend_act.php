<?php require_once "../backend/chu_tool.php"; ?>
<?php require_once "../templates/base.php"; ?>
<?php
		Session_start();
		
		$root = 'http://' . $_SERVER['HTTP_HOST'];

		if(!isset($_SESSION['SID']) || empty($_SESSION['SID']) || !isset($_SESSION['UserNo']) || empty($_SESSION['UserNo']) || !isset($_SESSION['Permission']))
		{
			$url = $root . '/u/login.php';
			header("Location: $url");
			exit();
		}
?>
<?php
	header("Content-Type: application/json; charset=utf-8");
	if(isset($_POST['SID']) && !empty($_POST['SID']) && isset($_SESSION['UserNo']) && !empty($_SESSION['UserNo']) && isset($_SESSION['SID']) && $_POST['SID'] == $_SESSION['SID'])
	{
		$SID = input_filter(strtolower($_POST['SID'])); //這個SID是學號

		$db_tool = new DB_Tool;
		$db = &$db_tool->get_DB();
		$mail_info = $db_tool->get_value('accounts', 'mail_sendtime, mail_resend', 'No = ? AND SID = ? AND del_status = 0', array($_SESSION['UserNo'], $SID));

		if(!empty($mail_info))
		{
			if($mail_info['mail_resend'] == 0)
			{
				$date_prev = new DateTime($mail_info['mail_sendtime'], new DateTimeZone('Asia/Taipei'));
				$date_now = new DateTime(null, new DateTimeZone('Asia/Taipei'));
				$time_sub = $date_prev -> diff($date_now);

				if($time_sub->y == 0 && $time_sub->m == 0 && $time_sub->d == 0 && $time_sub->h == 0 && $time_sub->i < 10)
				{
					$arr = array('status' => 0, 'msg' => '請稍待片刻，若十分鐘後仍未收到再進行補寄，謝謝。');
				}
				else
				{
					$mail_sendtime = GET_DATETIME();
					$mail_token = SET_MAILTOKEN($SID, $mail_sendtime);

					$sql = "UPDATE accounts SET mail_token = ?, mail_sendtime = ?, mail_resend = 1 WHERE No = ? AND SID = ?;";
					$std = $db->prepare($sql);
					$std->execute (array($mail_token, $mail_sendtime, $_SESSION['UserNo'], $SID));
					send_reg_message($SID, $mail_token);

					$arr = array('status' => 1, 'msg' => '認證信已補寄，請至校內信箱查看。');
				}
			}			
			else
			{
				$arr = array('status' => 0, 'msg' => '您已補寄過認證信，如超過半小時仍未收到，請直接與我們聯絡。');
			}
		}
		else
		{
			$arr = array('status' => 0, 'msg' => '系統發生了一些錯誤，如果持續發生，請與我們聯絡。');
		}

	}
	else
	{
			$arr = array('status' => 0, 'msg' => '系統發生了一些錯誤，如果持續發生，請與我們聯絡。');
	}

	echo urldecode(json_encode($arr));

	function send_reg_message($SID, $mail_token) {

		$content = "<h3>※ 此為系統自動發送，請勿回覆此郵件 ※</h3><br><p>哈囉，親愛的同學：<br><br>非常感謝您的註冊，在進行驗證前，想讓您知道，<br><br>中華大學資訊網，將會是您接下來的大學生涯中，最棒的交流園地。<br><br><br>目前我們仍屬起步階段，可能有許多功能未臻完善，<br><br>還望同學們見諒，我們將會持續提升網站品質，<br><br>如果有任何建議或疑問，<br><br>歡迎隨時透過<a href='https://www.facebook.com/chuinfo/' target='_blank'> Facebook 粉絲專頁</a>與我們聯繫。<br><br><br>請點選下列網址完成驗證：<br><br><a href='https://chuinfo.com/u/identify.php?sid=$SID&amp;token=$mail_token' target='_blank'>https://chuinfo.com/u/identify.php?sid=$SID&amp;token=$mail_token</a><br><br><br>再次感謝您的註冊，<br><br>期望您有個充實而美好的大學時光。<br><br><br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;中華大學資訊網　敬上<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href='https://www.chuinfo.com/' target='_blank'>https://www.chuinfo.com/</a><br><br><br><i>如果您沒有在本網站進行註冊，請盡快與我們聯繫，並忽略此封信件，謝謝。<br><br></i></p><h3>※ 此為系統自動發送，請勿回覆此郵件 ※</h3><br><br>";

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
	                      'subject' => '中華大學資訊網 會員註冊認證信',
	                      'html' => $content));
		$result = curl_exec($ch);
		curl_close($ch);
		return $result;
	}
?>