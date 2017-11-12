<?php require_once "../backend/chu_tool.php"; ?>
<?php check_to_login(); ?>
<?php require_once "../templates/base.php"; ?>
<?php
	if(isset($_POST['SID']) && !empty($_POST['SID'])  && isset($_POST['Content']) && !empty($_POST['Content'])  && isset($_SESSION['SID']) && !empty($_SESSION['SID']) && isset($_SESSION['UserNo']) && !empty($_SESSION['UserNo']) && $_POST['SID'] == $_SESSION['UserNo'])
	{
		$SID = input_filter($_POST['SID']); //這個SID是UserNo
		$Content = input_filter($_POST['Content']); 

		$db_tool = new DB_Tool;
		$db = &$db_tool->get_DB();
		$count = $db_tool->get_value('accounts', 'count(*)', 'No = ? AND SID = ? AND lock_status = 0 AND del_status = 0', array($SID, $_SESSION['SID']));

		if($count == 1)
		{
			//校園交流的回報
			if(isset($_POST['PostId']) && !empty($_POST['PostId']) && is_numeric($_POST['PostId']))
			{		
				$PostId = input_filter($_POST['PostId']); 

				$count = $db_tool->get_value('post_report', 'count(*)', 'UserNo = ? AND PostId = ? AND add_on > DATE_SUB(NOW(), INTERVAL 60 MINUTE)', array($SID, $PostId));

				if($count > 2)
				{
					$arr = array('status' => 0, 'msg' => '您已經回報多次，我們將會盡快處理，請勿重複回報，謝謝');
				}
				else
				{
					$sql = "INSERT INTO post_report(UserNo, PostId, Content, add_on) VALUES(?, ?, ?, ?);";
					$std = $db->prepare($sql);
					$std->execute (array($SID, $PostId, $Content, GET_DATETIME()));
					$arr = array('status' => 1, 'msg' => '感謝您的回報，我們將會盡快處理！');
				}
			}
			//課程評論的回報
			else if(isset($_POST['GroupNo']) && !empty($_POST['GroupNo']))
			{
				$GroupNo = input_filter($_POST['GroupNo']); 

				$count = $db_tool->get_value('comment_report', 'count(*)', 'UserNo = ? AND GroupNo = ? AND add_on > DATE_SUB(NOW(), INTERVAL 60 MINUTE)', array($SID, $GroupNo));

				if($count > 2)
				{
					$arr = array('status' => 0, 'msg' => '您已經回報多次，我們將會盡快處理，請勿重複回報，謝謝');
				}
				else
				{
					$sql = "INSERT INTO comment_report(UserNo, GroupNo, Content, add_on) VALUES(?, ?, ?, ?);";
					$std = $db->prepare($sql);
					$std->execute (array($SID, $GroupNo, $Content, GET_DATETIME()));
					$arr = array('status' => 1, 'msg' => '感謝您的回報，我們將會盡快處理！');
				}
			}
			else
			{
				$arr = array('status' => 0, 'msg' => '系統發生一些錯誤，若持續發生請與我們聯絡。');
			}
		}
		else
		{
			$arr = array('status' => 0, 'msg' => '系統發生一些錯誤，若持續發生請與我們聯絡。');
		}

	}
	else
	{
		$arr = array('status' => 0, 'msg' => '系統發生一些錯誤，若持續發生請與我們聯絡。');
	}
	
	echo urldecode(json_encode($arr));
?>