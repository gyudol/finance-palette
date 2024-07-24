<?php
$con = mysqli_connect("localhost", "root","","fn_prod");

if(!$con || mysqli_connect_errno()) {
    echo "Failed to connect to MySQL: "  .mysqli_connect_error();
}

mysqli_set_charset($con,"utf8");

// EditText로 원하는 값 가져오기
@$option_id = $_GET['optionId'];


$res = mysqli_query($con, "SELECT 
clp.financial_product_name, clp.credit_product_type_name, clp.cb_company_name, 
LEAST(MIN(COALESCE(credit_score_above_900, 100)), MIN(COALESCE(credit_score_801_to_900, 100)), MIN(COALESCE(credit_score_701_to_800, 100)), MIN(COALESCE(credit_score_601_to_700, 100)), 
MIN(COALESCE(credit_score_501_to_600, 100)), MIN(COALESCE(credit_score_401_to_500, 100)), MIN(COALESCE(credit_score_301_to_400, 100)), MIN(COALESCE(credit_score_below_300, 100))) AS lowest_loan_rate,
clpo.average_credit_loan_rate, clp.join_way, clpo.credit_loan_rate_type_name, c.financial_company_name, c.disclosure_officer, c.homepage_url, 
CONCAT(clp.disclosure_start_date, IFNULL(CONCAT(' ~ ', clp.disclosure_end_date), ' ~ ')) AS disclosure_start_to_end_date
FROM credit_loan_product_options clpo
JOIN credit_loan_products clp ON clpo.financial_product_id = clp.financial_product_id
JOIN financial_companies c ON clp.financial_company_id = c.financial_company_id
WHERE clpo.option_id = '$option_id';");


$result = array();

while($row = mysqli_fetch_array($res)) {
    array_push($result, array('financial_product_name'=>$row[0], 'credit_product_type_name'=>$row[1], 'cb_company_name'=>$row[2], 'lowest_loan_rate'=>$row[3], 
    'average_credit_loan_rate'=>$row[4], 'join_way'=>$row[5], 'credit_loan_rate_type_name'=>$row[6], 'financial_company_name'=>$row[7], 'disclosure_officer'=>$row[8],
    'homepage_url'=>$row[9], 'disclosure_start_to_end_date'=>$row[10]));
}

echo json_encode(array("result"=>$result), JSON_UNESCAPED_UNICODE);

mysqli_close($con);

?>