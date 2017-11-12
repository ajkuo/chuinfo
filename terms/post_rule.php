<?php require_once "../templates/base.php"; ?>
<!DOCTYPE html>
<html lang="zh-TW">
<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <title><?php echo $WEB_TITLE; ?></title>

    <link rel='stylesheet' href='../css/base.css'>
    <link rel='stylesheet' href='../css/style.css'>
    <link rel='stylesheet' href='../css/chuinfo.css'>
    <link rel='stylesheet' href='../css/bootstrap.min.css'>
    
	<style type="text/css">
		.control-group{
			margin-top: 10px;
		}

		html, body{
			height: 100%;
		}

		.wrapper {
		    min-height: 100%;
		    position: relative;
		}

		section .container{
			padding-top: 30px;
			padding-bottom: 177px;
		}

		@media(max-width:767px) {
			section .container{
				padding-top: 10px;
			}
		}

		footer{
			height: 157px;
			margin-top: -157px;
		    position: relative;
		}

		.para-text{
			margin-top: 20px;
		}

		h4{
			font-weight: bold;
			padding: 5px;
		}

		p{
			line-height: 28px;
		}

		.main_list{
			line-height: 32px;
		}

		.main_list > li{
			padding-top: 15px;
		}
		.detail_list{
    		list-style-type:upper-roman;
    		padding-top: 10px;
    		padding-bottom: 10px;
			line-height: 28px;
		}
		.slogan{
			font-weight: bold;
			font-size: large;
		}
	</style>

</head>

<body id="page-top" class="index">
    <nav class="navbar navbar-default navbar-fixed-top">
        <div class="container">
            <div class="navbar-header page-scroll">
                <a class="navbar-brand" href="../index.php"><?php echo $WEB_TITLE; ?></a>
            </div>
        </div>
    </nav>

	<div class="wrapper">
		<section id="all_content">
			<div class="container">
	            <div class="row">
	                <div class="col-xs-12 col-md-12 text-center">
	                	<h1>《 文章規範 》</h1>
	                </div>
	                <div class="hidden-xs col-md-3"></div>
	                <div class="col-xs-12 col-md-6 text-left" style="padding: 15px;">
	                	<div class="para-text">
		                	<h4>宗旨</h4>
		                	<p>
		                		　　嗨，非常歡迎您的加入！這是一個專屬於 中華大學 學生的交流園地，我們完全獨立於學校，並不屬於校方，因此各位同學可以放心評論、留言。
		                	</p>
		                	<p>
		                		　　 中華大學資訊網 想要建立的是一個<span class="slogan">「尊重、包容、友善」</span>的交流環境，也就是希望能夠成為<strong>「相互尊重、多元包容、開放友善」</strong>的平台，並期許能夠將此風氣從虛擬帶回現實，令中華大學成為一個更加進步、更加優質的校園。
		                	</p>
		                	<p>
		                		　　基於上述原則，我們會盡可能的減少束縛，盡量讓同學們保持 <strong>自治、自制</strong> 的原則，務請同學們配合，共勉之。
		                	</p>
		                	<p class="text-right">
		                		中華大學資訊網<br>
		                		2016/08/06
		                	</p>
	                	</div>
	                	<div class="para-text">
	                		<h4>規範內容</h4>
	                		<p>
	                			　　下列規範項目，適用於全站能夠供用戶發佈內容處，包括但不限於：課程評論、校園交流。全體用戶發佈內容時即表示您以閱讀並同意下述規範：
	                		</p>
	                		<p>
	                			<ol class="main_list">
	                				<li>
	                					請勿發表惡意言論，包括但不限於：人身攻擊、使用不雅字眼、挑釁、恐嚇、威脅或謾罵。
	                				</li>
	                				<li>
	                					禁止歧視言論，包括但不限於：種族、性別、性向、世系、民族、族群、膚色、職業、地區、學歷、薪資、年齡、殘疾、宗教信仰、婚姻狀況、政治傾向，請同學務必<strong> 包容異己，審慎發言</strong>。
	                				</li>
	                				<li>
	                					本站目前除校園交流區的「靠北」分類為限制級發言區，其他區域請勿發表情色、血腥、暴力、猥褻或可能令人感到不適之內容，即使是限制級發言區，亦切勿發表違反中華民國法令規範之內容，請自重。
	                				</li>
	                				<li>
	                					本站提供「匿名」發言功能，目的是希望能夠促進交流，切勿使用匿名（或非匿名）發表不實言論。
	                				</li>
	                				<li>
	                					禁止任何商業行為或廣告，並應避免文章置入性行銷，下列情形除外：
	                				</li>
		                			<ol class="detail_list">
		                				<li>
		                					中華大學<strong> 校內社團 </strong> 或 <strong> 跨校（包含中華大學）聯合活動 </strong>宣傳。
		                				</li>
		                				<li>
		                					中華大學<strong> 附近店家 </strong>宣傳，「附近」的範圍合理即可，新竹市區亦可接受，請同學自由心證。
		                				</li>
		                				<li>
		                					對中華大學學生有<strong> 特別優惠 </strong>之商家宣傳，請同學自由心證。
		                				</li>
		                				<li>
		                					基於<strong> 公益 </strong>或<strong> 急難救助 </strong>之精神的宣傳，藉此發表內容者，請詳細敘述，不得單純宣傳。
		                				</li>
		                				<li>
		                					其他合於一般道德標準，並非單純商業廣告者。
		                				</li>
		                			</ol>
	                				<li>
	                					其他不適當行為，包括但不限於：惡意鬧板、無意義文章、重覆內容，由站務人員依一般道德標準及法令規範自由心證。
	                				</li>
	                			</ol>
	                		</p>
	                	</div>
	                	<br>
	                	<div class="para-text">
	                		<h4>其他</h4>
	                		<p>
	                			　　中華大學資訊網 保留隨時修訂上述規範之權利，並得不另行通知， 中華大學資訊網 之用戶有定期檢視相關規範之義務。
	                		</p>
	                	</div>
	                	<br><br>
	                	<div class="para-text text-center text-muted">
	                		<h5>最後修訂於 西元 2016 年 08 月 06 日</h5>
	                	</div><br><br>
	                </div>
	                <div class="hidden-xs col-md-3"></div>
	            </div>
			</div>
		</section>
	</div>
    
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
                        Copyright &copy; <?php echo $WEB_TITLE; ?>
                    </div>
                </div>
            </div>
        </div>
    </footer>

	<script src='../js/jquery.min.js'></script>
    <script src='../js/bootstrap.min.js'></script>
    <script src='../js/freelancer.js'></script>

</body>
</html>

