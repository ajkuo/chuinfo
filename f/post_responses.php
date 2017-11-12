<?php include_once "../backend/chu_tool.php"; ?>
<?php check_to_login(); ?>
<?php require_once "../templates/base.php"; ?>
<?php
	header("Content-Type: application/json; charset=utf-8");

	$PID = isset($_GET['p']) ?  input_filter($_GET['p']) : '';
	$SN = isset($_GET['SN']) ?  input_filter($_GET['SN']) : 30;

	if(!empty($PID) && is_numeric($PID))
	{
		$db_tool = new DB_Tool;
		$db = &$db_tool->get_DB();
		
		//如果是編輯回應，就會多傳一個ResId，那只需要針對那篇回應回傳即可
		if(isset($_GET['ResId']) && !empty($_GET['ResId']) && is_numeric($_GET['ResId']))
		{
			$sql = "SELECT pr.No as PID, a.SID as SID, a.Permission as Permission, pr.UserNo as UserNo, a.Gender as Gender, d.Name as DeptName, pr.add_on as PostTime, pr.Floor as Floor, pr.Content as Content, pr.anonymous as Anonymous, pr.del_status as flag, pr.del_by as del_by FROM accounts a, post_response pr, departments d WHERE a.No = pr.UserNo AND a.DeptNo = d.No AND pr.PostId = ? AND pr.No = ?;";
				$std = $db->prepare($sql);
				$std->execute (array($PID, $_GET['ResId']));
		}
		else
		{
			$start_index = ($SN - 30);

			$sql = "SELECT pr.No as PID, a.SID as SID, a.Permission as Permission, pr.UserNo as UserNo, a.Gender as Gender, d.Name as DeptName, pr.add_on as PostTime, pr.Floor as Floor, pr.Content as Content, pr.anonymous as Anonymous, pr.del_status as flag, pr.del_by as del_by FROM accounts a, post_response pr, departments d WHERE pr.No IN (SELECT MAX(No) FROM post_response WHERE PostId = ? GROUP BY Floor) AND a.No = pr.UserNo AND a.DeptNo = d.No AND pr.PostId = ? ORDER BY pr.Floor ASC LIMIT $start_index,30;";
				$std = $db->prepare($sql);
				$std->execute (array($PID, $PID));
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
				if($data['del_by'] == 'Deleted by itself.')
				{
					$Content = "此篇評論已被原作者移除。";
				}
				else
				{
					$Content = "此篇評論已被站務人員移除。";
				}

				$Author = 0;

				$post = array(
					'PostId' => urlencode($data['PID']),	//這其實是ResId =.=
					'Floor' => urlencode($data['Floor']),
					'PostTime' => urlencode($data['PostTime']),
					'Content' => urlencode(trim($Content)),
					'flag' => urlencode($data['flag'])
				);
			}
			else
			{
				$Content = $data['Content'];

	 			$reg = '/((http|https):\/\/)?(\w+\.)+(\w+)[\w\/\.\-]*(jpg|gif|png)/U';

				preg_match_all($reg, $Content, $match);

				$img_url = array();
				$len = count($match[0]);

				$Content = emojiDecode($Content);
				$Content = str_replace("\\", "\\\\", $Content);
				$Content = str_replace("\n", '\n', $Content);	
				$Content = htmlspecialchars($Content);

				for($n=0; $n < $len; $n++){
				    $Content = str_replace($match[0][$n], "<img class='resImg' src='#:img" . $n . "' alt='no'>", $Content);
					
					$img_url[$n] = $match[0][$n];
				}

				for($n=0; $n < $len; $n++){
					if(preg_match('/((http|https):\/\/)+/', $img_url[$n]))
					{
						$Content = str_replace("#:img" . $n, $img_url[$n], $Content);
					}
					else
					{
					$Content = str_replace("#:img" . $n, "http://" . $img_url[$n], $Content);
					}
				}

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
						'PostId' => urlencode($data['PID']),	//這其實是ResId =.=
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
						'PostId' => urlencode($data['PID']),	//這其實是ResId =.=
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