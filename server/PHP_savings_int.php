<?php
$con = mysqli_connect("localhost", "root","","fn_prod");

if(!$con || mysqli_connect_errno()) {
    echo "Failed to connect to MySQL: "  .mysqli_connect_error();
}

mysqli_set_charset($con,"utf8");

// EditText로 원하는 값 가져오기
@$fin_prdt_cd = $_GET['finPrdtCd'];
@$intrRateType = $_GET['intrRateType'];
@$rsrvType = $_GET['rsrvType'];

$res = mysqli_query($con, "SELECT 
sp.fin_prdt_nm, sp.join_way, sp.mtrt_int, sp.spcl_cnd, sp.join_deny, sp.join_member, sp.etc_note, sp.max_limit, 
co.kor_co_nm, co.dcls_chrg_man, spo.intr_rate_type_nm, spo.rsrv_type_nm,
CONCAT(sp.dcls_strt_day, IFNULL(CONCAT(' ~ ', sp.dcls_end_day), ' ~ ')) AS dcls_strt_end_day,
MAX(CASE WHEN spo.save_trm = 1 THEN COALESCE(NULLIF(spo.intr_rate, 0), spo.intr_rate2) END) AS intr_rate_1,
MAX(CASE WHEN spo.save_trm = 3 THEN COALESCE(NULLIF(spo.intr_rate, 0), spo.intr_rate2) END) AS intr_rate_3,
MAX(CASE WHEN spo.save_trm = 6 THEN COALESCE(NULLIF(spo.intr_rate, 0), spo.intr_rate2) END) AS intr_rate_6,
MAX(CASE WHEN spo.save_trm = 12 THEN COALESCE(NULLIF(spo.intr_rate, 0), spo.intr_rate2) END) AS intr_rate_12,
MAX(CASE WHEN spo.save_trm = 24 THEN COALESCE(NULLIF(spo.intr_rate, 0), spo.intr_rate2) END) AS intr_rate_24,
MAX(CASE WHEN spo.save_trm = 36 THEN COALESCE(NULLIF(spo.intr_rate, 0), spo.intr_rate2) END) AS intr_rate_36,
(SELECT MAX(intr_rate2) FROM savingProductsOptions WHERE fin_prdt_cd = sp.fin_prdt_cd AND intr_rate_type = '$intrRateType' AND rsrv_type = '$rsrvType') AS intr_rate_max
FROM savingproducts sp 
JOIN company co ON sp.fin_co_no = co.fin_co_no 
JOIN savingProductsOptions spo ON sp.fin_prdt_cd = spo.fin_prdt_cd 
WHERE sp.fin_prdt_cd = '$fin_prdt_cd' AND spo.intr_rate_type = '$intrRateType' AND spo.rsrv_type = '$rsrvType';");


$result = array();

while($row = mysqli_fetch_array($res)) {
    array_push($result, array('fin_prdt_nm'=>$row[0], 'join_way'=>$row[1], 'mtrt_int'=>$row[2], 'spcl_cnd'=>$row[3], 'join_deny'=>$row[4], 
    'join_member'=>$row[5], 'etc_note'=>$row[6], 'max_limit'=>$row[7], 'kor_co_nm'=>$row[8], 'dcls_chrg_man'=>$row[9], 'intr_rate_type_nm'=>$row[10],
    'rsrv_type_nm'=>$row[11], 'dcls_strt_end_day'=>$row[12], 'intr_rate_1'=>$row[13], 'intr_rate_3'=>$row[14], 'intr_rate_6'=>$row[15], 'intr_rate_12'=>$row[16], 
    'intr_rate_24'=>$row[17], 'intr_rate_36'=>$row[18], 'intr_rate_max'=>$row[19]));
}

echo json_encode(array("result"=>$result), JSON_UNESCAPED_UNICODE);

mysqli_close($con);

?>