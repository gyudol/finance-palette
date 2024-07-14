<?php
$con = mysqli_connect("localhost", "root","","fn_prod");

if(!$con || mysqli_connect_errno()) {
    echo "Failed to connect to MySQL: "  .mysqli_connect_error();
}

mysqli_set_charset($con,"utf8");

// EditText로 원하는 값 가져오기
@$fin_prdt_cd = $_GET['finPrdtCd'];


$res = mysqli_query($con, "SELECT 
aspo.pnsn_recp_trm_nm, aspo.pnsn_entr_age_nm, aspo.mon_paym_atm_nm, 
aspo.paym_prd_nm, aspo.pnsn_strt_age_nm, aspo.pnsn_recp_amt
FROM annuitysavingproductsoptions aspo
WHERE aspo.fin_prdt_cd = '$fin_prdt_cd';");


$result = array();

while($row = mysqli_fetch_array($res)) {
    array_push($result, array('pnsn_recp_trm_nm'=>$row[0], 'pnsn_entr_age_nm'=>$row[1], 'mon_paym_atm_nm'=>$row[2], 
    'paym_prd_nm'=>$row[3], 'pnsn_strt_age_nm'=>$row[4], 'pnsn_recp_amt'=>$row[5]));
}

echo json_encode(array("result"=>$result), JSON_UNESCAPED_UNICODE);

mysqli_close($con);

?>