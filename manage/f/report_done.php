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

	if(isset($_POST['reportId']) && !empty($_POST['reportId']) && is_numeric($_POST['reportId']))
	{
        $db_tool = new DB_Tool;
        $db = &$db_tool->get_DB();
        $sql = "UPDATE post_report SET Status = 1 WHERE No = ?;";
        $std = $db->prepare($sql);
        $std->execute (array($_POST['reportId']));	     
		$result = array('status' => 1, 'msg' => '完成。');
	}
	else
	{
		$result = array('status' => 0, 'msg' => 'Error!');
	}

	echo urldecode(json_encode($result));
?>