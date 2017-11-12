<?php include_once "../backend/chu_tool.php"; ?>
<?php check_to_login(); ?>
<?php require_once "../templates/base.php"; ?>
<?php 
	if(!isset($_POST['SID']) || empty($_POST['SID']) || !isset($_POST['PostId']) || empty($_POST['PostId'])
		 || !isset($_POST['Content']) || !is_numeric($_POST['PostId']) || empty($_POST['Content']) || $_POST['SID'] != $_SESSION['UserNo']){
		$arr = array('status' => 0, 'msg' => urlencode("發生了一些錯誤，請與我們聯絡。"));
	}
	else{
		header("Content-Type: application/json; charset=utf-8");

		$SID = input_filter($_POST['SID']);	//這邊的SID實際上是會員流水編號(UserNo)
		$PostId = input_filter($_POST['PostId']);	//文章流水編號

		$Content = (string)	input_filter($_POST['Content']);

		$check = 1;

		$db_tool = new DB_Tool;
		$db = &$db_tool->get_DB();

		$existed = $db_tool->get_value('forum_post', 'count(*)', "No = ? AND del_status = 0;", array($PostId));

		if($existed != 1)
		{
			$check = 0;
		}

		if(mb_strlen($Content) > 2000 || mb_strlen($Content) <= 0){
			$check = 0;
		}

		if(isset($_POST['Edit']) && !empty($_POST['Edit']) && $_POST['Edit'] == 'true')
		{
			if(!isset($_POST['ResId']) || empty($_POST['ResId']) || !is_numeric($_POST['ResId']))
			{
				$check = 0;
			}
			
			if($check == 1)
			{
				$ResId = input_filter($_POST['ResId']);

				$res_info = $db_tool->get_value('post_response', 'count(*)', "No = ? AND PostId = ? AND UserNo = ? AND del_status = 0;", array($ResId, $PostId, $SID));

				if($res_info == 1)
				{
					$sql = "INSERT INTO post_response_history(PostId, UserNo, ResId, Floor, Content, UserIp, edit_on, add_on) SELECT PostId, UserNo, No, Floor, Content, UserIp, edit_on, ? FROM post_response WHERE No = ? AND PostId = ? AND UserNo = ?;";
					$std = $db->prepare($sql);
					$std->execute(array(GET_DATETIME(), $ResId, $PostId, $SID));

					$sql = "UPDATE post_response SET Content = ?, UserIp = ?, edit_on = ? WHERE No = ? AND UserNo = ? AND PostId = ?;";
					$std = $db->prepare($sql);
					$std->execute(array($Content, GET_USER_IP(), GET_DATETIME(), $ResId, $SID, $PostId));

					$arr = array('status' => 1, 'msg' => urlencode("編輯完成。"));
				}	
				else
				{
					$arr = array('status' => 0, 'msg' => urlencode("發生了一些錯誤，請與我們聯絡。"));				
				}			
			}
			else
			{
				$arr = array('status' => 0, 'msg' => urlencode("發生了一些錯誤，請與我們聯絡。"));				
			}
		}
		else
		{
			$anonymous = isset($_POST['Anonymous']) ? input_filter($_POST['Anonymous']) : '';
			$anonymous = $anonymous == "true" ? 1 : 0;

			if($check == 1)
			{
				$Floor = 0;
				$Floor = $db_tool->get_value('post_response', 'IFNULL(MAX(Floor), 0)', "PostId = ?;", array($PostId));

				$sql = "INSERT INTO post_response(PostId, UserNo, Floor, Content, Anonymous, UserIp, add_on, edit_on) VALUES(?, ?, ?, ?, ?, ?, ?, ?);";
				$std = $db->prepare($sql);
				$std->execute(array($PostId, $SID, $Floor + 1, $Content, $anonymous, GET_USER_IP(), GET_DATETIME(), GET_DATETIME()));

				$sql = "UPDATE forum_post SET TotalComment = TotalComment + 1 WHERE No = ?;";
				$std = $db->prepare($sql);
				$std->execute(array($PostId));

				$sql = "UPDATE m_Info SET TotalResponse = TotalResponse + 1;";
				$std = $db->prepare($sql);
				$std->execute(array());

				$arr = array('status' => 1, 'msg' => urlencode("發表成功。"));
			}
			else
			{
				$arr = array('status' => 0, 'msg' => urlencode("發生了一些錯誤，如果持續發生，請與我們聯絡。"));
			}
		}
	}

	echo urldecode(json_encode($arr));
?>