<?php include_once "../backend/chu_tool.php"; ?>
<?php check_to_login(); ?>
<?php require_once "../templates/base.php"; ?>
<?php 
	header("Content-Type: application/json; charset=utf-8");
	//三項參數都要存在且不為空，並且會員編號必須跟SESSION的相同
	if(!isset($_POST['SID']) || empty($_POST['SID']) || !isset($_POST['PostId']) || empty($_POST['PostId'])
		 || !isset($_POST['ResId']) || empty($_POST['ResId']) || $_POST['SID'] != $_SESSION['UserNo']){
		$arr = array('status' => 0, 'msg' => urlencode("系統錯誤，請與我們聯絡。"));
	}
	else{
		$SID = input_filter($_POST['SID']);	//這邊的SID實際上是會員流水編號(UserNo)
		$PID = input_filter($_POST['PostId']);	
		$ResId = input_filter($_POST['ResId']); 

		//先檢查格式，照理說三個都是數字
		if(!is_numeric($SID) || !is_numeric($PID) || !is_numeric($ResId))
		{
			$arr = array('status' => 0, 'msg' => urlencode("參數錯誤，如有異常請與我們聯絡。"));
		}
		else
		{
			$db_tool = new DB_Tool;
			$db = &$db_tool->get_DB();

			//刪除前先確定是否有這一筆資料
			$count = $db_tool->get_value('post_response', 'count(*)', "No = ? AND PostId = ? AND UserNo = ? AND del_status = 0;", array($ResId, $PID, $SID));

			if($count == 1)
			{
				$sql = "UPDATE post_response SET del_status = 1, del_on = ?, del_by = ? WHERE No = ? AND PostId = ? AND UserNo = ?;";
				$std = $db->prepare($sql);
				$std->execute(array(GET_DATETIME(), "Deleted by itself.", $ResId, $PID, $SID));
				
				$sql = "UPDATE forum_post SET TotalComment = TotalComment - 1 WHERE No = ?;";
				$std = $db->prepare($sql);
				$std->execute(array($PID));

				$sql = "UPDATE m_info SET TotalResponse = TotalResponse - 1;";
				$std = $db->prepare($sql);
				$std->execute();
				
				$arr = array('status' => 1, 'msg' => urlencode("成功刪除。"));
			}
			else
			{
				$arr = array('status' => 0, 'msg' => urlencode("發生了一些錯誤，請與我們聯絡。"));
			}

		}
	}

	echo urldecode(json_encode($arr));
?>