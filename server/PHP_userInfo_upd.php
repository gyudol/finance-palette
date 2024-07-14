<?php
$con = mysqli_connect("localhost", "root","","fn_prod");

if(!$con || mysqli_connect_errno()) {
    echo "Failed to connect to MySQL: "  .mysqli_connect_error();
}

mysqli_set_charset($con,"utf8");

// @ 붙이면 warning 사라짐
@$user_name = $_GET['user_name'];
@$age_dist = $_GET['age_dist'];
@$prefer = $_GET['prefer'];

// 해당 user_name의 데이터 존재 여부 확인
$result = mysqli_query($con, "SELECT * FROM userinfo_history WHERE user_name = '$user_name';");
$rowcount = mysqli_num_rows($result);

if ($rowcount > 0) {
    // userinfo_history 테이블에 해당 user_name의 데이터가 이미 존재하는 경우 업데이트 수행
    $query = "UPDATE userinfo_history SET age_dist = '$age_dist', prefer = '$prefer' WHERE user_name = '$user_name';";
    if (mysqli_query($con, $query)) {
        echo "Record updated successfully";
    } else {
        echo "Error updating record: " . mysqli_error($con);
    }
} else {
    // userinfo_history 테이블에 해당 user_name의 데이터가 없는 경우 INSERT 수행
    $query = "INSERT INTO userinfo_history(user_name, age_dist, prefer) 
    VALUES ('$user_name', '$age_dist', '$prefer');";
    if (mysqli_query($con, $query)) {
        echo "New record created successfully";
    } else {
        echo "Error: " . $query . "<br>" . mysqli_error($con);
    }
}

mysqli_close($con);

?>