<?php
$con = mysqli_connect("localhost", "root","","fn_prod");

if(!$con || mysqli_connect_errno()) {
    echo "Failed to connect to MySQL: "  .mysqli_connect_error();
}

mysqli_set_charset($con,"utf8");

// @ 붙이면 warning 사라짐
@$history_id = $_GET['historyId'];
@$aaid = $_GET['aaid'];

// 해당 history_id의 데이터 존재 여부 확인
$result = mysqli_query($con, "SELECT LEFT(history_id, 1) AS product_number
          FROM user_view_histories
          WHERE aaid = '$aaid'
          ORDER BY view_date_time DESC 
          LIMIT 1;");

if ($result && mysqli_num_rows($result) > 0) {
    $row = mysqli_fetch_assoc($result);
    $most_recent_view = $row['product_number'];
    echo "$most_recent_view";
} else echo "0";    // echo 0이면, toast Message

mysqli_close($con);

?>