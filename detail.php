<?php
	require('./base.php');
	$GLOBALS['d'] = isset($_GET['d']) ? $_GET['d'] : '';
	$api = new Base();
	$detail = $api -> getImageById($d);
	//print_r($detail)
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="keywords" content="bing,wallpaper,scenery,picture">
	<meta name="description" content="Bing Wallpaper Synchronous Update">
	<title>Bing Daily Picture - <?php echo $detail['caption']; ?></title>
	<link rel="stylesheet" href="style.css">
</head>
<body>
	<?php
	echo '<div class="wrapper" style="background-image: url('.$detail['url'].')">
		<div class="detail">
			<div class="date">'.$detail['date'].'<a href="https://www.bing.com/'.$detail['quiz'].'">today quiz</a><a class="a2a_dd" href="https://www.addtoany.com/share">Share</a></div>
			<div class="title">'.$detail['caption'].'&nbsp;'.$detail['copyrightonly'].'</div>
			<div class="description">'.$detail['detail'].'</div>
			<!-- AddToAny BEGIN -->
			<script async src="https://static.addtoany.com/menu/page.js"></script>
			<!-- AddToAny END -->
		</div>
	</div>'
	?>
</body>
</html>