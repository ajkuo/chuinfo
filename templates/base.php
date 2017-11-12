<?php

	$WEB_META = "";

	$WEB_TITLE = "中華大學資訊網";


	$WEB_HCSS = "
    <link rel='stylesheet' href='../css/base.css'>
    <link rel='stylesheet' href='../css/style.css'>
    <link rel='stylesheet' href='../css/chuinfo.css'>
    <link rel='stylesheet' href='../css/sweetalert.css'>
    <link rel='stylesheet' href='../css/bootstrap.min.css'>
    ";
	
	$WEB_HJS = "<script src='../js/jquery.min.js'></script>
                <script src='../js/bootstrap.min.js'></script>";

    $WEB_FCSS = "";

    $WEB_FJS = "
    <script src='../js/freelancer.js'></script>
    <script src='../js/sweetalert.min.js'></script>
    ";

    $LINK_FB = "https://www.facebook.com/chuinfo/";

    $LINK_MAIL = "chuinfo.service@gmail.com";

    $WEB_NAV = "";

    if(isset($_SESSION['Permission']) && !empty($_SESSION['Permission']) && is_numeric($_SESSION['Permission']) && $_SESSION['Permission'] >= 100)
    {

        $Permission = $_SESSION['Permission'];

        switch($Permission)
        {
            case 100:
                $WEB_TITLE = "目前以【站務人員】身分登入";
                break;
            case 255:
                $WEB_TITLE = "目前以【管理員】身分登入";
                break;
            default:
                $WEB_TITLE = "目前以【站務人員】身分登入";
                break;
        }

        $WEB_HCSS = $WEB_HCSS . "<style type='text/css'> .navbar-brand{ color:yellow !important; }</style>";

        $WEB_NAV = "
        <li class='page-scroll'>
            <a href='../manage/'>網站管理</a>
        </li>";
    }

    $WEB_NAV = $WEB_NAV . "
        <li class='page-scroll'>
            <a href='../c/course_list.php'>課程資訊</a>
        </li>

        <li class='page-scroll'>
            <a href='../f/post_list.php'>校園交流</a>
        </li>

        <li class='page-scroll'>
            <a href='../u/profile.php'>個人資訊</a>
        </li>

        <li class='page-scroll btn-logout'>
            <a href='../u/logout.php'>登出</a>
        </li>
    ";

    $CURRENT_STD_YEAR = 105;
    $CURRENT_STD_TERM = 1;

    $CODE_LIFETIME = 3600;

?>