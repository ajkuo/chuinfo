<?php require_once "../backend/chu_tool.php"; ?>
<?php check_to_login(); ?>
<?php require_once "../templates/base.php"; ?>
<?php
	header("Content-Type: application/json; charset=utf-8");

	if(isset($_SESSION['Permission']) && !empty($_SESSION['Permission']))
	{
		$db_tool = new DB_Tool;
		$db = &$db_tool->get_DB();

		$sql = "SELECT No, Name, Code, PublicPermission FROM forum_type WHERE del_status = 0 AND PublicPermission <= ? ORDER BY Sort ASC;";
		$std = $db->prepare($sql);
		$std->execute (array($_SESSION['Permission']));
		$rc = $std -> rowCount();
		$rs = $std -> fetchAll();

		$result = array();
		$i = 0;

		foreach($rs as $data)
		{
			$type = array(
				'TypeNo' => urlencode($data['No']),
				'Name' => urlencode(trim($data['Name'])),
				'Code' => urlencode(trim($data['Code']))
			);

			$result[$i] = $type;
			$i++;
		}
	}
	else
	{
		$result = array('status' => 0, 'msg' => 'Error!');
	}

	echo urldecode(json_encode($result));
?>