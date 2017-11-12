<?php require_once "../backend/chu_tool.php"; ?>
<?php check_to_login(); ?>
<?php require_once "../templates/base.php"; ?>
<?php
	header("Content-Type: application/json; charset=utf-8");

	$SID = isset($_POST['SID']) ? input_filter(strtolower($_POST['SID'])) : '';
	$Keyword = isset($_POST['Keyword']) ? input_filter($_POST['Keyword']) : '';
	$DeptNo = isset($_POST['DeptNo']) ? input_filter($_POST['DeptNo']) : '';
	$Type = isset($_POST['Type']) ? input_filter($_POST['Type']) : '';
	$Day = isset($_POST['Day']) ? input_filter($_POST['Day']) : '';
	$SearchType = isset($_POST['SearchType']) ? input_filter($_POST['SearchType']) : '';
	/* 用 $SearchType 來判斷搜尋類型，分別有：系別(不分類/日期)、系別(詳細分類/日期)、關鍵字搜尋
	 *
	 * 0 - 不能使用"0"，會被php認為是"false"
	 * 1 - 選單內容搜尋 (例如：通識教育中心、不限類型、可指定日期)
	 * 2 - 關鍵字搜尋 (不抓系別、分類及日期，直接用關鍵字搜尋全部課程)
	 *
	 */

	$Cur_Year = 105; //設定搜尋的學年度
	$Cur_Term = 1;   //設定搜尋的學期

	$result = array();

	$check = true;

	// 進行所有動作前，先進行各項參數的基本檢查
	if(strlen($Keyword) > 35){
		$check = false;
	}else if(!empty($DeptNo)){
		if(!is_numeric($DeptNo)){
			$check = false;
		}
		else if($DeptNo == 0){
			$check = false;
		}
	}else if(!empty($Type) && strlen($Type) > 5){
		$check = false;
	}else if(!empty($Day) && strlen($Day) > 5){
		$check = false;
	}else if(strlen($SID) > 9){
		$check = false;
	}else if(preg_match('/^[a-zA-Z]{1}[0-9]{8}$/', $SID) == false){
		$check = false;
	}else if($SID != $_SESSION['SID']){
		$check = false;
	}else if(!is_numeric($SearchType) || $SearchType == 0){
		$check = false;
	}

	if(!empty($SID) && !empty($SearchType) && $check)
	{
		$db_tool = new DB_Tool;
		$db = &$db_tool->get_DB();	

		if(empty($DeptNo) && empty($Type) && empty($Day)){
			course_search_log($SID, 0, 'all', 'all', $Keyword);
		}else if($SearchType != 99){
			course_search_log($SID, $DeptNo, $Type, $Day, $Keyword);
		}

		//修正搜尋選項的預設值
		$Type = $Type == 'all' ? '%%' : '%' . $Type . '%';
		$Day = $Day == 'all' ? '%%' : '%' . $Day . '%';

		switch ($SearchType) {
			case 1:
			case 99:
				if(!empty($DeptNo))
				{
					$sql = "SELECT c.No as No, d.Name as dname, c.Name as cname, c.Teacher as teacher, c.AverageScore as AvgScore, c.TotalLike as TotalLike, c.TotalComment as TotalComment FROM departments d, course_group c, course_history ch WHERE d.No = c.DeptNo AND d.No = ? AND c.No = ch.GroupNo AND c.Teacher != '' AND c.del_status = 0 AND ch.Year = ? AND ch.Term = ? AND c.Type LIKE ? AND ch.ClassTime LIKE ? GROUP BY c.No";
					$std = $db->prepare($sql);
					$std->execute(array($DeptNo, $CURRENT_STD_YEAR, $CURRENT_STD_TERM, $Type, $Day));
					$rs = $std -> fetchAll(PDO::FETCH_ASSOC);
				}
				break;

			case 2:
				if(!empty($Keyword))
				{
					$Keyword = '%' . $Keyword . '%';
					$sql = "SELECT c.No as No, d.Name as dname, c.Name as cname, c.Teacher as teacher, c.AverageScore as AvgScore, c.TotalLike as TotalLike, c.TotalComment as TotalComment FROM departments d, course_group c WHERE d.No = c.DeptNo AND c.Teacher != '' AND c.del_status = 0 AND (c.Name LIKE ? OR c.Teacher LIKE ?);";
					$std = $db->prepare($sql);
					$std->execute(array($Keyword, $Keyword));
					$rs = $std -> fetchAll(PDO::FETCH_ASSOC);
				}
				break;

			default:
				$sql = "SELECT c.No as No, d.Name as dname, c.Name as cname, c.Teacher as teacher, c.AverageScore as AvgScore, c.TotalLike as TotalLike, c.TotalComment as TotalComment FROM departments d, course_group c WHERE d.No = c.DeptNo AND d.No = 33 AND c.Teacher != '' AND c.del_status = 0;";
				$std = $db->prepare($sql);
				$std->execute();
				$rs = $std -> fetchAll(PDO::FETCH_ASSOC);
				break;
		}

			
		$i = 0;

		foreach($rs as $data)
		{
			$courses = array(
				'CID' => urlencode($data['No']),
				'DeptName' => urlencode($data['dname']),
				'Name' => urlencode($data['cname']),
				'Teacher' => urlencode($data['teacher']),
				'AvgScore' => urlencode($data['AvgScore']),
				'TotalLike' => urlencode($data['TotalLike']),
				'TotalComment' => urlencode($data['TotalComment'])
			);
			$result[$i] = $courses;
			$i++;
		}
	
		echo urldecode(json_encode($result));	
	}
	else
	{
		turn_to_url('/c/course_list.php');
	}
?>