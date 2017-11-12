<?php require_once "../backend/chu_tool.php"; ?>
<?php check_to_content('/c/course_list.php'); ?>
<?php require_once "../templates/base.php"; ?>
<?php
	//連接資料庫
	$db_tool = new DB_Tool;
	$db = &$db_tool->get_DB();

	//檢查POST參數，確認參數是否存在(isset)，若不存在則先以空字串（''）取代，待會在empty檢查時會被淘汰
	$SID = isset($_POST['SID']) ? strtolower(input_filter($_POST['SID'])) : '';
	$Password = isset($_POST['Password']) ? input_filter($_POST['Password']) : '';
	$rePassword = isset($_POST['rePassword']) ? input_filter($_POST['rePassword']) : '';
	$Name = isset($_POST['Name']) ? input_filter($_POST['Name']) : '';
	$Gender = isset($_POST['Gender']) ? input_filter($_POST['Gender']) : '';
	$Department = isset($_POST['Department']) ? input_filter($_POST['Department']) : '';
	$Year = isset($_POST['Year']) ? input_filter($_POST['Year']) : '';
	$Month = isset($_POST['Month']) ? input_filter($_POST['Month']) : '';
	$Day = isset($_POST['Day']) ? input_filter($_POST['Day']) : '';
	$Nickname = isset($_POST['Nickname']) ? input_filter($_POST['Nickname']) : '';
	$Email = isset($_POST['Email']) ? input_filter($_POST['Email']) : '';

	$count = -1;	//用來判斷帳號是否已被註冊
	$key = 1; //用於各項檢查，若全部通過則保持key=1，若有錯誤則key=0
	$error_msg = ''; //用於存放錯誤訊息

	try
	{
		//檢查各項參數資料是否有值，若為空字串則不進行註冊
		if(!empty($SID) && !empty($Password) && 
		   !empty($rePassword) && !empty($Name) && 
		   !empty($Gender) && !empty($Department) && 
		   !empty($Year) && !empty($Month) && !empty($Day) && !empty($Nickname))
		{
			if(empty($Email))
			{
				$Email = null;
			}
			else
			{
				if(strlen($Email) > 300)
				{
					$key = 0;
					$error_msg = '信箱過長，請換一個試試看。';
				}
				else if(preg_match('/^\w+((-\w+)|(\.\w+))*\@[A-Za-z0-9]+((\.|-)[A-Za-z0-9]+)*\.[A-Za-z]+$/', $Email) == false)
				{
					$key = 0;
					$error_msg = '信箱格式有誤。';
				}
			}

			if(strlen($Nickname) > 20)
			{
				$key = 0;
				$error_msg = '暱稱過長，請重新輸入。';
			}

			if(strlen($Name) > 20)
			{
				$key = 0;
				$error_msg = '姓名過長，如有特殊需求請來信。';
			}

			if($Password != $rePassword)
			{
				$key = 0;
				$error_msg = '兩次密碼輸入不同，請再試一次。';
			}
			else if(preg_match('/^(?=.*\d)(?=.*[a-zA-Z]).{8,}$/', $Password) == false)
			{
				$key = 0;
				$error_msg = '密碼至少需要有一個英文字母或數字。';
			}
			else if(strlen($Password) < 8)
			{
				$key = 0;
				$error_msg = '密碼長度不足，請重新輸入！';
			}

			//確認帳號格式是否為英數組合共9碼（接收參數時已轉換為小寫字母）
			if(preg_match('/^[a-zA-Z]{1}[0-9]{8}$/', $SID) == false)
			{

				$key = 0;
				$error_msg = '學號格式有誤！';
			}


			if($key == 1)
			{
				//利用SELECT Count(*)的方式檢查是否重複
				$count = $db_tool->get_value('accounts', 'count(*)', 'sid = ? AND del_status = 0', array($SID));

				//確認無重複，將資料寫入資料庫
				if($count == 0)
				{

					$dob = $Year . '-' . $Month . '-' . $Day;
					$dob = date("Y-m-d", strtotime($dob));

					$Password = SET_PASSWORD($SID, $Password);

					$reg_time = GET_DATETIME();
					$mail_token = SET_MAILTOKEN($SID, $reg_time);

					$sql = "INSERT INTO accounts(SID, Password, Email, Name, Nickname, Gender, DeptNo, dob, mail_token, mail_sendtime, UserIp, add_on, edit_on) VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?);";
					$std = $db->prepare($sql);
					$std->execute(array($SID, $Password, $Email, $Name, $Nickname, $Gender, $Department, $dob, $mail_token, GET_DATETIME(), GET_USER_IP(), $reg_time, $reg_time));

					$sql = "UPDATE departments SET TotalUsers = TotalUsers + 1 WHERE No = ?;";
					$std = $db->prepare($sql);
					$std->execute(array($Department));

					$sql = "UPDATE m_Info SET TotalUser = TotalUser + 1;";
					$std = $db->prepare($sql);
					$std->execute();

					send_reg_message($SID, $mail_token);

					$arr = array('status' => '1', 'msg' => urlencode('註冊成功！'));
				} 

				// Count結果不為0，表示帳號已註冊過
				else
				{
					$arr = array('status' => '0', 'msg' => urlencode('此學號已註冊過！'));
				}	
			}
			else
			{
				$arr = array('status' => '0', 'msg' => urldecode($error_msg));
			}

		}
		else //有必填欄位未輸入，將頁面轉回首頁
		{
			$arr = array('status' => '0', 'msg' => urlencode('您還有資料尚未輸入，請重新檢查。'));
		}
			echo urldecode(json_encode($arr));
	}
	catch(Exception $ex)
	{
		$error = $ex -> getMessage();
	}

?>


<?php
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