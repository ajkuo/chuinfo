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

    <style type="text/css">
        tr{
            cursor: pointer;
        }
    </style>
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
                    <h1 class="page-header">最新註冊會員</h1>
                </div>
            </div>
            <?php
                $sql = "SELECT a.No as UserNo, a.SID as SID, d.Name as DeptName, a.Name as Name, a.Permission as Permission, a.add_on as add_on FROM accounts a, departments d WHERE a.DeptNo = d.No AND a.del_status = 0 ORDER BY a.add_on DESC LIMIT 30;";
                $std = $db->prepare($sql);
                $std->execute ();
                $user_data = $std -> fetchAll(PDO::FETCH_ASSOC);
                $user_count = $std -> rowCount();
            ?>
            <div class="row">
                <div class="col-lg-12">
                    <div class="panel panel-default" id="tbStudents">
                        <?php
                            if($user_count == 0)
                            {
                        ?>
                            <div class='no-student'>目前尚無任何學生。</div>
                        <?php
                            }
                            else
                            {
                        ?>
                            <div class='panel-heading'>※ 以下為最新註冊的 30 位學生資料</div>
                            <div class='panel-body'>
                                <div class='dataTable_wrapper'>
                                    <table width='100%' class='table table-striped table-bordered table-hover' id='dataTables-student'>
                                        <thead>
                                            <tr>
                                                <th class='hidden-xs'>編號</th>
                                                <th>系所</th>
                                                <th>學號</th>
                                                <th>姓名</th>
                                                <th>認證狀態</th>
                                                <th class='hidden-xs'>註冊日期</th>
                                            </tr>
                                        </thead>
                                        <tbody id='dataTables-student-body'>
                                    <?php
                                        foreach($user_data as $ud)
                                        {
                                            $PermissionStatus = "";

                                            switch($ud['Permission'])
                                            {
                                                case '0':
                                                    $PermissionStatus = "未認證";
                                                    break;
                                                case '1':
                                                    $PermissionStatus = "校內信箱認證";
                                                    break;
                                                case '2':
                                                    $PermissionStatus = "手機認證";
                                                    break;
                                                case '3':
                                                    $PermissionStatus = "學生證認證";
                                                    break;
                                                case '5':
                                                    $PermissionStatus = "完全認證";
                                                    break;
                                                case '100':
                                                    $PermissionStatus = "站務人員(" . $ud['Permission'] . ")";
                                                    break;
                                                case '255':
                                                    $PermissionStatus = "管理員(" . $ud['Permission'] . ")";
                                                    break;
                                                default:
                                                    break;
                                            }

                                    ?>
                                            <tr class='odd gradeX' id='dataTables-row-s-" + UserNo + "'  data-href='user_detail.php?id=<?php echo $ud['UserNo']; ?>'>
                                                <td class='hidden-xs'><?php echo $ud['UserNo']; ?></td>
                                                <td class='center'><?php echo $ud['DeptName']; ?></td>
                                                <td class='center'><?php echo $ud['SID']; ?></td>
                                                <td class='center'><?php echo $ud['Name']; ?></td>
                                                <td><?php echo $PermissionStatus; ?></td>
                                                <td class='hidden-xs'><?php echo $ud['add_on']; ?></td>
                                            </tr>
                                    <?php
                                        }
                                    ?> 
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        <?php
                            }
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php echo $m_FJS; ?>

    <script src="js/jquery.dataTables.min.js"></script>
    <script src="js/dataTables.bootstrap.min.js"></script>
    <script src="js/dataTables.responsive.js"></script>
    <script type="text/javascript">        
        $(document).ready(function() {
            $('#dataTables-example').DataTable({
                responsive: true
            });
        });

        $(document).on('click', 'table tr', function(){
            window.location = $(this).data('href');
            return false;
        });
    </script>
</body>

</html>
