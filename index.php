<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>IP</title>
    <meta name="Keywords" content="IP">
    <meta name="Description" content="IP">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=2.0, minimum-scale=1.0, user-scalable=no">
    <meta name="format-detection" content="telephone=no">

</head>
<body>
<?php
function get_ip() {
	//Just get the headers if we can or else use the SERVER global
	if ( function_exists( 'apache_request_headers' ) ) {
		$headers = apache_request_headers();
	} else {
		$headers = $_SERVER;
	}
	//Get the forwarded IP if it exists
	if ( array_key_exists( 'X-Forwarded-For', $headers ) && filter_var( $headers['X-Forwarded-For'], FILTER_VALIDATE_IP, FILTER_FLAG_IPV4 ) ) {
		$the_ip = $headers['X-Forwarded-For'];
	} elseif ( array_key_exists( 'HTTP_X_FORWARDED_FOR', $headers ) && filter_var( $headers['HTTP_X_FORWARDED_FOR'], FILTER_VALIDATE_IP, FILTER_FLAG_IPV4 )
	) {
		$the_ip = $headers['HTTP_X_FORWARDED_FOR'];
	} else {	
		$the_ip = filter_var( $_SERVER['REMOTE_ADDR'], FILTER_VALIDATE_IP, FILTER_FLAG_IPV4 );
	}
	return $the_ip;
}

function get_loc($ip){
  $url = "http://freeapi.ipip.net/".$ip; 
    $ch = curl_init(); 
    curl_setopt($ch, CURLOPT_URL, $url); 
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); 
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT,20); 
    curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/3.0 (compatible; MSIE 5.01; Windows NT 5.0)');
    $content = curl_exec($ch); 
    if ($content === FALSE) {
        return "cURL Error: " . curl_error($ch);
    }
    curl_close($ch); 
    $ipArray = json_decode($content);    
    return $ipArray;
}

$ip="0.0.0.0";
if(isset($_POST['ip'])&&filter_var( $_POST['ip'], FILTER_VALIDATE_IP, FILTER_FLAG_IPV4 )){
    $ip=$_POST['ip'];
} elseif(isset($_GET['ip'])&&filter_var( $_GET['ip'], FILTER_VALIDATE_IP, FILTER_FLAG_IPV4 )) {
    $ip=$_GET['ip'];
} else {
    $ip=get_ip();
}

$ipipArray = get_loc($ip);
include ("IP.class.php");
$ipLocalData= IP::find($ip);
echo $ip."<br/>";
echo $ipipArray[0].$ipipArray[1].$ipipArray[2].$ipipArray[3].' '.$ipipArray[4]."<br/>";
echo $ipLocalData[0].$ipLocalData[1].$ipLocalData[2].' '.$ipLocalData[3];
?>
<br/>

<form name="f" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
<input type="text" name="ip">
<br />
<input type="submit" value="Submit" />
</form>
<br/>
<p style="font-size:small;">Powered by <a href="http://www.ipip.net/" target="_blank">www.ipip.net</a></p>
</body>
</html>
