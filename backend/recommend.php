<?php
$con = mysqli_connect("localhost", "root","","fn_prod");

if(!$con || mysqli_connect_errno()) {
    echo "Failed to connect to MySQL: "  .mysqli_connect_error();
}

mysqli_set_charset($con,"utf8");
/////////////////////////////////////////////////////////////////////////////////// 수정 필요!!!!!!!!!!!!!!!!!!!!
///// 임의로 설정한 것
$query = "SELECT dp.financial_product_name, dp.financial_product_id, c.financial_company_name, MAX(dpo.maximum_interest_rate) AS highest_interest_rate, dpo.interest_rate_type
          FROM deposit_products dp
          JOIN financial_companies c ON dp.financial_company_id = c.financial_company_id
          JOIN deposit_product_options dpo ON dp.financial_product_id = dpo.financial_product_id
          WHERE c.region_code = '020000' AND dp.maximum_limit <= 100000000
          GROUP BY dp.financial_product_name, c.financial_company_name
         ORDER BY highest_interest_rate DESC";

$res = mysqli_query($con, $query);
$result = array();


while($row = mysqli_fetch_array($res)) {
    array_push($result, array('financial_product_name'=>$row[0], 'financial_product_id'=>$row[1], 'financial_company_name'=>$row[2],
    'highest_interest_rate'=>$row[3], 'interest_rate_type'=>$row[4]));
}

echo json_encode(array("result"=>$result), JSON_UNESCAPED_UNICODE);

mysqli_close($con);

?>