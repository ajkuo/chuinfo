<?php include_once "../backend/chu_tool.php"; ?>
<?php check_to_login(); ?>
<?php require_once "../templates/base.php"; ?>
<?php 
	header("Content-Type: application/json; charset=utf-8");
	if(!isset($_POST['SID']) || empty($_POST['SID']) || !isset($_POST['PostId']) || empty($_POST['PostId'])
		|| $_POST['SID'] != $_SESSION['UserNo'] || !isset($_SESSION['Permission']) || empty($_SESSION['Permission']) || !is_numeric($_SESSION['Permission']) || $_SESSION['Permission'] < 100){
		$arr = array('status' => 0, 'msg' => urlencode("系統錯誤，請與我們聯絡。"));
	}
	else{
		$SID = input_filter($_POST['SID']);	//這邊的SID實際上是會員流水編號(UserNo)
		$PID = input_filter($_POST['PostId']); 

		if(!is_numeric($SID))
		{
			$arr = array('status' => 0, 'msg' => urlencode("參數錯誤，如有異常請與我們聯絡。"));
		}
		else
		{
			$db_tool = new DB_Tool;
			$db = &$db_tool->get_DB();

			//先確定是否有這位站務人員
			$count = $db_tool->get_value('accounts', 'count(*)', "No = ? AND Permission >= 100 AND del_status = 0;", array($SID));

			if($count == 1)
			{	
				if(is_array($PID))
				{
					$PID = implode(",", $PID);
				}

				$sql = "INSERT INTO log_manage_post(UserNo, SID, Permission, Action, UserIp, UserAgent, add_on) VALUES(?, ?, ?, ?, ?, ?, ?);";
				$std = $db->prepare($sql);
				$std->execute(array($_SESSION['UserNo'], $_SESSION['SID'], $_SESSION['Permission'], "Delete Forum Post(s): " . $PID, GET_USER_IP(),GET_USER_AGENT(), GET_DATETIME()));

				$sql = "UPDATE forum_post SET del_status = 1, del_on = ?, del_by = ? WHERE No IN ($PID);";
				$std = $db->prepare($sql);
				$std->execute(array(GET_DATETIME(), "Del by " . $_SESSION['SID'] . "."));

				$sql = "UPDATE m_info SET TotalPost = TotalPost - (SELECT count(*) FROM forum_post WHERE No IN ($PID));";
				$std = $db->prepare($sql);
				$std->execute();

				$sql = "UPDATE m_info SET TotalResponse = TotalResponse - (SELECT count(*) FROM post_response WHERE PostId IN ($PID) AND del_status = 0);";
				$std = $db->prepare($sql);
				$std->execute();

				$arr = array('status' => 1, 'msg' => urlencode($PID));
			}
			else
			{
				$arr = array('status' => 0, 'msg' => urlencode("發生了一些錯誤，請與我們聯絡。"));
			}

		}
	}

	echo urldecode(json_encode($arr));
?>