<?php
$con = mysqli_connect("localhost", "root","","fn_prod");

if(!$con || mysqli_connect_errno()) {
    echo "Failed to connect to MySQL: "  .mysqli_connect_error();
}

mysqli_set_charset($con,"utf8");

// @ 붙이면 warning 사라짐
@$prdtNum = $_GET['prdtNum'];
@$financial_product_id = $_GET['finPrdtId'];   // 대출 종류는 opt_num을 가져옴
@$opts = $_GET['opts'];
@$aaid = $_GET['aaid'];
@$cnt = $_GET['cnt'];
@$viewHistoryContains = $_GET['viewHistoryContains'];

// history_id 생성 [option이 여러 개면 붙여서 보내야 함]
$history_id = $prdtNum . '_' . $financial_product_id . '_' . $opts;

if ($viewHistoryContains === "true") {
    // viewHistoryContains가 true인 경우 user_view_histories 테이블에서 view_date_time 현재 시간으로 업데이트
    $query = "UPDATE user_view_histories SET view_date_time = NOW() WHERE history_id = '$history_id' AND aaid = '$aaid';";
    
    if (mysqli_query($con, $query)) {
        echo "Record updated successfully<br>";
    } else {
        echo "Error: " . $query . "<br>" . mysqli_error($con) . "<br>";
    }
} 
else {
    // cnt 50이면 하나 삭제 하고 추가
    if($cnt == 50) {
        $query = "DELETE FROM user_view_histories
                            WHERE view_date_time = (
                            SELECT MIN(view_date_time)
                            FROM user_view_histories) AND aaid = '$aaid';";

        if (mysqli_query($con, $query)) {
            echo "Record deleted successfully<br>";
        } else {
            echo "Error: " . $query . "<br>" . mysqli_error($con) . "<br>";
        }
    }

    $query = "";

    switch ($prdtNum) {
        case 1:
            $query = "INSERT INTO user_view_histories (history_id, aaid, financial_product_id_deposit, view_date_time) 
                   VALUES ('$history_id', '$aaid', '$financial_product_id', NOW())";
            break;
        case 2:
            $query = "INSERT INTO user_view_histories (history_id, aaid, financial_product_id_saving, view_date_time) 
                    VALUES ('$history_id', '$aaid', '$financial_product_id', NOW())";
            break;
        case 3:
            $query = "INSERT INTO user_view_histories (history_id, aaid, financial_product_id_annuity, view_date_time) 
                    VALUES ('$history_id', '$aaid', '$financial_product_id', NOW())";
            break;
        case 4:
            $query = "INSERT INTO user_view_histories (history_id, aaid, option_id_rent_house_loan, view_date_time) 
                    VALUES ('$history_id', '$aaid', '$financial_product_id', NOW())";
            break;
        case 5:
            $query = "INSERT INTO user_view_histories (history_id, aaid, option_id_mortgage_loan, view_date_time) 
                    VALUES ('$history_id', '$aaid', '$financial_product_id', NOW())";
            break;
        case 6:
            $query = "INSERT INTO user_view_histories (history_id, aaid, option_id_credit_loan, view_date_time) 
                    VALUES ('$history_id', '$aaid', '$financial_product_id', NOW())";
            break;
        default:
            echo "Invalid prdtNum<br>";
    }

    if (mysqli_query($con, $query)) {
        echo "New record created successfully<br>";
    } else {
        echo "Error: " . $query . "<br>" . mysqli_error($con) . "<br>";
    }
}

mysqli_close($con);

?>