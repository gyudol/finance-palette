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
@$cnt = $_GET['cnt'];
@$isContainedViewHistory = $_GET['isContainedViewHistory'];

// fin_prdt_num_cd 생성 [option이 여러 개면 붙여서 보내야 함]
$fin_prdt_num_cd = $prdtNum . '_' . $finPrdtCd . '_' . $opts;

if ($isContainedViewHistory === "true") {
    // isContainedViewHistory가 true인 경우 userViewHistory 테이블에서 viewDateTime 현재 시간으로 업데이트
    $query = "UPDATE userViewHistory SET viewDateTime = NOW() WHERE fin_prdt_num_cd = '$fin_prdt_num_cd';";
    
    if (mysqli_query($con, $query)) {
        echo "Record updated successfully<br>";
    } else {
        echo "Error: " . $query . "<br>" . mysqli_error($con) . "<br>";
    }
} 
else {
    // cnt 50이면 하나 삭제 하고 추가
    if($cnt == 50) {
        $query = "DELETE FROM userViewHistory
                            WHERE viewDateTime = (
                            SELECT MIN(viewDateTime)
                            FROM userViewHistory);";

        if (mysqli_query($con, $query)) {
            echo "Record deleted successfully<br>";
        } else {
            echo "Error: " . $query . "<br>" . mysqli_error($con) . "<br>";
        }
    }

    $query = "";

    switch ($prdtNum) {
        case 1:
            $query = "INSERT INTO userViewHistory (fin_prdt_num_cd, aaid, fin_prdt_cd_deposit, viewDateTime) 
                   VALUES ('$fin_prdt_num_cd', '$aaid', '$finPrdtCd', NOW())";
            break;
        case 2:
            $query = "INSERT INTO userViewHistory (fin_prdt_num_cd, aaid, fin_prdt_cd_saving, viewDateTime) 
                    VALUES ('$fin_prdt_num_cd', '$aaid', '$finPrdtCd', NOW())";
            break;
        case 3:
            $query = "INSERT INTO userViewHistory (fin_prdt_num_cd, aaid, fin_prdt_cd_annuity, viewDateTime) 
                    VALUES ('$fin_prdt_num_cd', '$aaid', '$finPrdtCd', NOW())";
            break;
        case 4:
            $query = "INSERT INTO userViewHistory (fin_prdt_num_cd, aaid, opt_num_rentHouse, viewDateTime) 
                    VALUES ('$fin_prdt_num_cd', '$aaid', '$finPrdtCd', NOW())";
            break;
        case 5:
            $query = "INSERT INTO userViewHistory (fin_prdt_num_cd, aaid, opt_num_mortgage, viewDateTime) 
                    VALUES ('$fin_prdt_num_cd', '$aaid', '$finPrdtCd', NOW())";
            break;
        case 6:
            $query = "INSERT INTO userViewHistory (fin_prdt_num_cd, aaid, opt_num_credit, viewDateTime) 
                    VALUES ('$fin_prdt_num_cd', '$aaid', '$finPrdtCd', NOW())";
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