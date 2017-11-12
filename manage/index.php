<?php require_once "../backend/chu_tool.php"; ?>
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
<?php require_once "../templates/base.php"; ?>
<?php require_once "templates/m_base.php"; ?>
<!DOCTYPE html>
<html lang="zh-TW">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <title><?php echo $WEB_TITLE; ?></title>

    <?php echo $m_HCSS; ?>
</head>

<body>
    <div id="wrapper">
        <!-- Navigation -->
        <nav class="navbar navbar-default navbar-static-top" role="navigation" style="margin-bottom: 0">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="#"><?php echo $WEB_TITLE; ?></a>
            </div>

            <?php echo $m_Sidebar; ?>
        </nav>

        <div id="page-wrapper">
            <div class="row">
                <div class="col-lg-12">
                    <h1 class="page-header">後臺管理</h1>
                </div>
            </div>
            <!-- /.row -->
            <div class="row">
                <div class="col-lg-3 col-md-6">
                    <div class="panel panel-primary">
                        <div class="panel-heading">
                            <div class="row">
                                <div class="col-xs-3">
                                    <i class="fa fa-group fa-5x"></i>
                                </div>
                                <div class="col-xs-9 text-right">
                                    <div class="huge"><?php echo number_format($info['TotalUser']); ?></div>
                                    <div>已註冊人數</div>
                                </div>
                            </div>
                        </div>
                        <a href="latest_reg_users.php">
                            <div class="panel-footer">
                                <span class="pull-left">View Details</span>
                                <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                                <div class="clearfix"></div>
                            </div>
                        </a>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="panel panel-green">
                        <div class="panel-heading">
                            <div class="row">
                                <div class="col-xs-3">
                                    <i class="fa fa-info-circle fa-5x"></i>
                                </div>
                                <div class="col-xs-9 text-right">
                                    <div class="huge"><?php echo number_format($info['TotalComment']); ?></div>
                                    <div>課程評論總數</div>
                                </div>
                            </div>
                        </div>
                        <a href="#">
                            <div class="panel-footer">
                                <span class="pull-left">View Details</span>
                                <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                                <div class="clearfix"></div>
                            </div>
                        </a>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="panel panel-yellow">
                        <div class="panel-heading">
                            <div class="row">
                                <div class="col-xs-3">
                                    <i class="fa fa-file-text-o fa-5x"></i>
                                </div>
                                <div class="col-xs-9 text-right">
                                    <div class="huge"><?php echo number_format($info['TotalPost']); ?></div>
                                    <div>校園交流文章數</div>
                                </div>
                            </div>
                        </div>
                        <a href="#">
                            <div class="panel-footer">
                                <span class="pull-left">View Details</span>
                                <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                                <div class="clearfix"></div>
                            </div>
                        </a>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="panel panel-red">
                        <div class="panel-heading">
                            <div class="row">
                                <div class="col-xs-3">
                                    <i class="fa fa-commenting fa-5x"></i>
                                </div>
                                <div class="col-xs-9 text-right">
                                    <div class="huge"><?php echo number_format($info['TotalResponse']); ?></div>
                                    <div>校園交流回應數</div>
                                </div>
                            </div>
                        </div>
                        <a href="#">
                            <div class="panel-footer">
                                <span class="pull-left">View Details</span>
                                <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                                <div class="clearfix"></div>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
            <!-- /.row -->
            <div class="row">

                <!-- 統計資料 -->
                <div class="col-lg-4">
                    <div class="chat-panel panel panel-default">
                        <div class="panel-heading">
                            <i class="fa fa-bar-chart-o fa-fw"></i> 統計資料
                        </div>
                        <div class="panel-body">
                            <ul class="chat">
                            <?php
                                $sql = "SELECT cr.No as No, cr.UserNo as UserNo, a.Gender as Gender, a.SID as SID, cr.GroupNo as CID, cr.Content as Content, cr.add_on as add_on FROM course_response cr, accounts a WHERE cr.UserNo = a.No AND a.del_status = 0 AND cr.del_status = 0 ORDER BY cr.add_on DESC LIMIT 25;";
                                $std = $db->prepare($sql);
                                $std->execute ();
                                $comment_data = $std -> fetchAll(PDO::FETCH_ASSOC);
                                $comment_count = $std -> rowCount();
                                if($comment_count == 0)
                                {
                            ?>
                                    <p class="text-center">
                                        <br><br><i class="fa fa-paper-plane"></i> 目前沒有任何課程評論。
                                    </p>
                            <?php
                                }
                                else
                                {
                                    foreach($comment_data as $cd)
                                    {
                                        $cd_content = $cd['Content'];

                                        if(mb_strlen($cd['Content'], "utf-8") > 30)
                                        {
                                            $cd_content = mb_substr($cd['Content'], 0, 30, "utf-8") . "......";
                                        }

                            ?>
                                        <li class="left clearfix">
                                            <span class="chat-img pull-left">
                                            <?php
                                                $Gender = "";
                                                switch($cd['Gender'])
                                                {
                                                    case "M":
                                                        $Gender = "fa-mars";
                                                        break;
                                                    case "F":
                                                        $Gender = "fa-venus";
                                                        break;
                                                    case "O":
                                                        $Gender = "fa-transgender-alt";
                                                        break;
                                                    default:
                                                        $Gender = "fa-question-circle-o";
                                                        break;
                                                }
                                            ?>
                                                <i class="fa <?php echo $Gender; ?> fa-3x"></i>
                                            </span>
                                            <div class="chat-body clearfix">
                                                <div class="header">
                                                    <strong class="primary-font"><a href="user_detail.php?id=<?php echo $cd['UserNo']; ?>"><?php echo strtoupper($cd['SID']); ?></a></strong>
                                                    <small class="pull-right text-muted">
                                                        <i class="fa fa-clock-o fa-fw"></i><?php echo $cd['add_on']; ?> 
                                                        <i class="fa fa-link fa-fw"></i><a href="/c/course_detail.php?id=<?php echo $cd['CID']; ?>" target="_blank">文章連結（<?php echo $cd['CID']; ?>）</a>
                                                    </small>
                                                </div>
                                                <p>
                                                    <?php echo $cd_content; ?>
                                                </p>
                                            </div>
                                        </li>
                            <?php
                                    }
                                }
                            ?>
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- 即時通知 -->
                <div class="col-lg-4">
                    <div class="chat-panel panel panel-default">
                        <div class="panel-heading">
                            <i class="fa fa-comments fa-fw"></i>
                            文章檢舉
                            <div class="btn-group pull-right">
                                <button type="button" class="btn btn-default btn-xs dropdown-toggle" data-toggle="dropdown">
                                    <i class="fa fa-chevron-down"></i>
                                </button>
                                <ul class="dropdown-menu slidedown">
                                    <li>
                                        <a href="#">
                                            <i class="fa fa-refresh fa-fw"></i> Refresh
                                        </a>
                                    </li>
                                    <li>
                                        <a href="#">
                                            <i class="fa fa-check-circle fa-fw"></i> Available
                                        </a>
                                    </li>
                                    <li>
                                        <a href="#">
                                            <i class="fa fa-times fa-fw"></i> Busy
                                        </a>
                                    </li>
                                    <li>
                                        <a href="#">
                                            <i class="fa fa-clock-o fa-fw"></i> Away
                                        </a>
                                    </li>
                                    <li class="divider"></li>
                                    <li>
                                        <a href="#">
                                            <i class="fa fa-sign-out fa-fw"></i> Sign Out
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
                            <ul class="chat">
                            <?php
                                $sql = "SELECT No, SID, PostId, Content, add_on FROM post_report WHERE status = 0 ORDER BY add_on DESC LIMIT 20;";
                                $std = $db->prepare($sql);
                                $std->execute ();
                                $report_data = $std -> fetchAll(PDO::FETCH_ASSOC);
                                $report_count = $std -> rowCount();
                                if($report_count == 0)
                                {
                            ?>
                                    <p class="text-center">
                                        <br><br><i class="fa fa-paper-plane"></i> 太好了！目前沒有接到任何檢舉。
                                    </p>
                            <?php
                                }
                                else
                                {
                                    foreach($report_data as $rd)
                                    {
                            ?>
                                <li class="left clearfix">
                                    <span class="chat-img pull-left">
                                        <i class="fa fa-exclamation-triangle fa-3x"></i>
                                    </span>
                                    <div class="chat-body clearfix">
                                        <div class="header">
                                            <strong class="primary-font"><?php echo strtoupper($rd['SID']); ?></strong>
                                            <small class="pull-right text-muted">
                                                <i class="fa fa-clock-o fa-fw"></i><?php echo $rd['add_on']; ?> 
                                                <i class="fa fa-link fa-fw"></i><a href="/f/post.php?p=<?php echo $rd['PostId']; ?>" target="_blank">文章連結</a>
                                            </small>
                                        </div>
                                        <p>
                                            <a class="btn-report-done" id="btn-report-done-<?php echo $rd['No']; ?>" href="#"><i class="fa fa-check-square-o fa-fw"></i>[完成]</a><br><?php echo $rd['Content']; ?>
                                        </p>
                                    </div>
                                </li>
                            <?php
                                    }
                                }
                            ?>
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- 異常警示 -->
                <div class="col-lg-4">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <i class="fa fa-bell fa-fw"></i> 異常警示
                        </div>
                        <a href="#" class="list-group-item">
                            <i class="fa fa-comment fa-fw"></i> New Comment
                            <span class="pull-right text-muted small"><em>4 minutes ago</em>
                            </span>
                        </a>
                        <a href="#" class="list-group-item">
                            <i class="fa fa-twitter fa-fw"></i> 3 New Followers
                            <span class="pull-right text-muted small"><em>12 minutes ago</em>
                            </span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <link rel='stylesheet' href='../../css/sweetalert.css'>
    <?php echo $m_FJS; ?>
    <script src='../../js/sweetalert.min.js'></script>
    <script type="text/javascript">
        $(".btn-report-done").click(function(){
            var reportId = this.id.replace("btn-report-done-", "");

            swal({
                title: "確定完成？", 
                text: "您確定要將這篇檢舉標記為已完成嗎？", 
                type: "warning",
                showCancelButton: true,   
                cancelButtonText: "取　　消",    
                confirmButtonText: "確　　定",   
                closeOnConfirm: false 
            },
            function(){
                $.ajax({
                    url: "f/report_done.php",
                    type: "POST",
                    dataType: "json",
                    data: {
                        reportId: reportId
                    },
                    success: function(result){
                        if(result.status == 1)
                        {
                            swal({
                                title: "完成", 
                                text: "該則檢舉已標記為完成。", 
                                type: "success"
                            },
                            function(){
                                location.reload();
                            });
                        }
                        else
                        {
                            swal("錯誤", result.msg, "error");
                        }
                    }
                });
            });
        });
    </script>
</body>

</html>
