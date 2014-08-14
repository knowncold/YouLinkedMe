<?php
if ($_GET['sec'] == "woshipia") {
	$con = mysql_connect(SAE_MYSQL_HOST_M.':'.SAE_MYSQL_PORT,SAE_MYSQL_USER,SAE_MYSQL_PASS); 
	mysql_select_db("app_ulinkb", $con);

	$result = mysql_query("SELECT * FROM onoff");
	while($row = mysql_fetch_array($result)){
	  $msdata = $row['state'];
	}
	$state = $msdata;
	mysql_close($con);
	echo $state;
}else{
	echo "failed";
}

?>