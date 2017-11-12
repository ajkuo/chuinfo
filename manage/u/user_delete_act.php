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

	//只有管理員(Permission >= 200)可以手動刪除會員
	if(isset($_POST['UserNo']) && !empty($_POST['UserNo']) && is_numeric($_POST['UserNo']) && $_SESSION['Permission'] >= 200)
	{
		$correct = $db_tool -> get_value('accounts', 'count(*)', "No = ? AND Password = ?", array($_SESSION['UserNo'], SET_PASSWORD($_SESSION['SID'], $_POST['AdminPassword'])));

		if($correct == 1)
		{
			$access = $db_tool -> get_value('accounts', 'SID', "No = ? AND Permission < ? AND del_status = 0", array($_POST['UserNo'], $_SESSION['Permission']));

			if(!empty($access))
			{	
				$sql = "INSERT INTO log_manage_user(UserNo, SID, Permission, Action, UserIp, UserAgent, add_on) VALUES(?, ?, ?, ?, ?, ?, ?);";
				$std = $db->prepare($sql);
				$std->execute(array($_SESSION['UserNo'], $_SESSION['SID'], $_SESSION['Permission'], "Delete User " . $_POST['UserNo'] . ".", GET_USER_IP(), GET_USER_AGENT(), GET_DATETIME()));

		        $sql = "UPDATE accounts SET del_status = 1, del_by = ?, del_on = ? WHERE No = ?;";
		        $std = $db->prepare($sql);
		        $std->execute (array($_SESSION['SID'], GET_DATETIME(), $_POST['UserNo']));

		        $sql = "UPDATE course_like SET del_status = 1, del_on = ? WHERE SID = ?;";
		        $std = $db->prepare($sql);
		        $std->execute (array(GET_DATETIME(), $access));

		        $sql = "UPDATE course_response SET del_status = 1, del_on = ?, del_by = ? WHERE UserNo = ?;";
		        $std = $db->prepare($sql);
		        $std->execute (array(GET_DATETIME(), "[del_acc] " . $_SESSION['SID'], $_POST['UserNo']));


				$sql = "UPDATE departments SET TotalUsers = TotalUsers - 1 WHERE No = (SELECT DeptNo FROM accounts WHERE No = ?);";
				$std = $db->prepare($sql);
				$std->execute(array($_POST['UserNo']));

				$sql = "UPDATE m_Info SET TotalUser = TotalUser - 1;";
				$std = $db->prepare($sql);
				$std->execute();


				$sql = "SELECT No FROM forum_post WHERE UserNo = ? AND del_status = 0;";
				$std = $db->prepare($sql);
				$std->execute(array($_POST['UserNo']));
				$posts = $std -> fetchAll(PDO::FETCH_ASSOC);

				$i = 0;

				foreach($posts as $post)
				{
					$sql = "UPDATE forum_post SET del_status = 1, del_on = ?, del_by = ? WHERE No = ?;";
					$std = $db->prepare($sql);
					$std->execute(array(GET_DATETIME(), "[del_acc] " . $_SESSION['SID'], $post['No']));

					$sql = "UPDATE forum_type SET TotalPost = TotalPost - 1 WHERE No = (SELECT Type FROM forum_post WHERE No = ?);";
					$std = $db->prepare($sql);
					$std->execute(array($post['No']));

					$sql = "UPDATE m_info SET TotalResponse = TotalResponse - (SELECT count(*) FROM post_response WHERE PostId = ? AND del_status = 0);";
					$std = $db->prepare($sql);
					$std->execute(array($post['No']));

					$i++;
				}

				$sql = "UPDATE m_info SET TotalPost = TotalPost - $i;";
				$std = $db->prepare($sql);
				$std->execute();

		        $sql = "UPDATE post_like SET del_status = 1, del_on = ? WHERE UserNo = ?;";
		        $std = $db->prepare($sql);
		        $std->execute (array(GET_DATETIME(), $_POST['UserNo']));

		        $sql = "UPDATE post_response SET del_status = 1, del_on = ?, del_by = ? WHERE UserNo = ?;";
		        $std = $db->prepare($sql);
		        $std->execute (array(GET_DATETIME(), "[del_acc] " . $_SESSION['SID'], $_POST['UserNo']));

				$result = array('status' => 1, 'msg' => '會員(' . strtoupper($access) . ')已刪除。');
			}
			else
			{
				$result = array('status' => 0, 'msg' => '該會員不存在或您的權限不足！');
			}
		} 
		else
		{
			$result = array('status' => 0, 'msg' => '您的密碼有誤。');
		}
	}
	else
	{
		$result = array('status' => 0, 'msg' => 'Error!');
	}

	echo urldecode(json_encode($result));
?>