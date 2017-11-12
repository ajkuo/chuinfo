<?php require_once "../backend/chu_tool.php"; ?>
<?php check_to_login(); ?>
<?php require_once "../templates/base.php"; ?>
<?php
	
	if(isset($_POST['SID']) && !empty($_POST['SID']) && isset($_POST['CID']) && !empty($_POST['CID']) && is_numeric($_POST['CID']) && $_POST['CID'] != 0 && $_POST['SID'] == $_SESSION['SID'])
	{
		$db_tool = new DB_Tool;
		$db = &$db_tool->get_DB();

		$SID = input_filter(strtolower($_POST['SID']));
		$CID = input_filter($_POST['CID']);

		$count = $db_tool->get_value('course_like', 'count(*)', "sid = ? AND GroupNo = ? AND del_status = 0;", array($SID, $CID));

		$Liked = isset($_POST['Liked']) ? $_POST['Liked'] : -1;

		// 收回推薦
		if($Liked == 0 && $count != 0){
			$sql = "UPDATE course_like SET del_status = 1 WHERE sid = ? AND GroupNo = ? AND del_status =0;";
			$std = $db->prepare($sql);
			$std->execute (array($SID, $CID));

			$sql = "UPDATE course_group SET TotalLike = TotalLike - 1 WHERE No = ?;";
			$std = $db->prepare($sql);
			$std->execute (array($CID));
		}
		// 加上推薦
		else if($Liked == 1 && $count == 0){
			$sql = "INSERT INTO course_like(SID, GroupNo, add_on) VALUES(?, ?, ?);";
			$std = $db->prepare($sql);
			$std->execute (array($SID, $CID, GET_DATETIME()));

			$sql = "UPDATE course_group SET TotalLike = TotalLike + 1 WHERE No = ?;";
			$std = $db->prepare($sql);
			$std->execute (array($CID));
		}else{
			turn_to_url('/c/course_list.php');
		}
	}
	else
	{
		turn_to_url('/c/course_list.php');
	}
?>