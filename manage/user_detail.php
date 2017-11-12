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
<?php 
    if(!isset($_GET['id']) || empty($_GET['id']) || !is_numeric($_GET['id']))
    {
            turn_to_url("/manage/user.php"); 
    }
    else
    {
        $sql = "UPDATE accounts SET TotalPost = (SELECT count(*) FROM forum_post WHERE UserNo = ? AND del_status = 0), TotalResponse = (SELECT count(*) FROM post_response WHERE UserNo = ? AND del_status = 0), TotalComment = (SELECT count(*) FROM course_response WHERE UserNo = ? AND del_status = 0) WHERE No = ? AND del_status = 0;";
        $data_arr = array($_GET['id'], $_GET['id'], $_GET['id'], $_GET['id']);
        $std = $db->prepare($sql);
        $std->execute ($data_arr);
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
        .tbTitle{
            font-weight: bold;
        }

        .hightlight_red{
            color: red;
            font-weight: bold;
        }

        .lbBlock{
            padding: 5px;
        }
    </style>
</head>

<?php   

    $sql = "SELECT a.No as UserNo, a.SID as SID, d.Name as DeptName, a.Name as Name, a.Email as Email, a.mail_resend as Resend, a.Nickname as Nickname, a.Gender Gender, a.Permission as Permission, a.dob as dob, a.add_on as add_on, a.lock_status as lock_status, a.lock_reason as lock_reason, a.del_status as del_status, a.TotalPost as TotalPost, a.TotalResponse as TotalResponse, a.TotalComment as TotalComment FROM accounts a, departments d WHERE a.DeptNo = d.No AND a.No = ?;";
    $std = $db->prepare($sql);
    $std->execute (array($_GET['id']));
    $rs = $std -> fetch();
    $rc = $std -> rowCount();

    $sql = "SELECT UserIp, UserAgent, add_on FROM log_login WHERE SID = ? AND status = 1 ORDER BY add_on DESC LIMIT 1;";
    $std = $db->prepare($sql);
    $std->execute (array($rs['SID']));
    $rs_login = $std -> fetch();

    $PermissionStatus = "";
    $AccountStatus = "正常";

    if($rs['Permission'] == 0)
    {
       $PermissionStatus = "<i class='fa fa-times'></i> 未認證";
    }
    else
    {
        if($rs['Permission'] >= 1)
        {
            $PermissionStatus = $PermissionStatus . "<i class='fa fa-check-circle'></i> 校內信箱認證<br>";
        }
        if($rs['Permission'] == 2)
        {
            $PermissionStatus = $PermissionStatus . "<i class='fa fa-check-circle'></i> 手機認證<br>";
        }
        if($rs['Permission'] == 3)
        {
            $PermissionStatus = $PermissionStatus . "<i class='fa fa-check-circle'></i> 學生證認證<br>";
        }
        if($rs['Permission'] == 5)
        {
            $PermissionStatus = $PermissionStatus . "<i class='fa fa-check-circle'></i> 手機認證<br>";
            $PermissionStatus = $PermissionStatus . "<i class='fa fa-check-circle'></i> 學生證認證<br>";
        }
        if($rs['Permission'] == 100)
        {
            $PermissionStatus = "<i class='fa fa-balance-scale'></i> 站務人員(" . $rs['Permission'] . ")<br>";
        }
        if($rs['Permission'] == 255)
        {
            $PermissionStatus = "<i class='fa fa-balance-scale'></i> 管理員(" . $rs['Permission'] . ")<br>";
        }
    }

    if($rs['lock_status'] == 1)
    {
        $AccountStatus = "鎖定中";
    }

    if($rs['del_status'] == 1)
    {
        $AccountStatus = "已刪除";
    }

?>

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
                    <h1 class="page-header">會員資料</h1>
                </div>
            </div>
            <?php
                if($rc == 0)
                {
            ?>
            <div class="row">
                <div class="col-xs-12">
                    ※ 無此會員資料，請重新確認會員編號(ID: <?php echo $_GET['id']; ?>)。
                </div>
            </div>
            <?php
                }
                else
                {
            ?>
            <div class="row">
                <div class="col-xs-12">
                    <h3>學號：<?php echo strtoupper($rs['SID']); ?></h3>
                    <h3>系所：<?php echo $rs['DeptName']; ?> <label><a href="#"><i class='fa fa-pencil'></i></a></label></h3>
                    <h3>姓名：<?php echo $rs['Name']; ?> <label><a href="#"><i class='fa fa-pencil'></i></a></label></h3>
                    <button type='button' class='btn btn-default btn-sm' id='btnChangePassword'>修改密碼</button>
                    <?php
                        if($rs['lock_status'] == 0)
                        {
                    ?>
                            <button type='button' class='btn btn-danger btn-sm' id='btnLockAccount'>鎖定會員</button>
                    <?php
                        } else{
                    ?>
                            <button type='button' class='btn btn-info btn-sm' id='btnUnlockAccount'>解除鎖定</button>
                    <?php
                        }
                    ?>
                    <?php
                        if($_SESSION['Permission'] >= 200 && $rs['del_status'] == 0)
                        {
                    ?>
                        <button type='button' class='btn btn-danger btn-sm' id='btnDelAccount'>刪除帳號</button>
                    <?php
                        }
                    ?>
                </div>
                <div class="col-xs-6">
                    <div class="lbBlock">
                        <h4 class="tbTitle">
                            認證狀態
                            <label><a href="#" id="btnEditPermission"><i class='fa fa-pencil'></i></a></label>
                        </h4>
                        <p>
                            <?php echo $PermissionStatus; ?>
                        </p>
                    </div>
                    <div class="lbBlock">
                        <h4 class="tbTitle">備用信箱 <label><a href="#"><i class='fa fa-pencil'></i></a></label></h4>
                        <p>
                            <?php 
                                if(empty($rs['Email']))
                                {
                                    echo "尚未填寫";
                                }
                                else
                                {
                                    echo $rs['Email'];
                                }
                            ?>
                        </p>
                    </div>
                    <div class="lbBlock">
                        <h4 class="tbTitle">評論總數</h4>
                        <p>共 <?php echo $rs['TotalComment']; ?> 篇</p>
                    </div>
                    <div class="lbBlock">
                        <h4 class="tbTitle">上次登入時間</h4>
                        <p><?php echo $rs_login['add_on'] . "（" . round((time() - strtotime(date_format(date_create($rs_login['add_on']), "Y/m/d")))/3600/24) . "天前）"; ?></p>
                    </div>
                    <div class="lbBlock">
                        <h4 class="tbTitle">註冊日期</h4>
                        <p><?php echo date_format(date_create($rs['add_on']), "Y/m/d") . "（" . round((time() - strtotime(date_format(date_create($rs['add_on']), "Y/m/d")))/3600/24) . "天）"; ?></p>
                    </div>
                    <div class="lbBlock">
                        <h4 class="tbTitle">上次登入裝置</h4>
                        <p><?php echo $rs_login['UserAgent']; ?></p>
                    </div>
                </div>
                <div class="col-xs-6">
                    <div class="lbBlock">
                        <h4 class="tbTitle">
                            帳號狀態
                        </h4>
                        <p <?php if($AccountStatus != '正常'){ echo "class='hightlight_red'"; } ?>><?php echo $AccountStatus; ?></p>
                    </div>
                    <div class="lbBlock">
                        <h4 class="tbTitle">生日 <label><a href="#"><i class='fa fa-pencil'></i></a></label></h4>
                        <p><?php echo $rs['dob']; ?></p>
                    </div>
                    <div class="lbBlock">
                        <h4 class="tbTitle">文章總數</h4>
                        <p>共 <?php echo $rs['TotalPost']; ?> 篇 / 回應 <?php echo $rs['TotalResponse']; ?> 篇</p>
                    </div>
                    <div class="lbBlock">
                        <h4 class="tbTitle">上次登入位置</h4>
                        <p><?php echo $rs_login['UserIp']; ?></p>
                    </div>
                    <div class="lbBlock">
                        <h4 class="tbTitle">認證信補寄</h4>
                        <p>
                            <?php
                                switch($rs['Resend'])
                                {
                                    case 0:
                                        echo "未補寄";
                                        break;
                                    case 1:
                                        echo "已申請補寄";
                                        break;
                                    default:
                                        echo "未補寄";
                                        break;
                                }
                            ?>
                        </p>
                    </div>
                </div>
            </div>
            <?php
                }
            ?>
        </div>
    </div>

    <link rel='stylesheet' href='../../css/sweetalert.css'>

    <?php echo $m_FJS; ?>

    <script src="js/jquery.dataTables.min.js"></script>
    <script src="js/dataTables.bootstrap.min.js"></script>
    <script src="js/dataTables.responsive.js"></script>
    <script src='../../js/sweetalert.min.js'></script>

    <script type="text/javascript">
        $("#btnEditPermission").click(function(){
                swal({
                   title: "<i class='fa fa-key'></i><br>會員權限",
                   text: "請輸入權限等級((-1)-未認證、1-信箱、2-手機、3-學生證、5-全部<?php if($_SESSION['Permission'] == 255){ echo "、100-站務人員、200-管理員"; } ?>)<br><br>",
                   type: "input",   
                   showCancelButton: true, 
                   html: true,   
                   closeOnConfirm: false,
                   cancelButtonText: "取　消",
                   confirmButtonText: "送　出",
                   animation: "slide-from-top",   
                   inputPlaceholder: "(-1)-未認證、1-信箱、2-手機、3-學生證、5-完全<?php if($_SESSION['Permission'] == 255){ echo "、100-站務人員、200-管理員"; } ?>" 
                    }, 
                   function(inputValue){   
                        if (inputValue === false) 
                           return false;      
                        if (inputValue === "") 
                        {     
                           swal.showInputError("請輸入權限等級。");     
                           return false;
                        }      
                        if (inputValue === "-1" || inputValue === "1" || inputValue === "2" || inputValue === "3" || inputValue === "5"<?php if($_SESSION['Permission'] == 255){ echo " || inputValue === '100' || inputValue === '200'"; } ?>) 
                        {
                           swal({   
                                title: "確定調整？",   
                                text: "您確定要調整該會員的權限嗎？",   
                                type: "warning",   
                                showCancelButton: true,   
                                cancelButtonText: "取　　消",    
                                confirmButtonText: "確　　定",   
                                closeOnConfirm: false 
                            }, 
                            function(){
                                $.ajax({
                                    url: "u/user_permission_act.php",
                                    type: "POST",
                                    dataType: "json",
                                    data: {
                                        SID: <?php echo $_GET['id']; ?>,
                                        Permission: inputValue
                                    },
                                    success: function(result){
                                        if(result.status == 1)
                                        {
                                            swal({
                                                title: "完成", 
                                                text: "該會員的權限已變更，重新登入後生效。", 
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
                        }
                        else
                        {     
                           swal.showInputError("您輸入的內容有誤。");     
                           return false;   
                        }       
                });
        });

        $("#btnChangePassword").click(function(){
                var newPassword = Math.random().toString(36).substr(18);
                swal({
                   title: "<i class='fa fa-pencil'></i><br>修改密碼",
                   text: "請輸入至少8碼英數混合的密碼。<br><br>",
                   type: "input",   
                   showCancelButton: true, 
                   html: true,   
                   inputValue: newPassword,
                   closeOnConfirm: false,
                   cancelButtonText: "取　消",
                   confirmButtonText: "送　出",
                   animation: "slide-from-top",   
                   inputPlaceholder: "請輸入至少8碼英數混合的密碼" 
                    }, 
                   function(inputValue){ 
                        var reg = /^(?=.*\d)(?=.*[a-zA-Z]).{8,}$/; 
                        if (inputValue === false) 
                           return false;      
                        if (inputValue.length < 8) 
                        {     
                           swal.showInputError("請輸入至少 8 碼。");     
                           return false;
                        }      
                        if (reg.test(inputValue)) 
                        {
                            newPassword = inputValue;
                            swal({
                               title: "<i class='fa fa-pencil'></i><br>確認密碼",
                               text: "安全起見，我們要再次確認您的身分，請重新輸入您的密碼。<br><br>",
                               type: "input",
                               inputType: "password",   
                               showCancelButton: true, 
                               html: true,
                               closeOnConfirm: false,
                               cancelButtonText: "取　消",
                               confirmButtonText: "送　出",
                               animation: "slide-from-top",   
                               inputPlaceholder: "請輸入您的密碼。" 
                            }, 
                            function(inputValue){
                                var reg = /^(?=.*\d)(?=.*[a-zA-Z]).{8,}$/; 
                                if (inputValue === false) 
                                   return false;      
                                if (inputValue.length < 8) 
                                {     
                                   swal.showInputError("您的密碼有誤。");     
                                   return false;
                                }      
                                if (reg.test(inputValue)) 
                                {
                                   swal({   
                                        title: "確定修改？",   
                                        text: "您確定要手動修改該會員的密碼？",   
                                        type: "warning",   
                                        showCancelButton: true,   
                                        cancelButtonText: "取　　消",    
                                        confirmButtonText: "確　　定",   
                                        closeOnConfirm: false 
                                    }, 
                                    function(){
                                        $.ajax({
                                            url: "u/user_edit_act.php",
                                            type: "POST",
                                            dataType: "json",
                                            data: {
                                                UserNo: <?php echo $_GET['id']; ?>,
                                                AdminPassword: inputValue,
                                                NewPassword: newPassword
                                            },
                                            success: function(result){
                                                if(result.status == 1)
                                                {
                                                    swal({
                                                        title: "完成", 
                                                        text: result.msg, 
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
                                }
                                else
                                {     
                                   swal.showInputError("您的密碼有誤。");     
                                   return false;
                                }      
                            });
                        }
                        else
                        {     
                           swal.showInputError("密碼至少包含英數各一碼。");     
                           return false;   
                        }       
                });
        });

        $("#btnLockAccount").click(function(){
            swal({
               title: "<i class='fa fa-lock'></i><br>鎖定帳號",
               text: "請輸入鎖定原因（會顯示給會員看，請輸入正式）<br><br>",
               type: "input",   
               showCancelButton: true, 
               html: true,
               closeOnConfirm: false,
               cancelButtonText: "取　消",
               confirmButtonText: "確　定",
               animation: "slide-from-top",   
               inputPlaceholder: "請輸入鎖定原因。" 
            }, 
            function(inputValue){
                var reason = inputValue;
                if (inputValue === false) 
                   return false;      
                else if (inputValue === "") 
                {     
                   swal.showInputError("您尚未輸入鎖定原因。");     
                   return false;
                }    
                else if (inputValue.length >= 50) 
                {     
                   swal.showInputError("您輸入的原因太長了。");     
                   return false;
                }
                else
                {     
                    swal({
                       title: "<i class='fa fa-pencil'></i><br>確認密碼",
                       text: "安全起見，我們要再次確認您的身分，請重新輸入您的密碼。<br><br>",
                       type: "input",
                       inputType: "password",   
                       showCancelButton: true, 
                       html: true,
                       closeOnConfirm: false,
                       cancelButtonText: "取　消",
                       confirmButtonText: "送　出",
                       animation: "slide-from-top",   
                       inputPlaceholder: "請輸入您的密碼。" 
                    }, 
                    function(inputValue){
                        var reg = /^(?=.*\d)(?=.*[a-zA-Z]).{8,}$/; 
                        if (inputValue === false) 
                           return false;      
                        if (inputValue.length < 8) 
                        {     
                           swal.showInputError("您的密碼有誤。");     
                           return false;
                        }      
                        if (reg.test(inputValue)) 
                        {
                            $.ajax({
                                url: "u/user_edit_act.php",
                                type: "POST",
                                dataType: "json",
                                data: {
                                    UserNo: <?php echo $_GET['id']; ?>,
                                    Lock: true,
                                    Reason: reason,
                                    AdminPassword: inputValue
                                },
                                success: function(result){
                                    if(result.status == 1)
                                    {
                                        swal({
                                            title: "完成", 
                                            text: "該會員已被鎖定，重新登入後生效。", 
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
                        }
                        else
                        {     
                           swal.showInputError("您的密碼有誤。");     
                           return false;
                        }      
                    }); 
                } 
            });
        });

        $("#btnUnlockAccount").click(function(){            
           swal({   
                title: "確定解除？",   
                text: "您確定要解除該會員的鎖定狀態嗎？",   
                type: "warning",   
                showCancelButton: true,   
                cancelButtonText: "取　　消",    
                confirmButtonText: "確　　定",   
                closeOnConfirm: false 
            }, 
            function(){
                swal({
                   title: "<i class='fa fa-pencil'></i><br>確認密碼",
                   text: "安全起見，我們要再次確認您的身分，請重新輸入您的密碼。<br><br>",
                   type: "input",
                   inputType: "password",   
                   showCancelButton: true, 
                   html: true,
                   closeOnConfirm: false,
                   cancelButtonText: "取　消",
                   confirmButtonText: "送　出",
                   animation: "slide-from-top",   
                   inputPlaceholder: "請輸入您的密碼。" 
                }, 
                function(inputValue){
                    var reg = /^(?=.*\d)(?=.*[a-zA-Z]).{8,}$/; 
                    if (inputValue === false) 
                       return false;      
                    if (inputValue.length < 8) 
                    {     
                       swal.showInputError("您的密碼有誤。");     
                       return false;
                    }      
                    if (reg.test(inputValue)) 
                    {
                        $.ajax({
                            url: "u/user_edit_act.php",
                            type: "POST",
                            dataType: "json",
                            data: {
                                UserNo: <?php echo $_GET['id']; ?>,
                                Unlock: true,
                                AdminPassword: inputValue
                            },
                            success: function(result){
                                if(result.status == 1)
                                {
                                    swal({
                                        title: "完成", 
                                        text: "該會員已解除鎖定，重新登入後生效。", 
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
                    }
                    else
                    {     
                       swal.showInputError("您的密碼有誤。");     
                       return false;
                    }      
                }); 
            });
        });

    <?php
        if($_SESSION['Permission'] >= 200)
        {
    ?>
        $("#btnDelAccount").click(function(){
            var admPassword = "";      
           swal({   
                title: "確定刪除？",   
                text: "您確定要刪除此會員帳號嗎？<br><strong>※ 注意，此行為將無法復原，請謹慎確認。</strong>",   
                type: "warning",   
                showCancelButton: true,  
                html: true, 
                cancelButtonText: "取　　消",    
                confirmButtonText: "確　　定",   
                closeOnConfirm: false 
            }, 
            function(){
                swal({   
                    title: "請謹慎考慮",   
                    text: "您目前將執行的操作是<strong>【刪除會員】</strong>。<br>此行為將無法回復，若非必要，請改以鎖定帳號。<br>若謹慎思考後，仍確定要繼續執行，<br>則請稍待片刻，將會自動跳轉至下一步驟。",   
                    type: "info",   
                    timer: 10000,
                    html: true,    
                    showConfirmButton: false
                },
                function(){
                    swal({
                       title: "<i class='fa fa-pencil'></i><br>確認密碼",
                       text: "安全起見，我們要再次確認您的身分，請重新輸入您的密碼。<br><br>",
                       type: "input",
                       inputType: "password",   
                       showCancelButton: true, 
                       html: true,
                       closeOnConfirm: false,
                       cancelButtonText: "取　消",
                       confirmButtonText: "送　出",
                       animation: "slide-from-top",   
                       inputPlaceholder: "請輸入您的密碼。" 
                    }, 
                    function(inputValue){
                        var reg = /^(?=.*\d)(?=.*[a-zA-Z]).{8,}$/; 
                        if (inputValue === false) 
                           return false;      
                        if (inputValue.length < 8) 
                        {     
                           swal.showInputError("您的密碼有誤。");     
                           return false;
                        }      
                        if (reg.test(inputValue)) 
                        {
                            admPassword = inputValue;

                            swal({   
                                title: "確認資料",   
                                text: "您目前將刪除的會員資料如下：<br><strong>編號【<?php echo $rs['UserNo']; ?>】</strong><br><strong>姓名【<?php echo $rs['Name']; ?>】</strong><br><strong>學號【<?php echo strtoupper($rs['SID']); ?>】</strong><br>確認無誤後，請稍待，將會自動跳轉至下一步驟。",   
                                type: "info",   
                                timer: 5000,
                                html: true,    
                                showConfirmButton: false
                            },
                            function(){
                                swal({   
                                    title: "最後確認",   
                                    text: "<strong>若確認要刪除，請輸入 'Y' or 'y' 以繼續。<br>※ 注意，此為最後一步驟，按下送出後，將會直接進行刪除。</strong><br><br>",   
                                    type: "input",  
                                    showCancelButton: true,  
                                    html: true, 
                                    cancelButtonText: "取　　消",    
                                    confirmButtonText: "確　　定",   
                                    closeOnConfirm: false 
                                }, 
                                function(inputValue){
                                    if (inputValue === "Y" || inputValue === "y")
                                    {   
                                        swal.disableButtons();

                                        $.ajax({
                                            url: "u/user_delete_act.php",
                                            type: "POST",
                                            dataType: "json",
                                            data: {
                                                UserNo: <?php echo $_GET['id']; ?>,
                                                AdminPassword: admPassword
                                            },
                                            success: function(result){
                                                if(result.status == 1)
                                                {
                                                    swal({
                                                        title: "完成", 
                                                        text: result.msg, 
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
                                    }
                                    else
                                    {
                                       swal.showInputError("輸入錯誤，請輸入Y/y，或取消動作。");     
                                       return false;
                                    }
                                });
                            });
                        }
                        else
                        {     
                           swal.showInputError("您的密碼有誤。");     
                           return false;
                        }     
                    });
                });
            });
        });
    <?php
        }
    ?>
    </script>
</body>

</html>
