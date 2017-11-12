<?php include_once "../backend/chu_tool.php"; ?>
<?php check_to_login(); ?>
<?php require_once "../templates/base.php"; ?>
<?php 
	if(!isset($_POST['SID']) || empty($_POST['SID']) || !isset($_POST['Type']) || empty($_POST['Type']) || !is_numeric($_POST['Type']) || !isset($_POST['Title']) || empty($_POST['Title']) || !isset($_POST['Content']) || empty($_POST['Content']) || $_POST['SID'] != $_SESSION['UserNo']){
		$arr = array('status' => 0, 'msg' => urlencode("發生了一些錯誤，請與我們聯絡。"));
	}
	else{
		header('Content-Type:text/html; charset=utf-8');
		$SID = input_filter($_POST['SID']);	//這邊的SID實際上是會員流水編號(UserNo)
		$Type = input_filter($_POST['Type']);
		$Title = (string) input_filter($_POST['Title']);
		$Content = (string)	input_filter($_POST['Content']);

		$check = 1;

		if(mb_strlen($Title, "utf-8") > 25 || mb_strlen($Title, "utf-8") <= 0){
			$check = 0;
		}
		if(mb_strlen($Content, "utf-8") > 12000 || mb_strlen($Content, "utf-8") <= 0){
			$check = 0;
		}

		if(isset($_POST['Edit']) && !empty($_POST['Edit']) && isset($_POST['PostId']) && !empty($_POST['PostId']) && is_numeric($_POST['PostId']) && $_POST['Edit'] == 'true')
		{
			$PostId = input_filter($_POST['PostId']);	//這邊的PostId實際上是文章流水編號(No)

			$db_tool = new DB_Tool;
			$db = &$db_tool->get_DB();

			$post_info = $db_tool->get_value('forum_post', 'count(*)', "No = ? AND UserNo = ? AND del_status = 0;", array($PostId, $SID));

			if($post_info == 1)
			{
				$sql = "INSERT INTO forum_post_history(PostId, UserNo, Type, Title, Content, UserIp, edit_on, add_on) SELECT No, UserNo, Type, Title, Content, UserIp, edit_on, ? FROM forum_post WHERE No = ?;";
				$std = $db->prepare($sql);
				$std->execute(array(GET_DATETIME(), $PostId));

				$sql = "UPDATE forum_post SET Type = ?, Title = ?, Content = ?, UserIp = ?, edit_on = ? WHERE No = ? AND UserNo = ?;";
				$std = $db->prepare($sql);
				$std->execute(array($Type, $Title, $Content, GET_USER_IP(), GET_DATETIME(), $PostId, $SID));
				
				$arr = array('status' => 1, 'msg' => urlencode("編輯完成。"));
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

			$db_tool = new DB_Tool;
			$db = &$db_tool->get_DB();
			
			$type_exist = 0;
			$type_exist = $db_tool->get_value('forum_type', 'count(*)', 'No = ? AND PublicPermission <= ?', array($Type, $_SESSION['Permission']));

			if($type_exist != 1)
			{
				$check = 2;
			}

			if($check == 1)
			{
				$sql = "INSERT INTO forum_post(UserNo, Type, Title, Content, Anonymous, UserIp, add_on, edit_on) VALUES(?, ?, ?, ?, ?, ?, ?, ?);";
				$std = $db->prepare($sql);
				$std->execute(array($SID, $Type, $Title, $Content, $anonymous, GET_USER_IP(), GET_DATETIME(), GET_DATETIME()));

				$sql = "UPDATE forum_type SET TotalPost = TotalPost + 1 WHERE No = ?;";
				$std = $db->prepare($sql);
				$std->execute(array($Type));

				$sql = "UPDATE m_Info SET TotalPost = TotalPost + 1;";
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