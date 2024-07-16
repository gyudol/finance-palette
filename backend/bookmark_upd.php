<?php
$con = mysqli_connect("localhost", "root","","fn_prod");

if(!$con || mysqli_connect_errno()) {
    echo "Failed to connect to MySQL: "  .mysqli_connect_error();
}

mysqli_set_charset($con,"utf8");

// @ 붙이면 warning 사라짐
@$prdtNum = $_GET['prdtNum'];
@$finPrdtCd = $_GET['finPrdtCd'];   // 대출 종류는 opt_num을 가져옴
@$opts = $_GET['opts'];
@$aaid = $_GET['aaid'];
@$mark = $_GET['mark'];

// fin_prdt_num_cd 생성 [option이 여러 개면 붙여서 보내야 함]
$fin_prdt_num_cd = $prdtNum . '_' . $finPrdtCd . '_' . $opts;

if ($mark === "true") {
    // mark가 true인 경우 userBookmark 테이블에서 해당 데이터 삭제
    $query = "DELETE FROM userBookmark WHERE fin_prdt_num_cd = '$fin_prdt_num_cd'";
    
    if (mysqli_query($con, $query)) {
        echo "Record deleted successfully";
    } else {
        echo "Error: " . $query . "<br>" . mysqli_error($con);
    }
} 
else {
    switch ($prdtNum) {
        case 1:
            $query = "INSERT INTO userBookmark (fin_prdt_num_cd, aaid, fin_prdt_cd_deposit) 
                   VALUES ('$fin_prdt_num_cd', '$aaid', '$finPrdtCd')";
            break;
        case 2:
            $query = "INSERT INTO userBookmark (fin_prdt_num_cd, aaid, fin_prdt_cd_saving) 
                    VALUES ('$fin_prdt_num_cd', '$aaid', '$finPrdtCd')";
            break;
        case 3:
            $query = "INSERT INTO userBookmark (fin_prdt_num_cd, aaid, fin_prdt_cd_annuity) 
                    VALUES ('$fin_prdt_num_cd', '$aaid', '$finPrdtCd')";
            break;
        case 4:
            $query = "INSERT INTO userBookmark (fin_prdt_num_cd, aaid, opt_num_rentHouse) 
                    VALUES ('$fin_prdt_num_cd', '$aaid', '$finPrdtCd')";
            break;
        case 5:
            $query = "INSERT INTO userBookmark (fin_prdt_num_cd, aaid, opt_num_mortgage) 
                    VALUES ('$fin_prdt_num_cd', '$aaid', '$finPrdtCd')";
            break;
        case 6:
            $query = "INSERT INTO userBookmark (fin_prdt_num_cd, aaid, opt_num_credit) 
                    VALUES ('$fin_prdt_num_cd', '$aaid', '$finPrdtCd')";
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