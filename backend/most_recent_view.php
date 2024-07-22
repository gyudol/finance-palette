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
$result = mysqli_query($con, "SELECT LEFT(history_id, 1) AS first_digit, COUNT(*) AS count 
          FROM user_view_histories
          WHERE aaid = '$aaid' AND LEFT(history_id, 1) IN ('1', '2', '3', '4', '5', '6') 
          GROUP BY first_digit 
          ORDER BY count DESC 
          LIMIT 1;");

if ($result && mysqli_num_rows($result) > 0) {
    $row = mysqli_fetch_assoc($result);
    $most_frequent_digit = $row['first_digit'];
    echo "$most_frequent_digit";
} else echo "0";    // echo 0이면, toast Message

mysqli_close($con);

?>