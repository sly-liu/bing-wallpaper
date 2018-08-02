<?php

date_default_timezone_set('America/Los_Angeles');

require_once  'php-sdk-7.2.2/autoload.php';
// 引入鉴权类
use Qiniu\Auth;
// 引入上传类
use Qiniu\Storage\BucketManager;

include_once("connect.php"); //连接数据库   

/*class DBHelper{
    public function DBHelper(){}
    private static function getConn(){
        //$conn = mysqli_connect("localhost","i4129677_wp1","mb8Be^kX^k=8","i4129677_wp1");
        $conn = mysqli_connect("localhost","root","","test");
        mysqli_query($conn,"SET NAMES utf8");
        return $conn;
    }
    public static function opearting($sql){
        return mysqli_query(self::getConn(),$sql);
    }
};*/

class Base{

    function autoPostToTwitter ($title , $url, $pic) {
        require_once('codebird/codebird.php');
        \Codebird\Codebird::setConsumerKey("");
        $cb = \Codebird\Codebird::getInstance();
        $cb->setToken("");
         
        $params = array(
          'status' => $title.' '.$url.' #bing #wallpaper',
          'media[]' => $pic
        );
        $reply = $cb->statuses_updateWithMedia($params);
    }

    function postToQiniu ($fetch_url, $img_name) {
        // 需要填写你的 Access Key 和 Secret Key
        $bucket = 'bing';
        $accessKey = '';
        $secretKey = '';
        // 构建鉴权对象
        $auth = new Auth($accessKey, $secretKey);
        // 生成上传 Token
        $token = $auth->uploadToken($bucket);
        $bucketManager = new BucketManager($auth);
        list($ret, $err) = $bucketManager->fetch($fetch_url, $bucket, $img_name);
        if ($err !== null) {
             var_dump($err);
        } else {
            // print_r($ret);
        }
    }


    function getImgFromBing($idx, $n){
        $newday = date('Ymd',strtotime("tomorrow"));
        $query = 'select count(id) as num from bing where startdate = '.$newday;
        $qrs = DBHelper::opearting($query);
        while($row = mysqli_fetch_assoc($qrs)){
            $data = $row;
        }

        if($data['num'] > 0) return;
        
        $url = 'http://global.bing.com/HPImageArchive.aspx?format=js&idx='.$idx.'&n='.$n.'&pid=hp&FORM=HPCNEN&setmkt=en-us&setlang=en-us';
        $html = file_get_contents($url);
        $html = mb_convert_encoding( $html, 'UTF-8', 'UTF-8,GBK,GB2312,BIG5' );
        $imgs = json_decode($html,true);
        $obj = $imgs['images'];
        
        foreach($obj as $key=>$value){
             $img_name = substr(strrchr($value['url'], "/"),1);
             $fetch_url = 'http://www.bing.com'.$value['url'];
             $path = 'bingImages'; 
             $per = 0.3;
             $newname = str_replace('1920x1080', '576x324', $img_name);
             $newname_m = str_replace('1920x1080', '608x1080', $img_name);
             
             $this->download($path.'/'.$img_name, $fetch_url); //读取并保存图片

             $this->compressImg($path.'/'.$img_name, $path.'/'.$newname, $per);

             $this->cropImg($path.'/'.$img_name, $path.'/'.$newname_m);

             $newurl = substr(strrchr($value['url'], "/"),1);
             $localurl = '/'.$path.'/'.$newurl;
             $qiniu_url = 'p2lksrezh.bkt.gdipper.com/'.$newurl;
             $desc = str_replace('\'', '"', $value['desc']);
             $caption = str_replace('\'', ' ', $value['caption']);

             $shortname = trim(strrchr($value['urlbase'], '/'),'/');
             $pos = strpos($shortname, '_');
             $name = substr($shortname, 0, $pos);

             $sql = "insert into bing(startdate,enddate,fullstartdate,hsh,url,urlbase,copyrightonly,caption,detail,quiz,date,qiniu_url) values('".$value['startdate']."','".$value['enddate']."','".$value['fullstartdate']."','".$value['hsh']."','".$localurl."','".$value['urlbase']."','".$value['copyrightonly']."','".$caption."','".$desc."','".$value['quiz']."','".$value['date']."','".$qiniu_url."')";
            $rs = DBHelper::opearting($sql);

            $newestid = $this->getLatestTermById();
            $wpurl = 'https://bing.gifposter.com/wallpaper-'.$newestid.'-'.$name.'.html';

            $this->postToQiniu($fetch_url,$img_name);
            $this->autoPostToTwitter ($value['caption'] , $wpurl, $path.'/'.$img_name);
        }
    }

    // 从数据库中获取图片信息,1页12条
    function getImageByPage($pageNo=1, $pageSize=12){
        $sql = "select * from bing order by enddate desc limit ".($pageNo-1)*$pageSize.",".$pageSize;
        $rs = DBHelper::opearting($sql);
        $data = array();
        $i = 0;
        while($row = mysqli_fetch_assoc($rs)){
            $data[$i] = $row;
            $i++;
        }
        $result = array();
        $result['images'] = $data;
        $sum = self::getCount();
        $result['total'] = ceil($sum/$pageSize);

        return $result;
    }

    // 从数据库中获取图片信息,1页12条,按观看次数排序
    function getImageByViewsOrder($pageNo=1, $pageSize=12){
        $sql = "select * from bing order by counter desc limit ".($pageNo-1)*$pageSize.",".$pageSize;
        $rs = DBHelper::opearting($sql);
        $data = array();
        $i = 0;
        while($row = mysqli_fetch_assoc($rs)){
            $data[$i] = $row;
            $i++;
        }
        $result = array();
        $result['images'] = $data;
        $sum = self::getCount();
        $result['total'] = ceil($sum/$pageSize);

        return $result;
    }

    // 根据id从数据库中获取单个图片信息
    function getImageById($id){
        $ip = self::get_real_ip();
        $maxid = self::getLatestTermById();
        $exsit = self::ifIdExsit($id);

        if($id == '' || $exsit == 0) $id = $maxid;

        //Adds one to the counter
        $rsa = DBHelper::opearting("UPDATE bing SET counter = counter + 1 WHERE id = ".$id);

        //Retrieves the current count
        $rsr = DBHelper::opearting("SELECT counter FROM bing WHERE id = ".$id);
        $count = mysqli_fetch_row($rsr);

        $ip_sql = "select ip from binglike where pic_id='$id' and ip='$ip'";  
        $rsp = DBHelper::opearting($ip_sql); 
        $countp = mysqli_num_rows($rsp);  

        //Displays the count on your site
        //print "$count[0]";

        $sql = "select * from bing where id = ".$id." limit 1";
        $rs = DBHelper::opearting($sql);
        $data = array();
        $i = 0;
        while($row = mysqli_fetch_assoc($rs)){
            $data[$i] = $row;
            $data[$i]['hits'] = $count[0];
            $data[$i]['islike'] = $countp;
            $i++;
        }
        return $data[0];
    }

    function download($name, $url){
         if(!is_dir(dirname($name))){
             mkdir(dirname($name));
         }
         if(file_exists($name)){
            return;
         }
         $str = file_get_contents($url);
         file_put_contents($name, $str);
         //输出一些东西,要不窗口一直黑着,感觉怪怪的
         //echo strlen($str);
         //echo "\n";
    }

    /**
    * 查询总条数
    */
    function getCount(){
        $sql = 'select count(id) as num from bing';
        $data = array();
        $rs = DBHelper::opearting($sql);
        while($row = mysqli_fetch_assoc($rs)){
            $data = $row;
        }
        return $data['num'];
    }

    /**
    * select prev item
    */
    function getPrevById($id){
        $curtime = $this -> getTimeById($id);
        $latestId = $this -> getLatestTermById();
        if($latestId == $id) return;
        $sql = 'select min(fullstartdate) from bing where fullstartdate > '.$curtime;
        $data = array();
        $rs = DBHelper::opearting($sql);
        while($row = mysqli_fetch_assoc($rs)){
            $data = $row;
        }
        $time = $data['min(fullstartdate)'];
        return $this -> getIdByTime($time);
    }

    /**
    * select next item
    */
    function getNextById($id){
        $curtime = $this -> getTimeById($id);
        $oldestId = $this -> getOldestTermById();
        if($oldestId == $id) return;
        $sql = 'select max(fullstartdate) from bing where fullstartdate < '.$curtime;
        $data = array();
        $rs = DBHelper::opearting($sql);
        while($row = mysqli_fetch_assoc($rs)){
            $data = $row;
        }
        $time = $data['max(fullstartdate)'];
        return $this -> getIdByTime($time);
    }

    /**
    * select latest item
    */
    function getLatestTermById(){
        $sql = "SELECT MAX(fullstartdate) FROM bing";
        $rs = DBHelper::opearting($sql);
        $data = array();
        while($row = mysqli_fetch_assoc($rs)){
            $data = $row;
        }
        $latestTime = $data['MAX(fullstartdate)'];
        return $this -> getIdByTime($latestTime);
    }

    /**
    * select oldest item
    */
    function getOldestTermById(){
        $sql = "SELECT MIN(fullstartdate) FROM bing";
        $rs = DBHelper::opearting($sql);
        $data = array();
        while($row = mysqli_fetch_assoc($rs)){
            $data = $row;
        }
        $oldestTime = $data['MIN(fullstartdate)'];
        return $this -> getIdByTime($oldestTime);
    }
    
    /**
    * get time by id
    */
    function getTimeById($id){
        $sql = 'select fullstartdate from bing where id = '.$id;
        $rs = DBHelper::opearting($sql);
        $data = array();
        while($row = mysqli_fetch_assoc($rs)){
            $data = $row;
        }
        return $data['fullstartdate'];
    }

    /**
    * get time by id
    */
    function getIdByTime($time){
        $sql = 'select id from bing where fullstartdate = '.$time;
        $rs = DBHelper::opearting($sql);
        $data = array();
        while($row = mysqli_fetch_assoc($rs)){
            $data = $row;
        }
        return $data['id'];
    }

    /**
    * if id exsit
    */
    function ifIdExsit($id){
        $sql = 'select * from bing where id = '.$id;
        $rs = DBHelper::opearting($sql);
        $data = array();
        while($row = mysqli_fetch_assoc($rs)){
            $data = $row;
        }
        return count($data);
    }

    /**
    * compress img
    */
    function compressImg($imgpath, $newname, $per){
        //打开来源图片
        $image = imagecreatefromjpeg($imgpath);

        //定义百分比，缩放到0.1大小
        $percent = $per;

        // 将图片宽高获取到
        list($width, $height) = getimagesize($imgpath);

        //设置新的缩放的宽高
        $new_width = $width * $percent;
        $new_height = $height * $percent;

        //创建新图片
        $new_image = imagecreatetruecolor($new_width, $new_height);

        //将原图$image按照指定的宽高，复制到$new_image指定的宽高大小中
        imagecopyresampled($new_image, $image, 0, 0, 0, 0, $new_width, $new_height, $width, $height);

        //header('content-type:image/jpeg');
        imagejpeg($new_image, $newname);
    }

    /**
    * crop img
    */
    function cropImg($imgpath, $newname){
        //打开来源图片
        $image = imagecreatefromjpeg($imgpath);

        //创建新图片
        $new_image = imagecreatetruecolor(608, 1080);

        //将原图$image按照指定的宽高，复制到$new_image指定的宽高大小中
        imagecopyresampled($new_image, $image, 0, 0, 656, 0, 608, 1080, 608, 1080);

        //header('content-type:image/jpeg');
        imagejpeg($new_image, $newname);
    }

    /**
    * traverse dir
    */
    function traverse($path = '.') {
        $current_dir = opendir($path);    //opendir()返回一个目录句柄,失败返回false
        while(($file = readdir($current_dir)) !== false) {    //readdir()返回打开目录句柄中的一个条目
            $sub_dir = $path . DIRECTORY_SEPARATOR . $file;    //构建子目录路径
            if($file == '.' || $file == '..') {
                continue;
            } else if(is_dir($sub_dir)) {    //如果是目录,进行递归
                echo 'Directory ' . $file . ':<br>';
                traverse($sub_dir);
            } else {    //如果是文件,直接输出
                

                $per = 0.3;
                $newname = str_replace('1920x1080', '576x324', $file);
                $this -> compressImg($path.'/'.$file, $path.'/'.$newname, $per);

                // $newname_m = str_replace('1920x1080', '608x1080', $file);
                // $this -> cropImg($path.'/'.$file, $path.'/'.$newname_m);
                // echo 'File in Directory ' . $path . ': ' . $file . '<br>';
            }
        }
    }

    /**
    * get client ip
    */
    function get_real_ip(){   
        $ip=false;   
        if(!empty($_SERVER['HTTP_CLIENT_IP'])){   
            $ip=$_SERVER['HTTP_CLIENT_IP'];   
        }  
        if(!empty($_SERVER['HTTP_X_FORWARDED_FOR'])){   
            $ips=explode (', ', $_SERVER['HTTP_X_FORWARDED_FOR']);   
            if($ip){ array_unshift($ips, $ip); $ip=FALSE; }  
            for ($i=0; $i < count($ips); $i++){  
                if(!eregi ('^(10│172.16│192.168).', $ips[$i])){  
                    $ip=$ips[$i];  
                    break;  
                }  
            }  
        }  
        return ($ip ? $ip : $_SERVER['REMOTE_ADDR']);   
    } 
}

$api = new Base();

// 取得共计16张图片
//$pics1 = $api -> getImgFromBing(7,8);
//$pics2 = $api -> getImgFromBing(0,7);
$api -> getImgFromBing(-1,1);

//$api -> traverse('bingImages');