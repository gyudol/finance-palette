<?php
$con = mysqli_connect("localhost", "root","","fn_prod");

if(!$con || mysqli_connect_errno()) {
    echo "Failed to connect to MySQL: "  .mysqli_connect_error();
}

mysqli_set_charset($con,"utf8");

// aaid 받아와서 정보들 null 아닌지 check
$query = "SELECT COUNT(*) AS count FROM user_information";

$res = mysqli_query($con, $query);

if ($row = mysqli_fetch_assoc($res)) {
    $cnt = $row["count"];
    echo $cnt;              
    // 결과를 숫자로 직접 출력
}

mysqli_close($con);

?>