<?php
$con = mysqli_connect("localhost", "root","","fn_prod");

if(!$con || mysqli_connect_errno()) {
    echo "Failed to connect to MySQL: "  .mysqli_connect_error();
}

mysqli_set_charset($con,"utf8");

// EditText로 원하는 값 가져오기
@$fin_prdt_cd = $_GET['finPrdtCd'];
@$intrRateType = $_GET['intrRateType'];


$res = mysqli_query($con, "SELECT 
dp.fin_prdt_nm, dp.join_way, dp.mtrt_int, dp.spcl_cnd, dp.join_deny, dp.join_member, dp.etc_note, dp.max_limit, 
co.kor_co_nm, co.dcls_chrg_man, dpo.intr_rate_type_nm, 
CONCAT(dp.dcls_strt_day, IFNULL(CONCAT(' ~ ', dp.dcls_end_day), ' ~ ')) AS dcls_strt_end_day,
MAX(CASE WHEN dpo.save_trm = 6 THEN COALESCE(NULLIF(dpo.intr_rate, 0), dpo.intr_rate2) END) AS intr_rate_6,
MAX(CASE WHEN dpo.save_trm = 12 THEN COALESCE(NULLIF(dpo.intr_rate, 0), dpo.intr_rate2) END) AS intr_rate_12,
MAX(CASE WHEN dpo.save_trm = 24 THEN COALESCE(NULLIF(dpo.intr_rate, 0), dpo.intr_rate2) END) AS intr_rate_24,
MAX(CASE WHEN dpo.save_trm = 36 THEN COALESCE(NULLIF(dpo.intr_rate, 0), dpo.intr_rate2) END) AS intr_rate_36,
(SELECT MAX(intr_rate2) FROM depositProductsOptions WHERE fin_prdt_cd = dp.fin_prdt_cd AND intr_rate_type = '$intrRateType') AS intr_rate_max
FROM depositproducts dp 
JOIN company co ON dp.fin_co_no = co.fin_co_no 
JOIN depositProductsOptions dpo ON dp.fin_prdt_cd = dpo.fin_prdt_cd 
WHERE dp.fin_prdt_cd = '$fin_prdt_cd' AND dpo.intr_rate_type = '$intrRateType';");


$result = array();

while($row = mysqli_fetch_array($res)) {
    array_push($result, array('fin_prdt_nm'=>$row[0], 'join_way'=>$row[1], 'mtrt_int'=>$row[2], 'spcl_cnd'=>$row[3], 'join_deny'=>$row[4], 
    'join_member'=>$row[5], 'etc_note'=>$row[6], 'max_limit'=>$row[7], 'kor_co_nm'=>$row[8], 'dcls_chrg_man'=>$row[9], 'intr_rate_type_nm'=>$row[10],
    'dcls_strt_end_day'=>$row[11], 'intr_rate_6'=>$row[12], 'intr_rate_12'=>$row[13], 'intr_rate_24'=>$row[14], 'intr_rate_36'=>$row[15],
    'intr_rate_max'=>$row[16]));
}

echo json_encode(array("result"=>$result), JSON_UNESCAPED_UNICODE);

mysqli_close($con);

?>