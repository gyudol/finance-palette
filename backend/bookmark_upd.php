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
@$mark = $_GET['mark'];

// bookmark_id 생성 [option이 여러 개면 붙여서 보내야 함]
$bookmark_id = $prdtNum . '_' . $financial_product_id . '_' . $opts;

if ($mark === "true") {
    // mark가 true인 경우 user_bookmarks 테이블에서 해당 데이터 삭제
    $query = "DELETE FROM user_bookmarks WHERE bookmark_id = '$bookmark_id'";
    
    if (mysqli_query($con, $query)) {
        echo "Record deleted successfully";
    } else {
        echo "Error: " . $query . "<br>" . mysqli_error($con);
    }
} 
else {
    switch ($prdtNum) {
        case 1:
            $query = "INSERT INTO user_bookmarks (bookmark_id, aaid, financial_product_id_deposit) 
                   VALUES ('$bookmark_id', '$aaid', '$financial_product_id')";
            break;
        case 2:
            $query = "INSERT INTO user_bookmarks (bookmark_id, aaid, financial_product_id_saving) 
                    VALUES ('$bookmark_id', '$aaid', '$financial_product_id')";
            break;
        case 3:
            $query = "INSERT INTO user_bookmarks (bookmark_id, aaid, financial_product_id_annuity) 
                    VALUES ('$bookmark_id', '$aaid', '$financial_product_id')";
            break;
        case 4:
            $query = "INSERT INTO user_bookmarks (bookmark_id, aaid, option_id_rent_house_loan) 
                    VALUES ('$bookmark_id', '$aaid', '$financial_product_id')";
            break;
        case 5:
            $query = "INSERT INTO user_bookmarks (bookmark_id, aaid, option_id_mortgage_loan) 
                    VALUES ('$bookmark_id', '$aaid', '$financial_product_id')";
            break;
        case 6:
            $query = "INSERT INTO user_bookmarks (bookmark_id, aaid, option_id_credit_loan) 
                    VALUES ('$bookmark_id', '$aaid', '$financial_product_id')";
            break;
        default:
            echo "Invalid prdtNum";
    }

    if (mysqli_query($con, $query)) {
        echo "New record created successfully";
    } else {
        echo "Error: " . $query . "<br>" . mysqli_error($con);
    }
}

mysqli_close($con);

?>