<?php
$con = mysqli_connect("localhost", "root","","fn_prod");

if(!$con || mysqli_connect_errno()) {
    echo "Failed to connect to MySQL: "  .mysqli_connect_error();
}

mysqli_set_charset($con,"utf8");

// EditText로 원하는 값 가져오기
@$optNum = $_GET['optNum'];


$res = mysqli_query($con, "SELECT 
lp2.fin_prdt_nm, lp2.loan_lmt, lp2.loan_inci_expn, lp2.erly_rpay_fee, lp2.dly_rate, lp2.join_way, 
co.kor_co_nm, co.dcls_chrg_man, lpo2.lend_rate_type_nm, lpo2.rpay_type_nm,
lend_rate_min, lend_rate_avg, lend_rate_max, 
CONCAT(lp2.dcls_strt_day, IFNULL(CONCAT(' ~ ', lp2.dcls_end_day), ' ~ ')) AS dcls_strt_end_day,
lend_rate_min AS lend_rate_min1, lpo2.mrtg_type_nm
FROM mortgageLoanProductsOptions lpo2
JOIN mortgageLoanProducts lp2 ON lpo2.fin_prdt_cd = lp2.fin_prdt_cd
JOIN company co ON lp2.fin_co_no = co.fin_co_no
WHERE lpo2.opt_num = '$optNum';");


$result = array();

while($row = mysqli_fetch_array($res)) {
    array_push($result, array('fin_prdt_nm'=>$row[0], 'loan_lmt'=>$row[1], 'loan_inci_expn'=>$row[2], 'erly_rpay_fee'=>$row[3], 'dly_rate'=>$row[4], 
    'join_way'=>$row[5], 'kor_co_nm'=>$row[6], 'dcls_chrg_man'=>$row[7], 'lend_rate_type_nm'=>$row[8], 'rpay_type_nm'=>$row[9], 'lend_rate_min'=>$row[10],
    'lend_rate_avg'=>$row[11], 'lend_rate_max'=>$row[12], 'dcls_strt_end_day'=>$row[13], 'lend_rate_min1'=>$row[14], 'mrtg_type_nm'=>$row[15]));
}

echo json_encode(array("result"=>$result), JSON_UNESCAPED_UNICODE);

mysqli_close($con);

?>