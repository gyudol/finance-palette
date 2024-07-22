
<?php
$con = mysqli_connect("localhost", "root","","fn_prod");

if(!$con || mysqli_connect_errno()) {
    echo "Failed to connect to MySQL: "  .mysqli_connect_error();
}

mysqli_set_charset($con,"utf8");

// EditText로 원하는 값 가져오기
@$financial_product_id = $_GET['finPrdtId'];
@$interest_rate_type = $_GET['intrRateType'];
@$accrual_type = $_GET['accrualType'];

$res = mysqli_query($con, "SELECT 
sp.financial_product_name, sp.join_way, sp.interest_rate_after_maturity, sp.preferential_condition, sp.join_restriction, sp.join_target, sp.other_precaution, sp.maximum_limit, 
c.financial_company_name, c.disclosure_officer, spo.interest_rate_type_name, spo.accrual_type_name,
CONCAT(sp.disclosure_start_date, IFNULL(CONCAT(' ~ ', sp.disclosure_end_date), ' ~ ')) AS disclosure_start_to_end_date,
MAX(CASE WHEN spo.saving_term = 1 THEN COALESCE(NULLIF(spo.interest_rate, 0), spo.maximum_interest_rate) END) AS interest_rate_1,
MAX(CASE WHEN spo.saving_term = 3 THEN COALESCE(NULLIF(spo.interest_rate, 0), spo.maximum_interest_rate) END) AS interest_rate_3,
MAX(CASE WHEN spo.saving_term = 6 THEN COALESCE(NULLIF(spo.interest_rate, 0), spo.maximum_interest_rate) END) AS interest_rate_6,
MAX(CASE WHEN spo.saving_term = 12 THEN COALESCE(NULLIF(spo.interest_rate, 0), spo.maximum_interest_rate) END) AS interest_rate_12,
MAX(CASE WHEN spo.saving_term = 24 THEN COALESCE(NULLIF(spo.interest_rate, 0), spo.maximum_interest_rate) END) AS interest_rate_24,
MAX(CASE WHEN spo.saving_term = 36 THEN COALESCE(NULLIF(spo.interest_rate, 0), spo.maximum_interest_rate) END) AS interest_rate_36,
(SELECT MAX(maximum_interest_rate) FROM savings_product_options WHERE financial_product_id = sp.financial_product_id AND interest_rate_type = '$interest_rate_type' AND accrual_type = '$accrual_type') AS highest_interest_rate
FROM savings_products sp 
JOIN financial_companies c ON sp.financial_company_id = c.financial_company_id 
JOIN savings_product_options spo ON sp.financial_product_id = spo.financial_product_id 
WHERE sp.financial_product_id = '$financial_product_id' AND spo.interest_rate_type = '$interest_rate_type' AND spo.accrual_type = '$accrual_type';");


$result = array();

while($row = mysqli_fetch_array($res)) {
    array_push($result, array('financial_product_name'=>$row[0], 'join_way'=>$row[1], 'interest_rate_after_maturity'=>$row[2], 'preferential_condition'=>$row[3], 'join_restriction'=>$row[4], 
    'join_target'=>$row[5], 'other_precaution'=>$row[6], 'maximum_limit'=>$row[7], 'financial_company_name'=>$row[8], 'disclosure_officer'=>$row[9], 'interest_rate_type_name'=>$row[10],
    'accrual_type_name'=>$row[11], 'disclosure_start_to_end_date'=>$row[12], 'interest_rate_1'=>$row[13], 'interest_rate_3'=>$row[14], 'interest_rate_6'=>$row[15], 'interest_rate_12'=>$row[16], 
    'interest_rate_24'=>$row[17], 'interest_rate_36'=>$row[18], 'highest_interest_rate'=>$row[19]));
}

echo json_encode(array("result"=>$result), JSON_UNESCAPED_UNICODE);

mysqli_close($con);

?>