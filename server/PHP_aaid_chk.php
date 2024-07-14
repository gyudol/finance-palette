<?php
$con = mysqli_connect("localhost", "root","","fn_prod");

if(!$con || mysqli_connect_errno()) {
    echo "Failed to connect to MySQL: "  .mysqli_connect_error();
}

mysqli_set_charset($con,"utf8");

// @ 붙이면 warning 사라짐
@$aaid = $_GET['aaid'];

// 해당 user_name의 데이터 존재 여부 확인
$result = mysqli_query($con, "SELECT * FROM userInfo WHERE aaid = '$aaid';");
$rowcount = mysqli_num_rows($result);

if ($rowcount == 0) {
    // userinfo 테이블에 해당 aaid 데이터가 존재하지 않는 경우 INSERT
    $query = "INSERT INTO userInfo(aaid) VALUES ('$aaid');";
    if (mysqli_query($con, $query)) {
        echo "New record created successfully";
    } else {
        echo "Error: " . $query . "<br>" . mysqli_error($con);
    }
}

mysqli_close($con);

?>