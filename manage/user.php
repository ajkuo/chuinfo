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
                    <h1 class="page-header">會員管理</h1>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-4 col-xs-12">
                    <select class="form-control" id="ddlstDept">
                        <option value="" disabled selected hidden>請選擇系所以顯示學生資料</option>
                         <?php 
                                $db_tool = new DB_Tool;
                                $db = &$db_tool->get_DB();
                                $sql = "SELECT No, Name FROM departments WHERE Type = 'S';";
                                $std = $db->prepare($sql);
                                $std->execute();
                                $rc = $std -> rowCount();
                                $rs = $std -> fetchAll();
                                foreach($rs as $data):
                         ?>
                         <option value="<?php echo $data['No']; ?>"><?php echo $data['Name']; ?></option>
                     <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-lg-8">
                </div>
            </div>
            <br>
            <div class="row">
                <div class="col-lg-12">
                    <div class="panel panel-default" id="tbStudents">
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php echo $m_FJS; ?>

    <script src="js/jquery.dataTables.min.js"></script>
    <script src="js/dataTables.bootstrap.min.js"></script>
    <script src="js/dataTables.responsive.js"></script>
    <script type="text/javascript" src="../../js/jquery.endless-scroll.js"></script>
    
    <script>
        var Total_SN = 30;
        var FinalData = false;

        $(document).ready(function() {

            if($('#ddlstDept').val() != 0)
            {
                GetStudentData();
            }

            $('#dataTables-example').DataTable({
                responsive: true
            });


        });

        $(document).on('click', 'table tr', function(){
                window.location = $(this).data('href');
                return false;
        });

        $(document).endlessScroll({
              fireOnce: false,
            inflowPixels: 100,
            ceaseFireOnEmpty: false,
            callback: function(i){
                i = (i + 1) * 30;
                GetStudentData(i);
            },
            ceaseFire: function(){
                if(FinalData)
                {
                    return true;
                }
            }
        });

        $("#ddlstDept").change(function(){
            GetStudentData();
        });

        function GetStudentData(SN)
        {   
            SN = SN || 30;
            Total_SN = SN;

            $.ajax({
                url: "u/dept_user_act.php",
                type: "GET",
                dataType: "json",
                data: {
                    DeptNo: $("#ddlstDept").val(),
                    SN: SN
                },
                success: function(result){
                    ShowStudentData(result, SN);
                }
            });
        }

        function ShowStudentData(result, SN)
        {
            if(result.length == 0)
            {
                if(Total_SN <= 30)
                {
                    $('#tbStudents').html("<div class='no-student'>此系所目前尚無任何學生。</div>");
                }

                FinalData = true;
            }
            else
            {
                if(Total_SN <= 30)
                {
                    var deptName = $("#ddlstDept :selected").text();
                    $("#tbStudents").html("<div class='panel-heading'>※ 以下為【" + deptName + "】的學生資料</div>");
                    $("#tbStudents").append("<div class='panel-body'><div class='dataTable_wrapper'><table width='100%' class='table table-striped table-bordered table-hover' id='dataTables-student'></table></div></div>");
                    $("#dataTables-student").html("<thead><tr><th class='hidden-xs'>會員編號</th><th>學號</th><th>姓名</th><th>信箱</th><th class='hidden-xs'>認證狀態</th><th class='hidden-xs'>帳號狀態</th><th class='hidden-xs'>註冊日期</th></tr></thead>");
                    $("#dataTables-student").append("<tbody id='dataTables-student-body'></tbody>");
                    $("#dataTables-student-body").html("");
                }

                for(var i = 0; i < result.length; i++)
                {
                    var UserNo = result[i].UserNo;
                    var PermissionStatus = "";
                    var accountStatus = "正常";

                    switch(result[i].Permission)
                    {
                        case '0':
                            PermissionStatus = "未認證";
                            break;
                        case '1':
                            PermissionStatus = "校內信箱認證";
                            break;
                        case '2':
                            PermissionStatus = "手機認證";
                            break;
                        case '3':
                            PermissionStatus = "學生證認證";
                            break;
                        case '5':
                            PermissionStatus = "完全認證";
                            break;
                        case '100':
                            PermissionStatus = "站務人員(" + result[i].Permission + ")";
                            break;
                        case '255':
                            PermissionStatus = "管理員(" + result[i].Permission + ")";
                            break;
                        default:
                            break;
                    }

                    if(result[i].lock_status == 1)
                    {
                        accountStatus = "鎖定中";
                    }

                    if(result[i].del_status == 1)
                    {
                        accountStatus = "已刪除";
                    }


                    var regDate = new Date(result[i].add_on);


                    $("#dataTables-student-body").append("<tr class='odd gradeX' id='dataTables-row-s-" + UserNo + "'  data-href='user_detail.php?id=" + UserNo + "'></tr>");
                    $("#dataTables-row-s-" + UserNo).html("<td class='hidden-xs'>" + UserNo + "</td>");
                    $("#dataTables-row-s-" + UserNo).append("<td>" + result[i].SID.toUpperCase() + "</td>");
                    $("#dataTables-row-s-" + UserNo).append("<td class='center'>" + result[i].Name + "</td>");
                    $("#dataTables-row-s-" + UserNo).append("<td class='center'>" + result[i].Email + "</td>");
                    $("#dataTables-row-s-" + UserNo).append("<td class='center hidden-xs'>" + PermissionStatus + "</td>");
                    $("#dataTables-row-s-" + UserNo).append("<td class='center hidden-xs'>" + accountStatus + "</td>");
                    $("#dataTables-row-s-" + UserNo).append("<td class='center hidden-xs'>" + regDate.getFullYear('yyyy') + "/" + (regDate.getMonth() + 1) + "/" + regDate.getDate() + "</td>");
                    }
            }
        }
    </script>

</body>

</html>
