<?php include_once "../backend/chu_tool.php"; ?>
<?php check_to_login(); ?>
<?php require_once "../templates/base.php"; ?>
<?php
	header("Content-Type: application/json; charset=utf-8");
	
	$CID = isset($_GET['c']) ?  input_filter($_GET['c']) : '';

	if(!empty($CID) && is_numeric($CID))
	{
		$db_tool = new DB_Tool;
		$db = &$db_tool->get_DB();

		//如果是編輯回應，就會多傳一個ResId，那只需要針對那篇回應回傳即可
		if(isset($_GET['PostId']) && !empty($_GET['PostId']) && is_numeric($_GET['PostId']))
		{
			$sql = "SELECT cr.No as PID, a.SID as SID, a.Permission as Permission, cr.UserNo as UserNo, a.Gender as Gender, d.Name as DeptName, cr.add_on as PostTime, cr.Floor as Floor, cr.Content as Content, cr.anonymous as Anonymous, cr.del_status as flag, cr.del_by as del_by FROM accounts a, course_response cr, departments d WHERE a.No = cr.UserNo AND a.DeptNo = d.No AND cr.GroupNo = ? AND cr.No = ?";

			$std = $db->prepare($sql);
			$std->execute (array($CID, $_GET['PostId']));
		}
		else
		{
			$SN = isset($_GET['SN']) ?  input_filter($_GET['SN']) : 30;
			$start_index = ($SN - 30);

			$sql = "SELECT cr.No as PID, a.SID as SID, a.Permission as Permission, cr.UserNo as UserNo, a.Gender as Gender, d.Name as DeptName, cr.add_on as PostTime, cr.Floor as Floor, cr.Content as Content, cr.anonymous as Anonymous, cr.del_status as flag, cr.del_by as del_by FROM accounts a, course_response cr, departments d WHERE cr.No IN (SELECT MAX(No) FROM course_response WHERE GroupNo = ? GROUP BY Floor) AND a.No = cr.UserNo AND a.DeptNo = d.No AND cr.GroupNo = ? ORDER BY cr.Floor ASC LIMIT $start_index,30;";

			$std = $db->prepare($sql);
			$std->execute (array($CID, $CID));				
		}

		$rc = $std -> rowCount();
		$rs = $std -> fetchAll();

		$result = array();
		$i = 0;
		foreach($rs as $data)
		{
			$Author = 0;
			if(isset($_SESSION['UserNo']) && !empty($_SESSION['UserNo']))
			{
				if($_SESSION['UserNo'] == $data['UserNo'])
				{
					$Author = 1;
				}
			}


			if($data['flag'] == 1)
			{
				$Author = 0;

				if($data['del_by'] == 'Deleted by itself.')
				{
					$Content = "此篇評論已被原作者移除。";
				}
				else
				{
					$Content = "此篇評論已被站務人員移除。";
				}

				$post = array(
					'PostId' => urlencode($data['PID']),
					'Floor' => urlencode($data['Floor']),
					'PostTime' => urlencode($data['PostTime']),
					'Content' => urlencode(trim($Content)),
					'flag' => urlencode($data['flag'])
				);
			}
			else
			{
				$Content = $data['Content'];
				$Content = emojiDecode($Content);
				$Content = str_replace("\\", "\\\\", $Content);
				$Content = str_replace("\n", '\n', $Content);	
				$Content = htmlspecialchars($Content);


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

				$Year = substr($data['SID'], 1, 3) . "級";

				if($data['Permission'] >= 100)
				{
					$data['Anonymous'] = 1;
					$Gender = "站務人員";
					$Year = "";
				}
				if($data['Permission'] == 255)
				{
					$data['Anonymous'] = 1;
					$Gender = "管理員";
					$Year = "";
				}

				if($data['Anonymous'] == 0)
				{
					$post = array(
						'Author' => urlencode($Author),
						'PostId' => urlencode($data['PID']),
						'Floor' => urlencode($data['Floor']),
						'Year' => urlencode($Year),
						'Gender' => urlencode($Gender),
						'DeptName' => urlencode(trim($data['DeptName'])),
						'PostTime' => urlencode($data['PostTime']),
						'Content' => urlencode(trim($Content)),
						'Anonymous' => urlencode($data['Anonymous']),
						'flag' => urlencode($data['flag'])
					);
				}
				else
				{
					$post = array(
						'Author' => urlencode($Author),
						'PostId' => urlencode($data['PID']),
						'Floor' => urlencode($data['Floor']),
						'Year' => urlencode($Year),
						'Gender' => urlencode($Gender),
						'PostTime' => urlencode($data['PostTime']),
						'Content' => urlencode(trim($Content)),
						'Anonymous' => urlencode($data['Anonymous']),
						'flag' => urlencode($data['flag'])
					);
				}
			}

			$result[$i] = $post;
			$i++;
		}
		echo urldecode(json_encode($result));			
	}
	else
	{
		$result = array('status' => 0, 'msg' => 'Error!');
		echo urldecode(json_encode($result));
	}
?>