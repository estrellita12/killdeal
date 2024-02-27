<html>
<head>
  <meta charset="UTF-8">
  <script src="<?php echo TB_JS_URL; ?>/jquery-1.8.3.min.js"></script>
  <script src="<?php echo TB_JS_URL; ?>/jquery-ui-1.10.3.custom.js"></script>
  <script src="<?php echo TB_JS_URL; ?>/common.js?ver=<?php echo TB_JS_VER;?>"></script>
  <style>
    body {
      margin: 0;
    }

    li {
      list-style: none;
    }

    a:link,
    a:visited {
      text-decoration: none;
    }

    a:hover,
    a:focus,
    a:active {
      text-decoration: none;
    }

    #cont_wrap {
      position: relative;
    }

    #tgs_event_wrap {
      width: 1200px;
      margin: 0 auto;
      box-sizing: border-box;
    }
	#cont_wrap .prod_wrap {
		position:absolute;
		left:50%;
		transform:translate(-50%);
		top:25.5%;
		width:1200px;
		height:100%;
	}
	#cont_wrap .vs_box ul li{position:absolute;left:50%;top:28%;margin-left:-100px;animation:scaleBox 1s 1s infinite forwards;}
	#cont_wrap .vs_box ul li:nth-child(2){top:37%;}
	#cont_wrap .vs_box ul li:nth-child(3){top:46%;}
	#cont_wrap .vs_box ul li:nth-child(4){top:55.5%;}
	#cont_wrap .vs_box ul li:nth-child(5){top:65.1%;}
	
	@keyframes scaleBox {
	0% {transform:scale(1);}

	50% {transform:scale(1.05);}

	100%{transform:scale(1);}

	}

	#cont_wrap .prod_wrap li{position:absolute;left:0;top:0;transition:all 0.5s;}
	#cont_wrap .prod_wrap li:hover{transform:scale(1.2);}
	#cont_wrap .prod_wrap li:nth-child(1){left:16%;top:2.1%;}
	#cont_wrap .prod_wrap li:nth-child(2){left:64%;top:2%;}
	#cont_wrap .prod_wrap li:nth-child(3){left:21%;top:11%;}
	#cont_wrap .prod_wrap li:nth-child(4){left:70%;top:11%;}
	#cont_wrap .prod_wrap li:nth-child(5){left:20%;top:20.3%;}
	#cont_wrap .prod_wrap li:nth-child(6){left:69%;top:20.3%;}
	#cont_wrap .prod_wrap li:nth-child(7){left:17%;top:30%;}
	#cont_wrap .prod_wrap li:nth-child(8){left:65.5%;top:30%;}
	#cont_wrap .prod_wrap li:nth-child(9){left:16%;top:39.6%;}
	#cont_wrap .prod_wrap li:nth-child(10){left:68%;top:39.7%;}
	#cont_wrap .prod_wrap li:nth-child(1) img {width:216px;}
	#cont_wrap .prod_wrap li:nth-child(2) img {width:230px;}
	#cont_wrap .prod_wrap li:nth-child(3) img {width:112px;}
	#cont_wrap .prod_wrap li:nth-child(4) img {width:125px;}
	#cont_wrap .prod_wrap li:nth-child(5) img {width:130px;}
	#cont_wrap .prod_wrap li:nth-child(6) img {width:138px;}
	#cont_wrap .prod_wrap li:nth-child(7) img {width:200px;}
	#cont_wrap .prod_wrap li:nth-child(8) img {width:220px;}
	#cont_wrap .prod_wrap li:nth-child(9) img {width:210px;}
	#cont_wrap .prod_wrap li:nth-child(10) img {width:170px;}

	#cont_wrap .sub_prod_wrap{position:absolute;left:50%;top:65.7%;width:1200px;transform:translate(-50%);}
	#cont_wrap .sub_prod_wrap ul li{position:absolute;left:0;top:320px;}
	#cont_wrap .sub_prod_wrap ul li img{width:75px;}
	#cont_wrap .sub_prod_wrap li:nth-child(1){left:0%;top:331px;}
	#cont_wrap .sub_prod_wrap li:nth-child(2){left:15.9%;top:331px;}
	#cont_wrap .sub_prod_wrap li:nth-child(3){left:23%;top:330px;}
	#cont_wrap .sub_prod_wrap li:nth-child(4){left:33.7%;top:377px;}

	#cont_wrap .sub_prod_wrap li:nth-child(5){left:53.9%;top:350px;}
	#cont_wrap .sub_prod_wrap li:nth-child(6){left:67%;top:335px;}
	#cont_wrap .sub_prod_wrap li:nth-child(7){left:75.6%;}
	#cont_wrap .sub_prod_wrap li:nth-child(8){left:84.8%;top:384px;}

	#cont_wrap .sub_prod_wrap li:nth-child(1) img{width:190px;transform:rotate(-8deg);}
	#cont_wrap .sub_prod_wrap li:nth-child(2) img{width:100px;}
	#cont_wrap .sub_prod_wrap li:nth-child(3) img{width:120px;}
	#cont_wrap .sub_prod_wrap li:nth-child(4) img{width:140px;}

	#cont_wrap .sub_prod_wrap li:nth-child(5) img{width:165px;}
	#cont_wrap .sub_prod_wrap li:nth-child(6) img{width:100px;}
	#cont_wrap .sub_prod_wrap li:nth-child(7) img{width:120px;}
	#cont_wrap .sub_prod_wrap li:nth-child(8) img{width:155px;}
  </style>
</head>

<body>
  <div id="cont_wrap">
    <div id="tgs_event_wrap">
<!--        <img src="https://killdeal.co.kr/img/tgs_autumn_event.jpg" alt="" width="1200"> -->
	   <img src="https://majorworld.hgodo.com/killdeal/event/tgs_autumn_event_pc.jpg" alt="" width="1200">
    </div>
	<div class="vs_box">
		<ul>
		<li><img src="https://majorworld.hgodo.com/killdeal/event/tgs/vs.png" alt="vs"></li>
		<li><img src="https://majorworld.hgodo.com/killdeal/event/tgs/vs.png" alt="vs"></li>
		<li><img src="https://majorworld.hgodo.com/killdeal/event/tgs/vs.png" alt="vs"></li>
		<li><img src="https://majorworld.hgodo.com/killdeal/event/tgs/vs.png" alt="vs"></li>
		<li><img src="https://majorworld.hgodo.com/killdeal/event/tgs/vs.png" alt="vs"></li>
	</div>
	<div class="prod_wrap">
		<ul>
			<li><a href="/shop/view.php?index_no=7816"><img src="https://majorworld.hgodo.com/killdeal/event/tgs/product_img_01.png" alt="상품이미지"></a></li>
			<li><a href="/shop/view.php?index_no=9437"><img src="https://majorworld.hgodo.com/killdeal/event/tgs/product_img_02.png" alt="상품이미지"></a></li>
			<li><a href="/shop/view.php?index_no=9718"><img src="https://majorworld.hgodo.com/killdeal/event/tgs/product_img_03.png" alt="상품이미지"></a></li>
			<li><a href="/shop/view.php?index_no=4552"><img src="https://majorworld.hgodo.com/killdeal/event/tgs/product_img_04.png" alt="상품이미지"></a></li>
			<li><a href="/shop/view.php?index_no=9725"><img src="https://majorworld.hgodo.com/killdeal/event/tgs/product_img_05.png" alt="상품이미지"></a></li>
			<li><a href="/shop/view.php?index_no=6628"><img src="https://majorworld.hgodo.com/killdeal/event/tgs/product_img_06.png" alt="상품이미지"></a></li>
			<li><a href="/shop/view.php?index_no=8012"><img src="https://majorworld.hgodo.com/killdeal/event/tgs/product_img_07.png" alt="상품이미지"></a></li>
			<li><a href="/shop/view.php?index_no=5900"><img src="https://majorworld.hgodo.com/killdeal/event/tgs/product_img_08.png" alt="상품이미지"></a></li>
			<li><a href="/shop/view.php?index_no=9514"><img src="https://majorworld.hgodo.com/killdeal/event/tgs/product_img_09.png" alt="상품이미지"></a></li>
			<li><a href="/shop/view.php?index_no=9970"><img src="https://majorworld.hgodo.com/killdeal/event/tgs/product_img_10.png" alt="상품이미지"></a></li>
		</ul>
	</div>
	<div class="sub_prod_wrap">
		<ul>
		  <li><img src="https://majorworld.hgodo.com/killdeal/event/tgs/product_img_01.png" alt="상품이미지"></li>
		  <li><img src="https://majorworld.hgodo.com/killdeal/event/tgs/product_img_03.png" alt="상품이미지"></li>
		  <li><img src="https://majorworld.hgodo.com/killdeal/event/tgs/product_img_05.png" alt="상품이미지"></li>
		  <li><img src="https://majorworld.hgodo.com/killdeal/event/tgs/product_img_07.png" alt="상품이미지"></li>
		  <li><img src="https://majorworld.hgodo.com/killdeal/event/tgs/product_img_02.png" alt="상품이미지"></li>
		  <li><img src="https://majorworld.hgodo.com/killdeal/event/tgs/product_img_04.png" alt="상품이미지"></li>
		  <li><img src="https://majorworld.hgodo.com/killdeal/event/tgs/product_img_06.png" alt="상품이미지"></li>
		  <li><img src="https://majorworld.hgodo.com/killdeal/event/tgs/product_img_08.png" alt="상품이미지"></li>
		</ul>
	</div>
  </div>
</body>
</html>