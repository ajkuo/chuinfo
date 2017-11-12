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

	if(isset($_POST['SID']) && !empty($_POST['SID']) && is_numeric($_POST['SID']) && isset($_POST['Permission']) && !empty($_POST['Permission']) && is_numeric($_POST['Permission']))
	{
		if($_POST['Permission'] >= 100 && $_SESSION['Permission'] < 200)
		{
        	turn_to_url("../user.php"); 
		}
		else
		{
			$access = $db_tool -> get_value('accounts', 'count(*)', "No = ? AND Permission < ? AND del_status = 0", array($_POST['SID'], $_SESSION['Permission']));

			if($access == 1)
			{
				if($_POST['Permission'] == -1)
				{
					$_POST['Permission'] = 0;
				}
		        $db_tool = new DB_Tool;
		        $db = &$db_tool->get_DB();
		        $sql = "UPDATE accounts SET Permission = ? WHERE No = ?;";
		        $std = $db->prepare($sql);
		        $std->execute (array($_POST['Permission'], $_POST['SID']));	     
				$result = array('status' => 1, 'msg' => '權限等級修改完成。');
			}
			else
			{
				$result = array('status' => 0, 'msg' => '該會員不存在或您的權限不足！');
			}   
		}
	}
	else
	{
		$result = array('status' => 0, 'msg' => 'Error!');
	}

	echo urldecode(json_encode($result));
?>