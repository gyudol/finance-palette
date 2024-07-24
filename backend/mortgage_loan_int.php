<?php
$con = mysqli_connect("localhost", "root","","fn_prod");

if(!$con || mysqli_connect_errno()) {
    echo "Failed to connect to MySQL: "  .mysqli_connect_error();
}

mysqli_set_charset($con,"utf8");

// EditText로 원하는 값 가져오기
@$option_id = $_GET['optionId'];


$res = mysqli_query($con, "SELECT 
mlp.financial_product_name, mlp.loan_limit, mlp.loan_incidental_expenses, mlp.early_repayment_fee, mlp.delinquency_rate, mlp.join_way, 
c.financial_company_name, c.disclosure_officer, c.homepage_url, mlpo.loan_rate_type, mlpo.loan_repayment_type, mlpo.mortgage_type, 
minimum_loan_rate, average_loan_rate, maximum_loan_rate, 
CONCAT(mlp.disclosure_start_date, IFNULL(CONCAT(' ~ ', mlp.disclosure_end_date), ' ~ ')) AS disclosure_start_to_end_date,
minimum_loan_rate AS lowest_loan_rate
FROM mortgage_loan_product_options mlpo
JOIN mortgage_loan_products mlp ON mlpo.financial_product_id = mlp.financial_product_id
JOIN financial_companies c ON mlp.financial_company_id = c.financial_company_id
WHERE mlpo.option_id = '$option_id';");


$result = array();

while($row = mysqli_fetch_array($res)) {
    array_push($result, array('financial_product_name'=>$row[0], 'loan_limit'=>$row[1], 'loan_incidental_expenses'=>$row[2], 'early_repayment_fee'=>$row[3], 'delinquency_rate'=>$row[4], 
    'join_way'=>$row[5], 'financial_company_name'=>$row[6], 'disclosure_officer'=>$row[7], 'homepage_url'=>$row[8], 'loan_rate_type'=>$row[9], 'loan_repayment_type'=>$row[10], 'mortgage_type'=>$row[11], 
    'minimum_loan_rate'=>$row[12], 'average_loan_rate'=>$row[13], 'maximum_loan_rate'=>$row[14], 'disclosure_start_to_end_date'=>$row[15], 'lowest_loan_rate'=>$row[16]));
}

echo json_encode(array("result"=>$result), JSON_UNESCAPED_UNICODE);

mysqli_close($con);

?>