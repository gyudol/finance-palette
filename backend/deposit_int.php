<?php
$con = mysqli_connect("localhost", "root","","fn_prod");

if(!$con || mysqli_connect_errno()) {
    echo "Failed to connect to MySQL: "  .mysqli_connect_error();
}

mysqli_set_charset($con,"utf8");

// EditText로 원하는 값 가져오기
@$financial_product_id = $_GET['finPrdtId'];
@$interest_rate_type = $_GET['intrRateType'];


$res = mysqli_query($con, "SELECT 
dp.financial_product_name, dp.join_way, dp.interest_rate_after_maturity, dp.preferential_condition, dp.join_restriction, dp.join_target, dp.other_precaution, dp.maximum_limit, 
c.financial_company_name, c.disclosure_officer, c.homepage_url, dpo.interest_rate_type_name, 
CONCAT(dp.disclosure_start_date, IFNULL(CONCAT(' ~ ', dp.disclosure_end_date), ' ~ ')) AS disclosure_start_to_end_date,
MAX(CASE WHEN dpo.saving_term = 6 THEN COALESCE(NULLIF(dpo.interest_rate, 0), dpo.maximum_interest_rate) END) AS interest_rate_6,
MAX(CASE WHEN dpo.saving_term = 12 THEN COALESCE(NULLIF(dpo.interest_rate, 0), dpo.maximum_interest_rate) END) AS interest_rate_12,
MAX(CASE WHEN dpo.saving_term = 24 THEN COALESCE(NULLIF(dpo.interest_rate, 0), dpo.maximum_interest_rate) END) AS interest_rate_24,
MAX(CASE WHEN dpo.saving_term = 36 THEN COALESCE(NULLIF(dpo.interest_rate, 0), dpo.maximum_interest_rate) END) AS interest_rate_36,
(SELECT MAX(maximum_interest_rate) FROM deposit_product_options WHERE financial_product_id = dp.financial_product_id AND interest_rate_type = '$interest_rate_type') AS highest_interest_rate
FROM deposit_products dp 
JOIN financial_companies c ON dp.financial_company_id = c.financial_company_id 
JOIN deposit_product_options dpo ON dp.financial_product_id = dpo.financial_product_id 
WHERE dp.financial_product_id = '$financial_product_id' AND dpo.interest_rate_type = '$interest_rate_type';");


$result = array();

while($row = mysqli_fetch_array($res)) {
    array_push($result, array('financial_product_name'=>$row[0], 'join_way'=>$row[1], 'interest_rate_after_maturity'=>$row[2], 'preferential_condition'=>$row[3], 'join_restriction'=>$row[4], 
    'join_target'=>$row[5], 'other_precaution'=>$row[6], 'maximum_limit'=>$row[7], 'financial_company_name'=>$row[8], 'disclosure_officer'=>$row[9], 'homepage_url'=>$row[10], 'interest_rate_type_name'=>$row[11],
    'disclosure_start_to_end_date'=>$row[12], 'interest_rate_6'=>$row[13], 'interest_rate_12'=>$row[14], 'interest_rate_24'=>$row[15], 'interest_rate_36'=>$row[16],
    'highest_interest_rate'=>$row[17]));
}

echo json_encode(array("result"=>$result), JSON_UNESCAPED_UNICODE);

mysqli_close($con);

?>