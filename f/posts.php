<?php include_once "../backend/chu_tool.php"; ?>
<?php check_to_login(); ?>
<?php require_once "../templates/base.php"; ?>
<?php
	header("Content-Type: application/json; charset=utf-8");

	$Type = isset($_GET['t']) ?  input_filter($_GET['t']) : 'all';
	$sortby = isset($_GET['latest']) ? input_filter($_GET['latest']) : "p.Sticky DESC, p.TotalLike DESC, p.TotalDislike DESC, p.TotalComment DESC, p.No DESC";
	$sortby = $sortby == 'true' ? "p.Sticky DESC, p.Sort ASC, p.No DESC" : "p.Sticky DESC, p.Sort ASC, p.TotalLike DESC, p.TotalDislike DESC, p.TotalComment DESC, p.No DESC";
	$SN = isset($_GET['SN']) ?  input_filter($_GET['SN']) : 30;

	if(!empty($Type))
	{
		$db_tool = new DB_Tool;
		$db = &$db_tool->get_DB();
		$type_exist = $db_tool->get_value('forum_type', 'count(*)', 'Code = ?', array($Type));

		$start_index = ($SN - 30);

		$result = array();

		if($type_exist == 1)
		{
			if($Type == "all")
			{
				//這邊是「全部」分類，置頂(Sticky = 1)代表分類置頂，因此不顯示在此區，另外Type NOT IN () 裡面設定不顯示在這區的有
				//「靠北」、「廢文」兩大類

				$sql = "SELECT p.No as PostId, a.Permission as Permission, a.Gender as Gender, d.Name as DeptName, p.Type as Type, p.Title as Title, p.TotalLike as TotalLike, p.TotalDislike as TotalDislike, p.TotalComment as TotalComment, p.Sticky as Sticky, p.Sort as SortIndex, p.Anonymous as Anonymous, p.add_on as PostTime FROM forum_post p, accounts a, departments d WHERE p.UserNo = a.No AND a.DeptNo = d.No AND p.del_status = 0 AND a.del_status = 0 AND p.Sticky != 1 AND p.Type NOT IN (3) ORDER BY $sortby LIMIT $start_index,30;";
				$std = $db->prepare($sql);
				$std->execute ();
			}
			else
			{
				$sql = "SELECT p.No as PostId, a.Permission as Permission, a.Gender as Gender, d.Name as DeptName, p.Type as Type, p.Title as Title, p.TotalLike as TotalLike, p.TotalDislike as TotalDislike, p.TotalComment as TotalComment, p.Sticky as Sticky, p.Sort as SortIndex, p.Anonymous as Anonymous, p.add_on as PostTime FROM forum_post p, accounts a, departments d, forum_type pt WHERE p.UserNo = a.No AND a.DeptNo = d.No AND p.Type = pt.No AND pt.del_status = 0 AND p.del_status = 0 AND a.del_status = 0 AND pt.Code = ? ORDER BY $sortby LIMIT $start_index,30;";
				$std = $db->prepare($sql);
				$std->execute (array($Type));
			}
			$rc = $std -> rowCount();
			$rs = $std -> fetchAll();
			$i = 0;
			foreach($rs as $data)
			{

				switch($data['Gender'])
				{
					case 'M':
						$Gender = "男同學";
						break;
					case 'F':
						$Gender = "女同學";
						break;
					default:
						$Gender = "同學";
						break;
				}

				if($data['Anonymous'] == 0)
				{
					$Author = trim($data['DeptName']) . " " . $Gender;

				}
				else
				{
					$Author = "匿名";
				}

				if($data['Permission'] >= 100)
				{
					$data['Anonymous'] = 1;
					$Author = "站務人員";
				}
				if($data['Permission'] == 255)
				{
					$data['Anonymous'] = 1;
					$Author = "管理員";
				}


				$Title = $data['Title'];
				$Title = emojiDecode($Title);
				$Title = str_replace("\\", "\\\\", $Title);
				$Title = str_replace("\n", '\\n', $Title);
				$Title = htmlspecialchars($Title);

				if($data['Sticky'] > 0)
				{
					$Title = $Title;
				}

				$post = array(
					'PostId' => urlencode($data['PostId']),
					'Author' => urlencode($Author),
					'PostTime' => urlencode($data['PostTime']),
					'Title' => urlencode(trim($Title)),
					'Sticky' => urlencode($data['Sticky']),
					'TotalLike' => urlencode($data['TotalLike']),
					'TotalDislike' => urlencode($data['TotalDislike']),
					'TotalComment' => urlencode($data['TotalComment']),
					'Anonymous' => urlencode($data['Anonymous'])
				);
				
				$result[$i] = $post;
				$i++;
			}
		}
		echo urldecode(json_encode($result));
	}
	else
	{
		$result = array('status' => 0, 'msg' => 'Error!');
		echo urldecode(json_encode($result));
	}
?>