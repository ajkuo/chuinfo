<?php require_once "backend/chu_tool.php"; ?>
<?php check_to_content('/f/post_list.php'); ?>
<?php require_once "templates/base.php"; ?>
<!DOCTYPE html>
<html lang="zh-TW">
<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="這是一個專屬於中華大學學生的交流園地，涵蓋了您大學生涯所需要的各項資訊。">

    <title><?php echo $WEB_TITLE; ?></title>

    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/freelancer.css">
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/chuinfo.css">
    <style type="text/css">
        .intro p{
            text-align: justify;
            text-justify: inter-ideograph;
        }

        .navbar-default{
            background-color: rgba(64, 72, 80, 0.3);
        }

        .navbar-default .navbar-brand{
            color: #fff;
            text-shadow:1px 1px 1px #000;
        }

        .navbar-default .navbar-nav>li>a{
            color: #fff;
            text-shadow:1px 1px 1px #000;
        }

        .navbar-default .navbar-toggle{
            border-color: #fff;
        }

        .btn-reg{
            background-color: rgba(255, 240, 255, 1);
        }
    </style>
</head>

<body id="page-top" class="index">
    <nav class="navbar navbar-default navbar-fixed-top">
        <div class="container">
            <div class="navbar-header page-scroll">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="#"><?php echo $WEB_TITLE; ?></a>
            </div>
            <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                <ul class="nav navbar-nav navbar-right">
                    <li class="hidden">
                        <a href="#page-top"></a>
                    </li>
                    <li class="page-scroll">
                        <a href="u/register.php">立即註冊</a>
                    </li>
                    <li class="page-scroll">
                        <a href="u/login.php">登入</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <header>
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="intro-text">
                        <span class="title"><i class="icon fa-graduation-cap"></i></span>
                        <span class="title">大學生涯 精彩可期</span>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <section id="intro">
        <div class="container">
            <div class="row">
                <div class="col-lg-12 text-center">
                    <h2>萬事俱備</h2>
                    <p>凡是您需要的，應有盡有</p>
                    <hr class="star-primary">
                </div>
            </div>
            <div class="row">
                <div class="col-sm-4 intro portfolio-item">
                    <section class="special box">
                        <i class="icon fa-book major"></i>
                        <h3>課程評論</h3>
                        <p>選課，最怕的就是踩到地雷而浪費整整一個學期的時間。所以我們讓學生來給課程評價，好壞公開透明、簡單明瞭，選課本來就該如此簡單！</p>
                    </section>
                </div>
                <div class="col-sm-4 intro">
                    <section class="special box">
                        <i class="icon fa-comments major"></i>
                        <h3>校園交流</h3>
                        <p>在中華大學裡，有超過25個系所、共8,473位學生，如此熱鬧的校園，天天都有新鮮事在發生。相信您絕對不會想錯過同學間最熱門的話題！</p>
                    </section>
                </div>
                <div class="col-sm-4 intro">
                    <section class="special box">
                        <i class="icon fa-lock major"></i>
                        <h3>保證隱私</h3>
                        <p>在這兒，每位會員都經嚴格審核，絕不提供教職員註冊，您的評論也將匿名。我們保證這是一個能夠讓您肆意暢談的校園私密社群，真心不騙！</p>
                    </section>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-8 col-lg-offset-2 text-center">
                    <a href="u/register.php" class="btn btn-lg btn-outline btn-reg">
                        <i class="fa fa-hand-o-right"></i>&nbsp;&nbsp;立即註冊&nbsp;
                    </a>
                </div>
            </div>
        </div>
    </section>

    <section class="about" id="about">
        <div class="container">
            <div class="row">
                <div class="col-lg-12 text-center">
                    <h2>關於我們</h2>
                    <hr class="star-light">
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12 text-center">
                    <p>我們是來自中華大學資訊管理學系的學生，正在煩惱該選哪堂課，所以來問問大家。</p>
                </div>
            </div>
        </div>
    </section>

    <footer class="text-center">
        <div class="footer-above">
            <div class="container">
                <div class="row">
                    <div class="footer-col col-md-12">
                        <ul class="list-inline">
                            <li>
                                <a href="<?php echo $LINK_FB; ?>" class="btn-social btn-outline" target="_blank"><i class="fa fa-fw fa-facebook"></i></a>
                            </li>
                            <li>
                                <a href="mailto:<?php echo $LINK_MAIL; ?>" class="btn-social btn-outline"><i class="fa fa-fw fa-envelope-o"></i></a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <div class="footer-below">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12">
                        Copyright &copy; 2016 <?php echo $WEB_TITLE; ?>
                    </div>
                </div>
            </div>
        </div>
    </footer>

    <script src="js/jquery.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="http://cdnjs.cloudflare.com/ajax/libs/jquery-easing/1.3/jquery.easing.min.js"></script>
    <script src="js/classie.js"></script>
    <script src="js/cbpAnimatedHeader.min.js"></script>
    <script src="js/freelancer.js"></script>

    <!--
    <script src="{% static "js/jqBootstrapValidation.js" %}"></script>
    <script src="{% static "js/signup.js" %}"></script>
    -->
</body>
</html>
