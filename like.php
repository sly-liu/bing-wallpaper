<?php
header('Content-Type:application/json; charset=utf-8'); 
include_once("connect.php"); //连接数据库   
    
$ip = get_real_ip(); //获取用户IP   
$id = $_POST['id'];   
if(!isset($id) || empty($id)) exit;   
$data = array();

$ip_sql = "select ip from binglike where pic_id='$id' and ip='$ip'";  
$rs = DBHelper::opearting($ip_sql); 
$count = mysqli_num_rows($rs);  
if($count == 0){ //如果没有记录   
    $sql = "update bing set likes=likes+1 where id='$id'"; //更新数据   
    DBHelper::opearting($sql);  
    $sql_in = "insert into binglike (pic_id,ip) values ('$id','$ip')"; //写入数据   
    DBHelper::opearting($sql_in);   
    $result = DBHelper::opearting("select likes from bing where id='$id'");   
    $row = mysqli_fetch_array($result);   
    $love = $row['likes']; //获取赞数值  
    $data['status'] = 1;
    $data['value'] = $love;
    exit(json_encode($data));
}else{   
    $data['status'] = 2;
    $data['msg'] = 'Thank you...';
    exit(json_encode($data));
}  

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