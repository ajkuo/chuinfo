<?php include_once "../backend/chu_tool.php"; ?>
<?php check_to_login(); ?>
<?php require_once "../templates/base.php"; ?>
<?php 
	if(!isset($_POST['SID']) || empty($_POST['SID']) || !isset($_POST['CID']) || empty($_POST['CID'])
		 || !isset($_POST['Content']) || empty($_POST['Content']) || $_POST['SID'] != $_SESSION['UserNo']){
		$arr = array('status' => 0, 'msg' => urlencode("發生了一些錯誤，請與我們聯絡。"));
	}
	else{
		header("Content-Type: application/json; charset=utf-8");
		$SID = input_filter($_POST['SID']);	//這邊的SID實際上是會員流水編號(UserNo)
		$CID = input_filter($_POST['CID']);	//這邊的CID實際上是GroupNo

		$Content = (string)	input_filter($_POST['Content']);

		$check = 1;

		if(mb_strlen($Content) > 2000 || mb_strlen($Content) <= 0){
			$check = 0;
		}

		if(isset($_POST['Edit']) && !empty($_POST['Edit']) && isset($_POST['PostId']) && !empty($_POST['PostId']) && is_numeric($_POST['PostId']) && $_POST['Edit'] == 'true')
		{
			$PostId = input_filter($_POST['PostId']);	//這邊的PostId實際上是文章流水編號(No)

			$db_tool = new DB_Tool;
			$db = &$db_tool->get_DB();

			$EditFloor = $db_tool->get_value('course_response', 'IFNULL(Floor, 0)', "No = ? AND UserNo = ? AND GroupNo = ? AND del_status = 0;", array($PostId, $SID, $CID));

			if($EditFloor != 0)
			{
				$sql = "INSERT INTO course_response_history(GroupNo, UserNo, ResId, Floor, Content, UserIp, edit_on, add_on) SELECT GroupNo, UserNo, No, Floor, Content, UserIp, edit_on, ? FROM course_response WHERE No = ? AND GroupNo = ? AND UserNo = ?;";
				$std = $db->prepare($sql);
				$std->execute(array(GET_DATETIME(), $PostId, $CID, $SID));

				$sql = "UPDATE course_response SET Content = ?, UserIp = ?, edit_on = ? WHERE No = ? AND UserNo = ? AND GroupNo = ?;";
				$std = $db->prepare($sql);
				$std->execute(array($Content, GET_USER_IP(), GET_DATETIME(), $PostId, $SID, $CID));

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

			if($check == 1)
			{

				$db_tool = new DB_Tool;
				$db = &$db_tool->get_DB();

				$Floor = 0;
				$Floor = $db_tool->get_value('course_response', 'IFNULL(MAX(Floor), 0)', "GroupNo = ?;", array($CID));

				$sql = "INSERT INTO course_response(GroupNo, UserNo, Floor, Content, Anonymous, UserIp, add_on, edit_on) VALUES(?, ?, ?, ?, ?, ?, ?, ?);";
				$std = $db->prepare($sql);
				$std->execute(array($CID, $SID, $Floor + 1, $Content, $anonymous, GET_USER_IP(), GET_DATETIME(), GET_DATETIME()));

				$sql = "UPDATE course_group SET TotalComment = TotalComment + 1 WHERE No = ?;";
				$std = $db->prepare($sql);
				$std->execute(array($CID));

				$sql = "UPDATE m_Info SET TotalComment = TotalComment + 1;";
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