<?php
$con = mysqli_connect("localhost", "root","","fn_prod");

if(!$con || mysqli_connect_errno()) {
    echo "Failed to connect to MySQL: "  .mysqli_connect_error();
}

mysqli_set_charset($con,"utf8");

// EditText로 원하는 값 가져오기
@$optNum = $_GET['optNum'];


$res = mysqli_query($con, "SELECT 
lp3.fin_prdt_nm, lp3.crdt_prdt_type_nm, lp3.cb_name, 
LEAST(MIN(COALESCE(crdt_grad_1, 100)), MIN(COALESCE(crdt_grad_4, 100)), MIN(COALESCE(crdt_grad_5, 100)), 
MIN(COALESCE(crdt_grad_6, 100)), MIN(COALESCE(crdt_grad_10, 100)), MIN(COALESCE(crdt_grad_11, 100)), MIN(COALESCE(crdt_grad_12, 100)), MIN(COALESCE(crdt_grad_13, 100))) AS lend_rate_min,
lpo3.crdt_grad_avg, lp3.join_way, lpo3.crdt_lend_rate_type_nm, co.kor_co_nm, co.dcls_chrg_man,
CONCAT(lp3.dcls_strt_day, IFNULL(CONCAT(' ~ ', lp3.dcls_end_day), ' ~ ')) AS dcls_strt_end_day
FROM creditLoanProductsOptions lpo3
JOIN creditLoanProducts lp3 ON lpo3.fin_prdt_cd = lp3.fin_prdt_cd
JOIN company co ON lp3.fin_co_no = co.fin_co_no
WHERE lpo3.opt_num = '$optNum';");


$result = array();

while($row = mysqli_fetch_array($res)) {
    array_push($result, array('fin_prdt_nm'=>$row[0], 'crdt_prdt_type_nm'=>$row[1], 'cb_name'=>$row[2], 'lend_rate_min'=>$row[3], 
    'crdt_grad_avg'=>$row[4], 'join_way'=>$row[5], 'crdt_lend_rate_type_nm'=>$row[6], 'kor_co_nm'=>$row[7], 'dcls_chrg_man'=>$row[8],
    'dcls_strt_end_day'=>$row[9]));
}

echo json_encode(array("result"=>$result), JSON_UNESCAPED_UNICODE);

mysqli_close($con);

?>