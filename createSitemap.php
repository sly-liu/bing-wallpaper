<?php
require('./base.php');
include 'src/SitemapPHP/Sitemap.php';
$GLOBALS['p'] = isset($_GET['p']) ? $_GET['p'] : 1;
$pics = $api -> getImageByPage($p, 32);
$pics2 = $api -> getImageByPage($p, 35);
//include_once("connect.php"); //连接数据库   


use SitemapPHP\Sitemap;

$sitemap = new Sitemap('https://bing.gifposter.com');	

//$sitemap->setPath('xmls/');

$sitemap->setFilename('sitemap');

$sitemap->addItem('/', '1.0', 'daily', 'Today');
$sitemap->addItem('/about.html', '0.8');
$sitemap->addItem('/viewsrank', '0.8', 'daily');
for ($x=2; $x<=$pics['total'] ; $x++) {
	 $sitemap->addItem('/?p='.$x, '0.8','daily');
} 
for ($x=2; $x<=$pics2['total'] ; $x++) {
	 $sitemap->addItem('/viewsrank?p='.$x, '0.8', 'daily');
} 

$sql = "select * from bing order by id desc";
$datas = array();
$i = 0;
$rs = DBHelper::opearting($sql);
while($row = mysqli_fetch_assoc($rs)){
    $datas[$i] = $row;
    $i++;
}
foreach ($datas as $post) {
	$shortname = trim(strrchr($post['urlbase'], '/'),'/');
    $pos = strpos($shortname, '_');
    $name = substr($shortname, 0, $pos);
    $sitemap->addItem('/wallpaper-'.$post['id'].'-'.$name.'.html', '0.6');
}

$sitemap->createSitemapIndex('https://bing.gifposter.com/sitemap/', 'Today');