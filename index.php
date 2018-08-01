<?php
	require('./base.php');
	$GLOBALS['p'] = isset($_GET['p']) ? $_GET['p'] : 1;
	//$api = new Base();
	$pics = $api -> getImageByPage($p, 32);
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="keywords" content="bing,wallpaper,gallery">
	<meta name="description" content="Bing Wallpaper synchronous update every day">
	<meta name="google-site-verification" content="g3vj5lnprHRLHofBWD2oDZpxp5v2q0j0vQ7sR_eCzSk">
	<title>Bing Wallpaper Gallery</title>
	<link href="images/favicon.ico" rel="shortcut icon">
	<link rel="stylesheet" href="style.css?v=0421">
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
	<header class="flex">
		<h1 class="logo"><a href="/">Bing Wallpaper Gallery</a></h1>
		<script>
		  (function() {
		    var cx = '006401166497944214159:owapppem2eq';
		    var gcse = document.createElement('script');
		    gcse.type = 'text/javascript';
		    gcse.async = true;
		    gcse.src = 'https://cse.google.com/cse.js?cx=' + cx;
		    var s = document.getElementsByTagName('script')[0];
		    s.parentNode.insertBefore(gcse, s);
		  })();
		</script>
		<gcse:search></gcse:search>
		<nav><a class="" href="./viewsrank">Views Ranking</a><a class="" href="./about.html">About Us</a></nav>
	</header>
	<section>
	<?php 
    foreach($pics['images'] as $key=>$value){
        $tumburl = str_replace('1920x1080', '576x324', $value['url']);
        $shortname = trim(strrchr($value['urlbase'], '/'),'/');
        $pos = strpos($shortname, '_');
        $name = substr($shortname, 0, $pos);
        echo '<article class="thumb" itemscope itemtype="http://schema.org/ImageObject">
           <a href="/wallpaper-'.$value['id'].'-'.$name.'.html" itemprop="url" ><img itemprop="image" src="'.$tumburl.'" alt="'.$value['caption'].'"></a><div><time class="date" itemprop="date">'.$value['date'].'</time><span itemprop="name">'.$value['caption'].'</span></div>
        </article>';
    }
    ?>
    </section>
    <div class="pagination">
    	<?php 
		for ($x=1; $x<=$pics['total'] ; $x++) {
			if ($p == $x) {
				echo '<a href="/?p='.$x.'" class="curr">'.$x.'</a>';
			}else{
				echo '<a href="/?p='.$x.'" >'.$x.'</a>';
			}
		} 
		?>
    </div>
    <footer>
    	<p>All resources of the site come from <a href="https://www.bing.com">Bing</a></p>
    	<p>Copyright © 2018 - 2019 <a href="https://www.gifposter.com/">Gifposter</a></p>
    </footer>
    <script>
    	 function randombg(){
             var r=Math.floor(Math.random()*256);
             var g=Math.floor(Math.random()*256);
             var b=Math.floor(Math.random()*256);
             var a=(Math.random()).toFixed(1);
             return "rgba("+r+','+g+','+b+','+a+")";//所有方法的拼接都可以用ES6新特性`其他字符串{$变量名}`替换
         }
         var adom = document.querySelectorAll('.thumb a');
         for(var i=0; i<adom.length; i++){
         	adom[i].style.background = randombg();
         }
    </script>
</body>
</html>