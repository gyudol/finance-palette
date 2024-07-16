<?php
$con = mysqli_connect("localhost", "root","","fn_prod");

if(!$con || mysqli_connect_errno()) {
    echo "Failed to connect to MySQL: "  .mysqli_connect_error();
}

mysqli_set_charset($con,"utf8");
/////////////////////////////////////////////////////////////////////////////////// 수정 필요!!!!!!!!!!!!!!!!!!!!
///// 임의로 설정한 것
$query = "SELECT dp.fin_prdt_nm, dp.fin_prdt_cd, c.kor_co_nm, MAX(dpo.intr_rate2) AS highest_intr_rate, dpo.intr_rate_type
          FROM depositproducts dp
          JOIN company c ON dp.fin_co_no = c.fin_co_no
          JOIN depositproductsoptions dpo ON dp.fin_prdt_cd = dpo.fin_prdt_cd
          WHERE c.topFinGrpNo = '020000' AND dp.max_limit <= 100000000
          GROUP BY dp.fin_prdt_nm, c.kor_co_nm
         ORDER BY highest_intr_rate DESC";

$res = mysqli_query($con, $query);
$result = array();


while($row = mysqli_fetch_array($res)) {
    array_push($result, array('fin_prdt_nm'=>$row[0], 'fin_prdt_cd'=>$row[1], 'kor_co_nm'=>$row[2],
    'highest_intr_rate'=>$row[3], 'intr_rate_type'=>$row[4]));
}

echo json_encode(array("result"=>$result), JSON_UNESCAPED_UNICODE);

mysqli_close($con);

?>