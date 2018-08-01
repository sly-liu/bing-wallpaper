<?php

// create 608x1080 size image
// $current_dir = opendir('bingImages');    //opendir()返回一个目录句柄,失败返回false
// while(($file = readdir($current_dir)) !== false) {    //readdir()返回打开目录句柄中的一个条目

// 	if(strpos($file,'1920') !== false){

// 		//打开来源图片
// 		$image = imagecreatefromjpeg('bingImages/'.$file);

// 		//创建新图片
// 		$new_image = imagecreatetruecolor(608, 1080);
// 		$newname = str_replace('1920x1080', '608x1080', $file);

// 		//将原图$image按照指定的宽高，复制到$new_image指定的宽高大小中
// 		imagecopyresampled($new_image, $image, 0, 0, 656, 0, 608, 1080, 608, 1080);

// 		//header('content-type:image/jpeg');
// 		imagejpeg($new_image, 'bingImages/'.$newname);
// 	    echo 'File in Directory bingImages: ' . $file . '<br>';

//     }
// }

// create 576x324 size image
$current_dir = opendir('bingImages');    //opendir()返回一个目录句柄,失败返回false
while(($file = readdir($current_dir)) !== false) {    //readdir()返回打开目录句柄中的一个条目

	if(strpos($file,'1920') !== false){

		//打开来源图片
		$image = imagecreatefromjpeg('bingImages/'.$file);

		//创建新图片
		$new_image = imagecreatetruecolor(576, 324);
		$newname = str_replace('1920x1080', '576x324', $file);

		//将原图$image按照指定的宽高，复制到$new_image指定的宽高大小中
		imagecopyresampled($new_image, $image, 0, 0, 0, 0, 576, 324, 1920, 1080);

		//header('content-type:image/jpeg');
		imagejpeg($new_image, 'bingImages/'.$newname);
	    echo 'File in Directory bingImages: ' . $file . '<br>';

    }
}

?>