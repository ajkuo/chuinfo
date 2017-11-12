<?php require_once "../backend/chu_tool.php"; ?>
<?php check_to_login(); ?>
<?php require_once "../templates/base.php"; ?>
<?php
	header("Content-Type: application/json; charset=utf-8");
	
	if(isset($_POST['SID']) && !empty($_POST['SID']) && isset($_POST['PostId']) && !empty($_POST['PostId']) && is_numeric($_POST['PostId']) && $_POST['SID'] == $_SESSION['UserNo'])
	{
		$db_tool = new DB_Tool;
		$db = &$db_tool->get_DB();

		$SID = input_filter(strtolower($_POST['SID']));
		$PID = input_filter($_POST['PostId']);

		$existedLike = $db_tool->get_value('post_like', 'No, PostLike', "PostId= ? AND UserNo = ? AND del_status = 0;", array($PID, $SID));

		//先檢查是不是按Like or Dislike
		if(isset($_POST['PostLike']) && !empty($_POST['PostLike']) && is_numeric($_POST['PostLike']))
		{
			$PostLike = input_filter($_POST['PostLike']);

			if($PostLike == 1 || $PostLike == 2)
			{
				//如果已經有存在的，就是收回Like or Dislike
				if(!empty($existedLike))
				{
					$LikeType = "";

					if($existedLike['PostLike'] == 1)
					{
						$LikeType = "TotalLike";
					}
					else if($existedLike['PostLike'] == 2)
					{
						$LikeType = "TotalDislike";
					}

					$sql = "UPDATE forum_post SET $LikeType = $LikeType - 1 WHERE No = ?;";
					$std = $db->prepare($sql);
					$std->execute (array($PID));

					$sql = "UPDATE post_like SET del_status = 1 WHERE No = ?;";
					$std = $db->prepare($sql);
					$std->execute (array($existedLike['No']));

					$PostLike = 0;
				}
				//沒有已經不存在的才去做增加
				else
				{
					$sql = "INSERT INTO post_like(PostId, UserNo, PostLike, add_on) VALUES(?, ?, ?, ?);";
					$std = $db->prepare($sql);
					$std->execute (array($PID, $SID, $PostLike, GET_DATETIME()));

					$LikeType = "";

					if($PostLike == 1)
					{
						$LikeType = "TotalLike";
					}
					else if($PostLike == 2)
					{
						$LikeType = "TotalDislike";
					}

					$sql = "UPDATE forum_post SET $LikeType = $LikeType + 1 WHERE No = ?;";
					$std = $db->prepare($sql);
					$std->execute (array($PID));
				}

				$Total = $db_tool->get_value('forum_post', 'TotalLike, TotalDislike', "No = ?;", array($PID));

				$result = array('PostId' => $PID, 'SID' => $SID, 'PostLike' => $PostLike, 'TotalLike' => $Total['TotalLike'], 'TotalDislike' => $Total['TotalDislike']);
			}
		}
		//如果不是按Like or Dislike，就單純回傳這個人在這篇貼文的按讚狀況
		else
		{
			$Total = $db_tool->get_value('forum_post', 'TotalLike, TotalDislike', "No = ?;", array($PID));

			if(empty($existedLike))
			{
				$result = array('PostId' => $PID, 'SID' => $SID, 'PostLike' => '0', 'TotalLike' => $Total['TotalLike'], 'TotalDislike' => $Total['TotalDislike']);
			}
			else
			{
				$result = array('PostId' => $PID, 'SID' => $SID, 'PostLike' => $existedLike['PostLike'], 'TotalLike' => $Total['TotalLike'], 'TotalDislike' => $Total['TotalDislike']);
			}
		}
	}
	else
	{
		$result = array('status' => '0', 'msg' => 'Error!');
	}
	
	echo urldecode(json_encode($result));
?>