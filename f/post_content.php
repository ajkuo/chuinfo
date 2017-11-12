<?php include_once "../backend/chu_tool.php"; ?>
<?php check_to_login(); ?>
<?php require_once "../templates/base.php"; ?>
<?php
	header("Content-Type: application/json; charset=utf-8");

	$PID = isset($_GET['p']) ?  input_filter($_GET['p']) : '';

	if(!empty($PID) && is_numeric($PID))
	{
		$db_tool = new DB_Tool;
		$db = &$db_tool->get_DB();

		if($_SESSION['Permission'] >= 200)
		{
			$post_exist = $db_tool->get_value('forum_post', 'count(*)', 'No = ?', array($PID));
		}
		else
		{
			$post_exist = $db_tool->get_value('forum_post', 'count(*)', 'No = ? AND del_status = 0', array($PID));
		}

		if($post_exist == 1)
		{

			$sql = "SELECT p.No as PID, p.UserNo as UserNo, a.SID as SID, a.Permission as Permission, pt.Name as Type, p.Title as Title, p.Content as Content, p.anonymous as Anonymous, p.TotalComment as TotalComment, p.TotalLike as TotalLike, p.TotalDislike as TotalDislike, p.add_on as PostTime, p.del_status as del_status, a.Gender as Gender, d.Name as DeptName FROM forum_post p, accounts a, departments d, forum_type pt WHERE p.UserNo = a.No AND a.DeptNo = d.No AND p.Type = pt.No AND p.No = ?;";

			$std = $db->prepare($sql);
			$std->execute (array($PID));
			$data = $std -> fetch();

			$Author = 0;
			if(isset($_SESSION['UserNo']) && !empty($_SESSION['UserNo']))
			{
				if($_SESSION['UserNo'] == $data['UserNo'])
				{
					$Author = 1;
				}
			}

			$Title = $data['Title'];
			$Title = emojiDecode($Title);
			$Title = str_replace("\\", "\\\\", $Title);
			$Title = str_replace("\n", '\n', $Title);	
			$Title = htmlspecialchars($Title);

			$Content = $data['Content'];

 			$reg = '/((http|https):\/\/)?(\w+\.)+(\w+)[\w\/\.\-]*(jpg|gif|png)/U';
 			
			preg_match_all($reg, $Content, $match);

			$img_url = array();
			$len = count($match[0]);

			$Content = emojiDecode($Content);
			$Content = str_replace("\\", "\\\\", $Content);
			$Content = str_replace("\n", '\n', $Content);	
			$Content = htmlspecialchars($Content);

			for($i=0; $i < $len; $i++){
			    $Content = str_replace($match[0][$i], "<img class='postImg' src='#:img" . $i . "' alt=''>", $Content);
				
				$img_url[$i] = $match[0][$i];
			}


			for($i=0; $i < $len; $i++){
				if(preg_match('/((http|https):\/\/)+/', $img_url[$i]))
				{
					$Content = str_replace("#:img" . $i, $img_url[$i], $Content);
				}
				else
				{
				$Content = str_replace("#:img" . $i, "http://" . $img_url[$i], $Content);
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

			if($data['Permission'] >= 100)
			{
				$data['Anonymous'] = 1;
				$Gender = "站務人員";
			}
			if($data['Permission'] == 255)
			{
				$data['Anonymous'] = 1;
				$Gender = "管理員";
			}


			if($data['Anonymous'] == 0)
			{
				$post = array(
					'Author' => urlencode($Author),
					'PostId' => urlencode($data['PID']),
					'Type' => urlencode(trim($data['Type'])),
					'Title' => urlencode(trim($Title)),
					'Content' => urlencode(trim($Content)),
					'TotalComment' => urlencode($data['TotalComment']),
					'TotalLike' => urlencode($data['TotalLike']),
					'TotalDislike' => urlencode($data['TotalDislike']),
					'Gender' => urlencode($Gender),
					'DeptName' => urlencode(trim($data['DeptName'])),
					'PostTime' => urlencode($data['PostTime']),
					'Anonymous' => urlencode($data['Anonymous']),
					'Permission' => urlencode($data['Permission']),
					'status' => 1
				);
			}
			else
			{
				if($data['Permission'] < 100)
				{
					$Gender = "";
				}

				$post = array(
					'Author' => urlencode($Author),
					'PostId' => urlencode($data['PID']),
					'Type' => urlencode(trim($data['Type'])),
					'Title' => urlencode(trim($Title)),
					'Content' => urlencode(trim($Content)),
					'TotalComment' => urlencode($data['TotalComment']),
					'TotalLike' => urlencode($data['TotalLike']),
					'TotalDislike' => urlencode($data['TotalDislike']),
					'Gender' => urlencode($Gender),
					'PostTime' => urlencode($data['PostTime']),
					'Anonymous' => urlencode($data['Anonymous']),
					'Permission' => urlencode($data['Permission']),
					'status' => 1
				);
			}

			if($_SESSION['Permission'] >= 200)
			{
				$post['SID'] = urlencode(strtoupper($data['SID']));
				$post['del_status'] = urlencode(strtoupper($data['del_status']));
			}
			
		}
		else
		{
			$post = array('status' => 0, 'msg' => '文章不存在或是已經被移除了！');
		}

		echo urldecode(json_encode($post));
	}
	else
	{
		$post = array('status' => 0, 'msg' => 'Error!');
		echo urldecode(json_encode($post));
	}
?>