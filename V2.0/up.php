<?php

if ($_GET['type'] && ($_GET['sec'] == "woshipia")){
	if ($_GET['type'] == "data") {
		$data = $_GET['data'];
	}
}


$con = mysql_connect(SAE_MYSQL_HOST_M.':'.SAE_MYSQL_PORT,SAE_MYSQL_USER,SAE_MYSQL_PASS); 


$dati = date("h:i:sa");
mysql_select_db("app_ulinkb", $con);

$sql ="UPDATE temprt SET timestamp='$dati',data = '$data'
WHERE sign = '233'";

if(!mysql_query($sql,$con)){
    die('Error: ' . mysql_error());
}else{
    echo "yes";
}

mysql_close($con);
?>
