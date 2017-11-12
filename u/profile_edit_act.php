<?php require_once "../backend/chu_tool.php"; ?>
<?php check_to_login(); ?>
<?php require_once "../templates/base.php"; ?>
<?php
	if(isset($_POST['SID']) && !empty($_POST['SID']) && isset($_POST['Nickname']) && !empty($_POST['Nickname']) && isset($_SESSION['UserNo']) && !empty($_SESSION['UserNo']) && isset($_SESSION['SID']) && $_POST['SID'] == $_SESSION['SID'])
	{
		$SID = input_filter($_POST['SID']); //這個SID是學號
		$Nickname = (string) input_filter($_POST['Nickname']); //這個SID是學號
		$Email = (string) isset($_POST['Email']) ? input_filter($_POST['Email']) : '';

		$db_tool = new DB_Tool;
		$db = &$db_tool->get_DB();
		$count = $db_tool->get_value('accounts', 'count(*)', 'No = ? AND SID = ? AND lock_status = 0 AND del_status = 0', array($_SESSION['UserNo'], $SID));

		if($count == 1)
		{
			$sql = "INSERT INTO accounts_history(UserNo, DeptNo, SID, Name, Nickname, Gender, dob, Email, UserIp, edit_on, add_on) SELECT UserNo, DeptNo, SID, Name, Nickname, Gender, dob, Email, UserIp, edit_on, NOW() FROM accounts WHERE No = ? AND SID = ?;";
			$std = $db->prepare($sql);
			$std->execute (array($_SESSION['UserNo'], $SID));

			//看有沒有要修改會員密碼
			if(isset($_POST['CurPassword']) && !empty($_POST['CurPassword']) && isset($_POST['NewPassword']) && !empty($_POST['NewPassword']))
			{	
				$CurPassword = input_filter($_POST['CurPassword']);
				$NewPassword = input_filter($_POST['NewPassword']);

				$match = $db_tool->get_value('accounts', 'count(*)', 'No = ? AND SID = ? AND Password = ?', array($_SESSION['UserNo'], $SID, SET_PASSWORD($SID, $CurPassword)));

				if($match == 1)
				{
					//先將密碼修改行為紀錄到資料表中
					$sql = "INSERT INTO accounts_pw_history(UserNo, SID, reason, UserIp, add_on) VALUES(?, ?, ?, ?, ?)";
					$std = $db->prepare($sql);
					$std->execute (array($_SESSION['UserNo'], $SID, "Change by itself.", GET_USER_IP(), GET_DATETIME()));

					$sql = "INSERT INTO accounts_history(UserNo, SID, DeptNo, Name, Nickname, Gender, dob, UserIp, edit_on, add_on) SELECT No, SID, DeptNo, Name, Nickname, Gender, dob, UserIp, edit_on, ? FROM accounts WHERE No = ? AND SID = ?;";
					$std = $db->prepare($sql);
					$std->execute (array(GET_DATETIME(), $_SESSION['UserNo'], $SID));

					//再更新會員原表
					$sql = "UPDATE accounts SET Password = ?, Nickname = ?, Email = ?, UserIp = ?, edit_on = ? WHERE No = ? AND SID = ?;";
					$std = $db->prepare($sql);
					$std->execute (array(SET_PASSWORD($SID, $NewPassword), $Nickname, $Email, GET_USER_IP(), GET_DATETIME(), $_SESSION['UserNo'], $SID));

					$arr = array('status' => 1, 'msg' => '會員資料及密碼修改完成。');
				}
				else
				{
					$arr = array('status' => 0, 'msg' => '密碼輸入錯誤，請再試一次。');
				}
			}
			else
			{
				$sql = "INSERT INTO accounts_history(UserNo, SID, DeptNo, Name, Nickname, Gender, dob, UserIp, edit_on, add_on) SELECT No, SID, DeptNo, Name, Nickname, Gender, dob, UserIp, edit_on, ? FROM accounts WHERE No = ? AND SID = ?;";
				$std = $db->prepare($sql);
				$std->execute (array(GET_DATETIME(), $_SESSION['UserNo'], $SID));

				$sql = "UPDATE accounts SET Nickname = ?, Email = ?, UserIp = ?, edit_on = ? WHERE No = ? AND SID = ?;";
				$std = $db->prepare($sql);
				$std->execute (array($Nickname, $Email, GET_USER_IP(), GET_DATETIME(), $_SESSION['UserNo'], $SID));

				$arr = array('status' => 1, 'msg' => '會員資料已更新。');
			}
		}
		else
		{
			$arr = array('status' => 0, 'msg' => '系統發生一些錯誤，請重新登入，若持續發生請與我們聯絡。');
		}

		echo urldecode(json_encode($arr));
	}
	else
	{
		turn_to_url("/u/profile.php");
	}
?>