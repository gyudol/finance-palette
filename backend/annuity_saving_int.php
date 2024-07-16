<?php
$con = mysqli_connect("localhost", "root","","fn_prod");

if(!$con || mysqli_connect_errno()) {
    echo "Failed to connect to MySQL: "  .mysqli_connect_error();
}

mysqli_set_charset($con,"utf8");

// EditText로 원하는 값 가져오기
@$fin_prdt_cd = $_GET['finPrdtCd'];


$res = mysqli_query($con, "SELECT 
asp.fin_prdt_nm, asp.pnsn_kind_nm, asp.prdt_type_nm, asp.avg_prft_rate, asp.btrm_prft_rate_1, asp.btrm_prft_rate_2, asp.btrm_prft_rate_3, 
asp.sale_strt_day, asp.join_way, asp.sale_co, asp.etc, asp.mntn_cnt, asp.dcls_rate, asp.guar_rate,
co.kor_co_nm, co.dcls_chrg_man,
CONCAT(asp.dcls_strt_day, IFNULL(CONCAT(' ~ ', asp.dcls_end_day), ' ~ ')) AS dcls_strt_end_day
FROM annuitysavingproducts asp 
JOIN company co ON asp.fin_co_no = co.fin_co_no 
WHERE asp.fin_prdt_cd = '$fin_prdt_cd';");


$result = array();

while($row = mysqli_fetch_array($res)) {
    array_push($result, array('fin_prdt_nm'=>$row[0], 'pnsn_kind_nm'=>$row[1], 'prdt_type_nm'=>$row[2], 'avg_prft_rate'=>$row[3], 'btrm_prft_rate_1'=>$row[4], 
    'btrm_prft_rate_2'=>$row[5], 'btrm_prft_rate_3'=>$row[6], 'sale_strt_day'=>$row[7], 'join_way'=>$row[8], 'sale_co'=>$row[9], 'etc'=>$row[10],
    'mntn_cnt'=>$row[11], 'dcls_rate'=>$row[12], 'guar_rate'=>$row[13], 'kor_co_nm'=>$row[14], 'dcls_chrg_man'=>$row[15], 'dcls_strt_end_day'=>$row[16]));
}

echo json_encode(array("result"=>$result), JSON_UNESCAPED_UNICODE);

mysqli_close($con);

?>