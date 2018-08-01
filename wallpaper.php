<?php
	require('./base.php');
	require_once "libs/Mobile_Detect.php";
	$detect = new Mobile_Detect;
	$ifMobile = $detect->isMobile();

	$GLOBALS['d'] = isset($_GET['d']) ? $_GET['d'] : $api ->getLatestTermById();
	$idExsit = $api -> ifIdExsit($d);
	if(!$idExsit) $d = $api -> getLatestTermById();
	$detail = $api -> getImageById($d);
	$prev = $api -> getPrevById($d);
	$next = $api -> getNextById($d);
	$minid = $api -> getOldestTermById();
	$maxid = $api -> getLatestTermById();
	$date = $api -> getTimeById($d);
	if($prev) $prevDetail = $api -> getImageById($prev);
	if($next) $nextDetail = $api -> getImageById($next);
	$tumburl = str_replace('1920x1080', '576x324', $detail['url']);
	function getName($urlbase){
		$shortname = trim(strrchr($urlbase, '/'),'/');
     	$pos = strpos($shortname, '_');
     	$name = substr($shortname, 0, $pos);
     	return $name;
	}
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="keywords" content="bing,wallpaper,gallery">
	<meta name="description" content="<?php echo $detail['detail'] ?>">
	<meta name="google-site-verification" content="g3vj5lnprHRLHofBWD2oDZpxp5v2q0j0vQ7sR_eCzSk">
	<title><?php echo $detail['caption']; ?> - Bing Wallpaper Gallery</title>
	<link href="images/favicon.ico" rel="shortcut icon">
	<link rel="stylesheet" href="style.css?v=382011100">
	<!-- Global site tag (gtag.js) - Google Analytics -->
	<script async src="https://www.googletagmanager.com/gtag/js?id=UA-114373646-1"></script>
	<script>
	  window.dataLayer = window.dataLayer || [];
	  function gtag(){dataLayer.push(arguments);}
	  gtag('js', new Date());

	  gtag('config', 'UA-114373646-1');
	</script>
	<script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
	<script>
	     (adsbygoogle = window.adsbygoogle || []).push({
	          google_ad_client: "ca-pub-7664794252965039",
	          enable_page_level_ads: true
	     });
	</script>
</head>
<body>
	<div class="backdrop"><div class="spinner"><div class="rect2"></div><div class="rect3"></div><div class="rect4"></div><div class="rect5"></div></div></div>
	<div class="fanye"><div class="prevPage"><img src="images/icon-prev.png" alt=""></div><div class="nextPage"><img src="images/icon-next.png" alt=""></div></div>
	<?php
	echo '<div class="wrapper" itemscope itemtype="http://schema.org/ImageObject">
	<span class="info-btn" id="infoBtn"><img src="images/aui-icon-camera.png" alt="wallpaper information">info</span>';
		if($ifMobile){
			echo '<img src="'.str_replace('1920x1080', '608x1080', $detail['url']).'" alt="'.$detail['caption'].'" id="bing_wallpaper" class="mobile_terminal">';
		}else{
			echo '<img src="'.$tumburl.'" alt="thumbnail" id="preset_photo" itemprop="image">
				<img src="'.$detail['url'].'" alt="'.$detail['caption'].'" id="bing_wallpaper" itemprop="image">';
		}
		echo '<div class="detail">
			<div class="date"><time itemprop="date">'.$detail['date'].'</time><a href="https://www.bing.com/'.$detail['copyrightlink'].'">bing search</a></div>
			<div class="title" itemprop="name">'.$detail['caption'].'&nbsp;'.$detail['copyrightonly'].'</div>
			<div class="description" itemprop="description">'.$detail['detail'].'</div>
			<div class="nav-btn">';
				echo '<a href="/" class="icon home" title="Back to Homepage">home</a>';
				if($d == $maxid){
					echo '<a class="icon prev disabled" href="javascript:void(0)">prev</a>';
				}else{
					echo '<a class="icon prev" title="Prev Picture" href="/wallpaper-'.$prev.'-'.getName($prevDetail['urlbase']).'.html">prev</a>';
				}
				if($d == $minid){
					echo '<a class="icon next disabled" href="javascript:void(0)">next</a>';
				}else{
					echo '<a class="icon next" title="Next Picture" href="/wallpaper-'.$next.'-'.getName($nextDetail['urlbase']).'.html">next</a>';
				}
			echo '<span><span class="icon view" title="views">view</span><strong>'.$detail['hits'].'</strong></span>';
			if($detail['islike'] > 0){
				echo '<span id="likeBtn" rel="'.$d.'" class="liked" title="liked">';
			}else{
				echo '<span id="likeBtn" title="like" rel="'.$d.'">';
			}
			echo '<span class="icon like">like</span><strong id="likes">'.$detail['likes'].'</strong></span>';
			echo '<a class="a2a_dd icon share" href="https://www.addtoany.com/share">Share</a>';
			if($ifMobile){
				echo '<a href="'.str_replace('1920x1080', '608x1080', $detail['url']).'" title="download mobile wallpaper" class="icon download" download="bing_wallpaper_'.getName($detail['urlbase']).'_mobile.jpg"></a>';
			}else{
				echo '<a href="'.$detail['url'].'" title="download HD bing wallpaper" class="icon download" download="bing_wallpaper_'.getName($detail['urlbase']).'_1920x1080.jpg"></a>';
			}
			echo '</div>
		</div>
	</div>'
	?>
	<!-- AddToAny BEGIN -->
	<script async src="https://static.addtoany.com/menu/page.js"></script>
	<!-- AddToAny END -->
	<script>
		var likeBtn = document.getElementById('likeBtn');
		var infoBtn = document.getElementById('infoBtn');
		var pcratio = (window.innerWidth/window.innerHeight);
		var imgratio = 1920/1080;
		var preset = document.getElementById('preset_photo');
		var img = document.getElementById('bing_wallpaper');

		if(img.className == ''){
			if(pcratio < imgratio){
				preset.className = 'higher';
				imgWidth = 1920/(1080/window.innerHeight);
				preset.style.marginLeft = (-imgWidth/2 + 'px');

				img.className = 'higher';
				//imgWidth = 1920/(1080/window.innerHeight);
				img.style.marginLeft = (-imgWidth/2 + 'px');
			}else{
				preset.className = '';
				imgHeight = 1080/(1920/window.innerWidth);
				preset.style.marginTop = (-imgHeight/2 + 'px');

				img.className = '';
				//imgHeight = 1080/(1920/window.innerWidth);
				img.style.marginTop = (-imgHeight/2 + 'px');
			}
			
			if(preset.complete){
				document.querySelector('.backdrop').style.display = 'none';
			}
			preset.onload = function(){
				document.querySelector('.backdrop').style.display = 'none';
			};
			if(img.complete){
				preset.style.display = 'none';
			}
			img.onload = function(){
				preset.style.display = 'none';
			};
		}else{
			if(img.complete){
				document.querySelector('.backdrop').style.display = 'none';
			}
			img.onload = function(){
				document.querySelector('.backdrop').style.display = 'none';
			};
		}
		
		document.getElementById('bing_wallpaper').addEventListener('click', function(){
			if(document.querySelector('.detail').style.display != 'none'){
				document.querySelector('.detail').style.display = 'none';
			}else{
				document.querySelector('.detail').style.display = 'block';
			}
		});

		likeBtn.addEventListener('click', function () {
			var id = this.getAttribute('rel');
			var xmlhttp = new XMLHttpRequest();
			xmlhttp.onreadystatechange = function(){
	  			if (xmlhttp.readyState==4 && xmlhttp.status==200){
	  				//var data =  JSON.parse(xmlhttp.responseText);
	    			var data = eval("("+xmlhttp.responseText+")");
	    			console.log(data);
	    			if(data.status == 1){
	    				likeBtn.className += ' liked';
	    				document.getElementById('likes').innerHTML = data.value;
	    			}
	    		}
	  		};
			xmlhttp.open("POST","like.php",true);
			xmlhttp.setRequestHeader("Content-type","application/x-www-form-urlencoded");
			xmlhttp.send("id="+id);
		});

		var prevPage = document.querySelector('.prev').getAttribute('href');
		var nextPage = document.querySelector('.next').getAttribute('href');

		if(prevPage.indexOf('java') == 0){
			document.querySelector('.prevPage').className += ' disabled';
		}

		if(nextPage.indexOf('java') == 0){
			document.querySelector('.nextPage').className += ' disabled';
		}

		document.querySelector('.prevPage').addEventListener('click', function(){
			location.href = prevPage;
		});
		document.querySelector('.nextPage').addEventListener('click', function(){
			location.href = nextPage;
		});
	</script>
</body>
</html>