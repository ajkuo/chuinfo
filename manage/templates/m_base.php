<?php

	$m_HCSS = "
	    <link href='../css/bootstrap.min.css' rel='stylesheet'>
	    <link href='../css/font-awesome.min.css' rel='stylesheet' type='text/css'>
	    <link rel='stylesheet' href='../css/base.css'>
	    <link rel='stylesheet' href='../css/style.css'>

	    <link href='css/morris.css' rel='stylesheet'>
	    <link href='css/metisMenu.min.css' rel='stylesheet'>
	    <link href='css/timeline.css' rel='stylesheet'>
	    <link href='css/sb-admin-2.css' rel='stylesheet'>

	    <style type='text/css'>
	        body{
	            padding: 0;
	        }

	        .navbar-brand{ 
	            color:yellow !important; 
	        }
	    </style>
    ";

    $m_HJS = "";

    $m_Sidebar = "
            <div class='navbar-default sidebar' role='navigation'>
                <div class='sidebar-nav navbar-collapse'>
                    <ul class='nav' id='side-menu'>
                        <li class='sidebar-search'>
                            <div class='input-group custom-search-form'>
                                <input type='text' class='form-control' placeholder='Search...'>
                                <span class='input-group-btn'>
                                <button class='btn btn-default' type='button'>
                                    <i class='fa fa-search'></i>
                                </button>
                            </span>
                            </div>
                            <!-- /input-group -->
                        </li>
                        <li>
                            <a href='/'><i class='fa fa-home fa-fw'></i> 回首頁</a>
                        </li>
                        <li>
                            <a href='/manage/'><i class='fa fa-dashboard fa-fw'></i> 後臺首頁</a>
                        </li>
                        <li>
                            <a href='/manage/user.php'><i class='fa fa-group fa-fw'></i> 會員管理</a>
                        </li>
                        <li>
                            <a href='#'><i class='fa fa-wrench fa-fw'></i> UI Elements<span class='fa arrow'></span></a>
                            <ul class='nav nav-second-level'>
                                <li>
                                    <a href='panels-wells.html'>Panels and Wells</a>
                                </li>
                                <li>
                                    <a href='buttons.html'>Buttons</a>
                                </li>
                                <li>
                                    <a href='notifications.html'>Notifications</a>
                                </li>
                            </ul>
                            <!-- /.nav-second-level -->
                        </li>
                        <li>
                            <a href='#'><i class='fa fa-sitemap fa-fw'></i> Multi-Level Dropdown<span class='fa arrow'></span></a>
                            <ul class='nav nav-second-level'>
                                <li>
                                    <a href='#'>Second Level Item</a>
                                </li>
                                <li>
                                    <a href='#'>Second Level Item</a>
                                </li>
                                <li>
                                    <a href='#'>Third Level <span class='fa arrow'></span></a>
                                    <ul class='nav nav-third-level'>
                                        <li>
                                            <a href='#'>Third Level Item</a>
                                        </li>
                                        <li>
                                            <a href='#'>Third Level Item</a>
                                        </li>
                                        <li>
                                            <a href='#'>Third Level Item</a>
                                        </li>
                                        <li>
                                            <a href='#'>Third Level Item</a>
                                        </li>
                                    </ul>
                                    <!-- /.nav-third-level -->
                                </li>
                            </ul>
                            <!-- /.nav-second-level -->
                        </li>
                        <li>
                            <a href='#'><i class='fa fa-files-o fa-fw'></i> Sample Pages<span class='fa arrow'></span></a>
                            <ul class='nav nav-second-level'>
                                <li>
                                    <a href='blank.html'>Blank Page</a>
                                </li>
                                <li>
                                    <a href='login.html'>Login Page</a>
                                </li>
                            </ul>
                            <!-- /.nav-second-level -->
                        </li>
                    </ul>
                </div>
                <!-- /.sidebar-collapse -->
            </div>
    ";


    $m_FJS = "
	    <script src='../js/jquery.min.js'></script>
	    <script src='../js/bootstrap.min.js'></script>
	    <script src='js/metisMenu.min.js'></script>
	    <script src='js/raphael-min.js'></script>
	    <script src='js/morris.min.js'></script>
	    <script src='js/morris-data.js'></script>
	    <script src='js/sb-admin-2.js'></script>
	";

?>