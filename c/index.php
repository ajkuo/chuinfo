<?php

	if(isset($_GET['c']) && !empty($_GET['c'])){
		$_GET['id'] = $_GET['c'];
		include "course_detail.php";
	}
	else{
		include "course_list.php";
	}

?>