<?php
class DBHelper{
    public function DBHelper(){}
    private static function getConn(){
        $conn = mysqli_connect("localhost","root","","test");
        mysqli_query($conn,"SET NAMES utf8");
        return $conn;
    }
    public static function opearting($sql){
        return mysqli_query(self::getConn(),$sql);
    }
};