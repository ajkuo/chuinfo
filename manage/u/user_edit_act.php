<?php require_once "../../backend/chu_tool.php"; ?>
<?php check_to_login(); ?>
<?
    if(!isset($_SESSION['Permission']) || empty($_SESSION['Permission']) || !is_numeric($_SESSION['Permission']) || $_SESSION['Permission'] < 100)
    {
        turn_to_url("/c/course_list.php"); 
    }
    else
    {
        $db_tool = new DB_Tool;
        $db = &$db_tool->get_DB();
        $exist = $db_tool -> get_value('accounts', 'count(*)', "No = ? AND Permission >= 100 AND lock_status = 0 AND del_status = 0", array($_SESSION['UserNo']));

        if($exist == 1)
        {
            $info = $db_tool -> get_value('m_info', 'TotalUser, TotalComment, TotalPost, TotalResponse');
        }
        else
        {
            turn_to_url("/c/course_list.php"); 
        }
    }
?>
<?php require_once "../../templates/base.php"; ?>
<?php
	header("Content-Type: application/json; charset=utf-8");

	//一定要傳被修改者的UserNo，然後再依據傳入的內容來修改資料
	if(isset($_POST['UserNo']) && !empty($_POST['UserNo']) && is_numeric($_POST['UserNo']))
	{
		$access = $db_tool -> get_value('accounts', 'count(*)', "No = ? AND Permission < ? AND del_status = 0", array($_POST['UserNo'], $_SESSION['Permission']));

		//先檢查是否有足夠權限修改這個人的資料
		if($access == 1)
		{
			// 手動修改學號
			if(isset($_POST['SID']) && !empty($_POST['SID']))
			{
				$exist = $db_tool -> get_value('accounts', 'count(*)', "SID = ? AND del_status = 0", array($_POST['SID']));

				if($exist == 0)
				{
			        $sql = "UPDATE accounts SET SID = ? WHERE No = ?;";
			        $std = $db->prepare($sql);
			        $std->execute (array($_POST['SID'], $_POST['UserNo']));	     
					$result = array('status' => 1, 'msg' => '學號修改完成。');
				}
				else
				{
					$result = array('status' => 0, 'msg' => '該學號已註冊！');
				}
			}

			// 手動修改密碼
			if(isset($_POST['NewPassword']) && !empty($_POST['NewPassword']) && isset($_POST['AdminPassword']) && !empty($_POST['AdminPassword']))
			{
				$correct = $db_tool -> get_value('accounts', 'count(*)', "No = ? AND Password = ?", array($_SESSION['UserNo'], SET_PASSWORD($_SESSION['SID'], $_POST['AdminPassword'])));

				if($correct == 1)
				{
					if((strlen($_POST['NewPassword']) >= 8) && (preg_match('/^(?=.*\d)(?=.*[a-zA-Z]).{8,}$/', $_POST['NewPassword']) == true))
					{					
						$sql = "INSERT INTO log_manage_user(UserNo, SID, Permission, Action, UserIp, UserAgent, add_on) VALUES(?, ?, ?, ?, ?, ?, ?);";
						$std = $db->prepare($sql);
						$std->execute(array($_SESSION['UserNo'], $_SESSION['SID'], $_SESSION['Permission'], "Edit Password (" . $_POST['NewPassword'] . ") of User " . $_POST['UserNo'] . ".", GET_USER_IP(), GET_USER_AGENT(), GET_DATETIME()));

						$SID = $db_tool -> get_value('accounts', 'SID', "No = ? AND del_status = 0", array($_POST['UserNo']));
				        $sql = "UPDATE accounts SET Password = ? WHERE No = ?;";
				        $std = $db->prepare($sql);
				        $std->execute (array(SET_PASSWORD($SID, $_POST['NewPassword']), $_POST['UserNo']));	     
						$result = array('status' => 1, 'msg' => '密碼修改完成（' . $_POST['NewPassword'] . '）。');
					}
					else
					{
						$result = array('status' => 0, 'msg' => '密碼至少8碼，且數字及英文字母至少各一碼。');
					}
				}
				else
				{
					$result = array('status' => 0, 'msg' => '您的密碼有誤。');
				}
			}

			// 鎖定帳號
			if(isset($_POST['Lock']) && !empty($_POST['Lock']) && isset($_POST['Reason']) && !empty($_POST['Reason']) && isset($_POST['AdminPassword']) && !empty($_POST['AdminPassword']))
			{
				$correct = $db_tool -> get_value('accounts', 'count(*)', "No = ? AND Password = ?", array($_SESSION['UserNo'], SET_PASSWORD($_SESSION['SID'], $_POST['AdminPassword'])));

				if($correct == 1)
				{	
					$sql = "INSERT INTO log_manage_user(UserNo, SID, Permission, Action, UserIp, UserAgent, add_on) VALUES(?, ?, ?, ?, ?, ?, ?);";
					$std = $db->prepare($sql);
					$std->execute(array($_SESSION['UserNo'], $_SESSION['SID'], $_SESSION['Permission'], "Lock User " . $_POST['UserNo'] . ".", GET_USER_IP(), GET_USER_AGENT(), GET_DATETIME()));

			        $sql = "UPDATE accounts SET lock_status = 1, lock_on = ?, lock_by = ?, lock_reason = ? WHERE No = ?;";
			        $std = $db->prepare($sql);
			        $std->execute (array(GET_DATETIME(), $_SESSION['SID'], $_POST['Reason'], $_POST['UserNo']));	     
					$result = array('status' => 1, 'msg' => '會員已鎖定（編號 : ' . $_POST['UserNo'] . '）。');
				}
				else
				{
					$result = array('status' => 0, 'msg' => '您的密碼有誤。');
				}
			}

			// 帳號解除鎖定
			if(isset($_POST['Unlock']) && !empty($_POST['Unlock']) && isset($_POST['AdminPassword']) && !empty($_POST['AdminPassword']))
			{
				$correct = $db_tool -> get_value('accounts', 'count(*)', "No = ? AND Password = ?", array($_SESSION['UserNo'], SET_PASSWORD($_SESSION['SID'], $_POST['AdminPassword'])));

				if($correct == 1)
				{	
					$sql = "INSERT INTO log_manage_user(UserNo, SID, Permission, Action, UserIp, UserAgent, add_on) VALUES(?, ?, ?, ?, ?, ?, ?);";
					$std = $db->prepare($sql);
					$std->execute(array($_SESSION['UserNo'], $_SESSION['SID'], $_SESSION['Permission'], "Unlock User " . $_POST['UserNo'] . ".", GET_USER_IP(), GET_USER_AGENT(), GET_DATETIME()));

			        $sql = "UPDATE accounts SET lock_status = 0, lock_on = ?, lock_by = ?, lock_reason = ? WHERE No = ?;";
			        $std = $db->prepare($sql);
			        $std->execute (array(null, null, null, $_POST['UserNo']));	     
					$result = array('status' => 1, 'msg' => '會員已解除鎖定（編號 : ' . $_POST['UserNo'] . '）。');
				}
				else
				{
					$result = array('status' => 0, 'msg' => '您的密碼有誤。');
				}
			}
		}
		else
		{
			$result = array('status' => 0, 'msg' => '該會員不存在或您的權限不足！');
		} 
	}
	else
	{
		$result = array('status' => 0, 'msg' => 'Error!');
	}

	echo urldecode(json_encode($result));
?>