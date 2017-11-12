<?php require_once "../../backend/chu_tool.php"; ?>
<?php check_to_login(); ?>
<?
    if(!isset($_SESSION['Permission']) || empty($_SESSION['Permission']) || !is_numeric($_SESSION['Permission']) || $_SESSION['Permission'] < 100)
    {
        turn_to_url("/c/course_list.php"); 
    }
    else
    {
        $db_tool = new DB_Tool;
        $db = &$db_tool->get_DB();
        $exist = $db_tool -> get_value('accounts', 'count(*)', "No = ? AND Permission >= 100 AND lock_status = 0 AND del_status = 0", array($_SESSION['UserNo']));

        if($exist == 1)
        {
            $info = $db_tool -> get_value('m_info', 'TotalUser, TotalComment, TotalPost, TotalResponse');
        }
        else
        {
            turn_to_url("/c/course_list.php"); 
        }
    }
?>
<?php require_once "../../templates/base.php"; ?>
<?php
	header("Content-Type: application/json; charset=utf-8");

	if(isset($_GET['DeptNo']) && !empty($_GET['DeptNo']) && is_numeric($_GET['DeptNo']))
	{
		$SN = isset($_GET['SN']) ?  input_filter($_GET['SN']) : 30;
		$start_index = ($SN - 30);

		$sql = "SELECT a.No as UserNo, a.SID as SID, d.Name as DeptName, a.Name as Name, a.Email as Email, a.Nickname as Nickname, a.Gender Gender, a.Permission as Permission, a.dob as dob, a.add_on as add_on, a.lock_status as lock_status, a.lock_reason as lock_reason, a.del_status as del_status, a.TotalPost as TotalPost, a.TotalResponse as TotalResponse, a.TotalComment as TotalComment FROM accounts a, departments d WHERE a.DeptNo = d.No AND DeptNo = ? ORDER BY a.No ASC LIMIT $start_index,30;";
		$std = $db->prepare($sql);
		$std->execute (array($_GET['DeptNo']));
		$rs = $std -> fetchAll();

		$result = array();
		$i = 0;

		foreach($rs as $data)
		{
			$user = array(
				'UserNo' => urlencode($data['UserNo']),
				'SID' => urlencode($data['SID']),
				'DeptName' => urlencode(trim($data['DeptName'])),
				'Name' => urlencode(trim($data['Name'])),
				'Email' => urlencode(trim($data['Email'])),
				'Nickname' => urlencode($data['Nickname']),
				'Gender' => urlencode($data['Gender']),
				'Permission' => urlencode($data['Permission']),
				'dob' => urlencode($data['dob']),
				'add_on' => urlencode($data['add_on']),
				'lock_status' => urlencode($data['lock_status']),
				'lock_reason' => urlencode($data['lock_reason']),
				'TotalPost' => urlencode($data['TotalPost']),
				'TotalResponse' => urlencode($data['TotalResponse']),
				'TotalComment' => urlencode($data['TotalComment'])
			);

			$result[$i] = $user;
			$i++;
		}
	}
	else
	{
		$result = array('status' => 0, 'msg' => 'Error!');
	}

	echo urldecode(json_encode($result));
?>