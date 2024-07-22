<?php
$con = mysqli_connect("localhost", "root","","fn_prod");

if(!$con || mysqli_connect_errno()) {
    echo "Failed to connect to MySQL: "  .mysqli_connect_error();
}

mysqli_set_charset($con,"utf8");

// @ 붙이면 warning 사라짐
@$aaid = $_GET['aaid'];
@$age_distribution = $_GET['ageDist'];
@$investment_tendency = $_GET['investmentTend'];

// 해당 aaid 데이터 존재 여부 확인
$result = mysqli_query($con, "SELECT * FROM user_information WHERE aaid = '$aaid';");
$rowcount = mysqli_num_rows($result);

if ($rowcount > 0) {
    // user_information 테이블에 해당 aaid의 데이터가 이미 존재하는 경우 업데이트 수행
    $query = "UPDATE user_information SET age_distribution = '$age_distribution', investment_tendency = '$investment_tendency' WHERE aaid = '$aaid';";
    if (mysqli_query($con, $query)) {
        echo "Record updated successfully";
    } else {
        echo "Error updating record: " . mysqli_error($con);
    }
} else {
    // user_information 테이블에 해당 aaid의 데이터가 없는 경우 INSERT 수행
    $query = "INSERT INTO user_information(aaid, age_distribution, investment_tendency) 
    VALUES ('$aaid', '$age_distribution', '$investment_tendency');";
    if (mysqli_query($con, $query)) {
        echo "New record created successfully";
    } else {
        echo "Error: " . $query . "<br>" . mysqli_error($con);
    }
}

mysqli_close($con);

?>