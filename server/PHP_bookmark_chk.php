<?php
$con = mysqli_connect("localhost", "root","","fn_prod");

if(!$con || mysqli_connect_errno()) {
    echo "Failed to connect to MySQL: "  .mysqli_connect_error();
}

mysqli_set_charset($con,"utf8");

// @ 붙이면 warning 사라짐
@$fin_prdt_num_cd = $_GET['fin_prdt_num_cd'];
@$aaid = $_GET['aaid'];

// 해당 fin_prdt_num_cd의 데이터 존재 여부 확인
$result = mysqli_query($con, "SELECT * FROM userBookmark WHERE fin_prdt_num_cd = '$fin_prdt_num_cd' AND aaid = '$aaid';");
$rowcount = mysqli_num_rows($result);

if ($rowcount > 0) echo "true";
else echo "false";

mysqli_close($con);

?>