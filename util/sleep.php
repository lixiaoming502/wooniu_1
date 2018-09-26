<?php 
ignore_user_abort();//关闭浏览器仍然执行
set_time_limit(0);//让程序一直执行下去
$interval=3;//每隔一定时间运行
do{
    $msg=date("Y-m-d H:i:s");
    file_put_contents("log.log",$msg,FILE_APPEND);//记录日志
    sleep($interval);//等待时间，进行下一次操作。
}while(true);
?>