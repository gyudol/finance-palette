    <?php
$con = mysqli_connect("localhost", "root","","fn_prod");

if(!$con || mysqli_connect_errno()) {
    echo "Failed to connect to MySQL: "  .mysqli_connect_error();
}

mysqli_set_charset($con,"utf8");

// EditText로 원하는 값 가져오기
@$option_id = $_GET['optionId'];


$res = mysqli_query($con, "SELECT 
rlp.financial_product_name, rlp.loan_limit, rlp.loan_incidental_expenses, rlp.early_repayment_fee, rlp.delinquency_rate, rlp.join_way, 
c.financial_company_name, c.disclosure_officer, rlpo.loan_rate_type, rlpo.loan_repayment_type,
minimum_loan_rate, average_loan_rate, maximum_loan_rate, 
CONCAT(rlp.disclosure_start_date, IFNULL(CONCAT(' ~ ', rlp.disclosure_end_date), ' ~ ')) AS disclosure_start_to_end_date,
minimum_loan_rate AS lowest_loan_rate
FROM rent_house_loan_product_options rlpo
JOIN rent_house_loan_products rlp ON rlpo.financial_product_id = rlp.financial_product_id
JOIN financial_companies c ON rlp.financial_company_id = c.financial_company_id
WHERE rlpo.option_id = '$option_id';");


$result = array();

while($row = mysqli_fetch_array($res)) {
    array_push($result, array('financial_product_name'=>$row[0], 'loan_limit'=>$row[1], 'loan_incidental_expenses'=>$row[2], 'early_repayment_fee'=>$row[3], 'delinquency_rate'=>$row[4], 
    'join_way'=>$row[5], 'financial_company_name'=>$row[6], 'disclosure_officer'=>$row[7], 'loan_rate_type'=>$row[8], 'loan_repayment_type'=>$row[9], 'minimum_loan_rate'=>$row[10],
    'average_loan_rate'=>$row[11], 'maximum_loan_rate'=>$row[12], 'disclosure_start_to_end_date'=>$row[13], 'lowest_loan_rate'=>$row[14]));
}

echo json_encode(array("result"=>$result), JSON_UNESCAPED_UNICODE);

mysqli_close($con);

?>