<?php

//global $base_dir="/ebook";

function makehtml($file,$content){
	$fp = fopen($file,'w');
	fwrite($fp,$content);
	fclose($fp);
}

function myflush(){
	flush();
	ob_flush();
}

function myfile_get_content($url) {
	/**if (function_exists('file_get_contents')) {
		$file_contents = @file_get_contents($url);
	}**/
	//$proxy_url = "http://39.107.103.131:8088/contents/myproject1/test1.php?url=";
	//$url = $proxy_url.base64_encode($url);
	writeLog("start get url [".$url."] file contents") ;
	$ch = curl_init();
	$timeout = 30;
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
	curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_13_4) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/66.0.3359.139 Safari/537.36');
	curl_setopt ($ch, CURLOPT_REFERER, "https://m.xiaoshuoli.com/i14173/");  
	$file_contents = curl_exec($ch);
	curl_close($ch);
	$encode = mb_detect_encoding($file_contents, array("ASCII",'UTF-8',"GB2312","GBK",'BIG5'));
    $file_contents = mb_convert_encoding($file_contents, "UTF-8",$encode);
	return $file_contents;
}

function pagesleep(){
	echo "<br>��ͣ1�������ɼ�...
		<script language=\"javascript\">setTimeout(\"gonextpage();\",1000);
		function gonextpage(){location.href=window.location;}</script><a href='javascript:gonextpage();'>���������һҳ</a>";
}

function setlocation($location){
	echo "<script>window.location='".$location."'</script>";
}

function dhtmlspecialchars($string, $flags = null) {
	if(is_array($string)) {
		foreach($string as $key => $val) {
			$string[$key] = dhtmlspecialchars($val, $flags);
		}
	} else {
		if($flags === null) {
			$string = str_replace(array('&', '"', '<', '>'), array('&amp;', '&quot;', '&lt;', '&gt;'), $string);
			if(strpos($string, '&amp;#') !== false) {
				$string = preg_replace('/&amp;((#(\d{3,5}|x[a-fA-F0-9]{4}));)/', '&\\1', $string);
			}
		} else {
			if(PHP_VERSION < '5.4.0') {
				$string = htmlspecialchars($string, $flags);
			} else {
				if(strtolower(CHARSET) == 'utf-8') {
					$charset = 'UTF-8';
				} else {
					$charset = 'ISO-8859-1';
				}
				$string = htmlspecialchars($string, $flags, $charset);
			}
		}
	}
	return $string;
}


/*
sql注入检测
 */
function inject_check($sql_str) {
	//if(preg_match('/^test/i',$file))
	return preg_match('/select|insert|and|or|update|delete|\'|\/\*|\*|\.\.\/|\.\/|union|into|load_file|outfile/i', $sql_str);
}

/*
 */
function verify_id($id=null) {
	if (!$id) { exit('id为空'); }
	elseif (inject_check($id)) { exit('存在注入'); }
	elseif (!is_numeric($id)) { exit('不是数字'); }
	$id = intval($id); //

	return $id;
}


function str_check( $str ) {
	if (!get_magic_quotes_gpc()) { // �ж�magic_quotes_gpc�Ƿ��
		$str = addslashes($str);
	}
	if (inject_check($str)) { exit('�ύ�Ĳ����Ƿ���'); } // ע���ж�
	$str = str_replace("_", "\_", $str); // �� '_'���˵�
	$str = str_replace("%", "\%", $str); // �� '%'���˵�
	$str = htmlspecialchars($str); // html���ת��

	return $str;
}

/*
 �������ƣ�post_check()
 �������ã����ύ�ı༭���ݽ��д���
 �Ρ�������$post: Ҫ�ύ������
 �� �� ֵ��$post: ���ع��˺������
 */
function post_check($post) {
	if (!get_magic_quotes_gpc()) { // �ж�magic_quotes_gpc�Ƿ�Ϊ��
		$post = addslashes($post); // ����magic_quotes_gpcû�д򿪵�������ύ���ݵĹ���
	}
	$post = str_replace("_", "\_", $post); // �� '_'���˵�
	$post = str_replace("%", "\%", $post); // �� '%'���˵�
	$post = nl2br($post); // �س�ת��
	$post = htmlspecialchars($post); // html���ת��

	return $post;
}


/**
 ���������ַ�����ת��
 **/
function convert($t_Val){
	$t_Val = str_replace("&", "&amp;",$t_Val);
	$t_Val = str_replace("<", "&lt;",$t_Val);
	$t_Val = str_replace(">", "&gt;",$t_Val);
	if ( get_magic_quotes_gpc() )
	{
		$t_Val = str_replace("\\\"", "&quot;",$t_Val);
		$t_Val = str_replace("\\''", "&#039;",$t_Val);
	}
	else
	{
		$t_Val = str_replace("\"", "&quot;",$t_Val);
		$t_Val = str_replace("'", "&#039;",$t_Val);
	}
	return $t_Val;
}

function deldir($dir) {
  //��ɾ��Ŀ¼�µ��ļ���
  $dh=opendir($dir);
  while ($file=readdir($dh)) {
    if($file!="." && $file!="..") {
      $fullpath=$dir."/".$file;
      if(!is_dir($fullpath)) {
          unlink($fullpath);
      } else {
          deldir($fullpath);
      }
    }
  }
  closedir($dh);
  //ɾ����ǰ�ļ��У�
  if(rmdir($dir)) {
    return true;
  } else {
    return false;
  }
}

function mkdir_force($dir){
	if(file_exists($dir)){
		deldir($dir);
	}
	mkdir($dir, 0777);
}

function isSpider(){
    $ua = strtolower($_SERVER['HTTP_USER_AGENT']);
    if(!empty($ua)){
        $spiderAgentArr = array(
            "Baiduspider",
            "Googlebot",
            "360Spider",
            "Sosospider",
            "sogou spider"
        );
        foreach($spiderAgentArr as $val){
            $spiderAgent = strtolower($val);
            if(strpos($ua, $spiderAgent) !== false){
                return true;
            }
        }
        return false;
    } else {
        return false;
    }
}

function writeLog($msg){
	 $logFile = WEB_ROOT.'logs/'.date('Y-m-d').'.txt';
	 $msg = date('Y-m-d H:i:s').' >>> '.$msg."\r\n";
	 file_put_contents($logFile,$msg,FILE_APPEND );
}


?>