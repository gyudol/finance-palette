<?php
$con = mysqli_connect("localhost", "root","","fn_prod");

if(!$con || mysqli_connect_errno()) {
    echo "Failed to connect to MySQL: "  .mysqli_connect_error();
}

mysqli_set_charset($con,"utf8");

$query = "SELECT COUNT(*) AS cnt FROM userinfo_history";

$res = mysqli_query($con, $query);

if ($row = mysqli_fetch_assoc($res)) {
    $cnt = $row["cnt"];
    echo $cnt;              
    // 결과를 숫자로 직접 출력
}

mysqli_close($con);

?>