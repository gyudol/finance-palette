<?php
$con = mysqli_connect("localhost", "root","","fn_prod");

if(!$con || mysqli_connect_errno()) {
    echo "Failed to connect to MySQL: "  .mysqli_connect_error();
}

mysqli_set_charset($con,"utf8");

@$a = $_GET['a'];
@$b = $_GET['b'];

$query = "SELECT lp3.fin_prdt_nm, lpo3.opt_num, c.kor_co_nm, lp3.crdt_prdt_type_nm, LEAST(MIN(COALESCE(crdt_grad_1, 100)), MIN(COALESCE(crdt_grad_4, 100)), MIN(COALESCE(crdt_grad_5, 100)), 
            MIN(COALESCE(crdt_grad_6, 100)), MIN(COALESCE(crdt_grad_10, 100)), MIN(COALESCE(crdt_grad_11, 100)), MIN(COALESCE(crdt_grad_12, 100)), MIN(COALESCE(crdt_grad_13, 100))) AS lend_rate_min
          FROM creditLoanProductsOptions lpo3
          JOIN creditLoanProducts lp3 ON lpo3.fin_prdt_cd = lp3.fin_prdt_cd
          JOIN company c ON lp3.fin_co_no = c.fin_co_no";

if ($a == 1) {
    $query .= " WHERE c.topFinGrpNo = '020000'";
} elseif ($a == 2) {
    $query .= " WHERE c.topFinGrpNo != '020000'";
}

if ($b == 1) {
    $query .= ($a == 1 || $a == 2) ? " AND lp3.crdt_prdt_type_nm = '일반신용대출'" : " WHERE lp3.crdt_prdt_type_nm = '일반신용대출'";
} elseif ($b == 2) {
    $query .= ($a == 1 || $a == 2) ? " AND lp3.crdt_prdt_type_nm = '마이너스한도대출'" : " WHERE lp3.crdt_prdt_type_nm = '마이너스한도대출'";
} elseif ($b == 3) {
    $query .= ($a == 1 || $a == 2) ? " AND lp3.crdt_prdt_type_nm = '장기카드대출'" : " WHERE lp3.crdt_prdt_type_nm = '장기카드대출'";
}

$query .= " GROUP BY lp3.fin_prdt_nm, c.kor_co_nm, lpo3.crdt_lend_rate_type_nm
            ORDER BY lend_rate_min ASC";

$res = mysqli_query($con, $query);
$result = array();


while($row = mysqli_fetch_array($res)) {
    array_push($result, array('fin_prdt_nm'=>$row[0], 'opt_num'=>$row[1], 'kor_co_nm'=>$row[2],
    'crdt_prdt_type_nm'=>$row[3], 'lend_rate_min'=>$row[4]));
}

echo json_encode(array("result"=>$result), JSON_UNESCAPED_UNICODE);

mysqli_close($con);

?>