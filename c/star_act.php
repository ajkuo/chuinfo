<?php include_once "../backend/chu_tool.php"; ?>
<?php check_to_login(); ?>
<?php require_once "../templates/base.php"; ?>
<?php 

	/* 這隻程式最下方更新平均分數的部分可能有效能問題，可以再調整程式寫法 */


	//先檢查各項參數是否存在並有值，且會員編號與SESSION內的會員編號相同
	if(!isset($_POST['SID']) || empty($_POST['SID']) || !isset($_POST['CID']) || empty($_POST['CID'])
		 || !isset($_POST['Type']) || empty($_POST['Type']) || !isset($_POST['Value']) || empty($_POST['Value']) || !is_numeric($_POST['Value']) || $_POST['SID'] != $_SESSION['UserNo']){
			turn_to_url('/c/course_list.php');
	}
	else{
		$SID = input_filter($_POST['SID']);	//這邊的SID實際上是會員流水編號(UserNo)
		$CID = input_filter($_POST['CID']);	//這邊的CID實際上是GroupNo
		$Type = input_filter($_POST['Type']);	//這是評分類別(共分四類: 教師向度(st), 課程向度(sc), 分數向度(ss), 推薦向度(sr))
		$Value = input_filter($_POST['Value']) * 5;	//Value分別有:0.2, 0.4, 0.6, 0.8, 1，分別代表1~5顆星，存入時x5，得到1, 2, 3, 4, 5

		if(!is_numeric($SID) || !is_numeric($CID) || !is_numeric($Value))
		{
			turn_to_url('/c/course_list.php');
		}
		else
		{
			switch ($Type) {
				case 'st':
					$Type = "TeacherScore";
					$CountType = "TeacherCount";
					break;
				case 'sc':
					$Type = "CourseScore";
					$CountType = "CourseCount";
					break;
				case 'ss':
					$Type = "GradeScore";
					$CountType = "GradeCount";
					break;
				case 'sr':
					$Type = "RecommendScore";
					$CountType = "RecommendCount";
					break;
				default:
					turn_to_url('/c/course_list.php');
					break;
			}

			$db_tool = new DB_Tool;
			$db = &$db_tool->get_DB();

			//先檢查之前是不是已經有評分紀錄
			$sql = "SELECT No, $Type FROM `course_score` WHERE `UserNo` = ? AND `GroupNo` = ? AND del_status = 0 AND $Type != 0;";
			$std = $db->prepare($sql);
			$std->execute (array($SID, $CID));
			$rc = $std -> rowCount();
			$data = $std -> fetch(PDO::FETCH_ASSOC);

			//如果之前沒評分紀錄，表示為第一次評分
			if($rc == 0)
			{
				$sql = "INSERT INTO course_score(GroupNo, UserNo, $Type, referer, add_on) VALUES(?, ?, ?, ?, ?);";
				$std = $db->prepare($sql);
				$std->execute(array($CID, $SID, $Value, GET_USER_REFERER(), GET_DATETIME()));

				$sql = "UPDATE course_group SET $CountType = $CountType + 1 WHERE No = ?; ";
				$std = $db->prepare($sql);
				$std->execute(array($CID));
			}
			//不是第一次評分的話，要先把之前的紀錄刪除再新增紀錄
			else
			{
				if($data["$Type"] != $Value)
				{
					$ScoreNo = $data['No'];
					$sql = "UPDATE course_score SET del_status = 1, del_on = ?, del_by = ? WHERE No = $ScoreNo;";
					$std = $db->prepare($sql);
					$std->execute(array(GET_DATETIME(), 'Add new scores.'));

					$sql = "INSERT INTO course_score(GroupNo, UserNo, $Type, referer, add_on) VALUES(?, ?, ?, ?, ?);";
					$std = $db->prepare($sql);
					$std->execute(array($CID, $SID, $Value, GET_USER_REFERER(), GET_DATETIME()));
				}
			}

			//最後要回頭把course_group上面的分數更新為該向度最新的平均分數
			$sql = "UPDATE course_group SET $Type = (SELECT IFNULL(AVG($Type), 0)  FROM course_score WHERE GroupNo = ? AND del_status = 0 AND $Type > 0) WHERE No = ?;";
			$std = $db->prepare($sql);
			$std->execute(array($CID, $CID));

			//再計算最新的總平均分數(取四大向度不為0的進行平均)
			$sql = "SELECT TeacherScore, CourseScore, GradeScore, RecommendScore FROM course_group WHERE No = ?;";
			$std = $db->prepare($sql);
			$std->execute(array($CID));
			$scoreData = $std -> fetchAll(PDO::FETCH_ASSOC);

			$st = $scoreData[0]['TeacherScore'];
			$sc = $scoreData[0]['CourseScore'];
			$sg = $scoreData[0]['GradeScore'];
			$sr = $scoreData[0]['RecommendScore'];
			$count = 0;

			if($st > 0) $count++;
			if($sc > 0) $count++;
			if($sg > 0) $count++;
			if($sr > 0) $count++;

			if($count > 0) 
				$avg_sc = round(($st + $sc + $sg + $sr)/$count, 1);
			else 
				$avg_sc = 0;

			$sql = "UPDATE course_group SET AverageScore = ? WHERE No = ?;";
			$std = $db->prepare($sql);
			$std->execute(array($avg_sc, $CID));
		}
	}
?>