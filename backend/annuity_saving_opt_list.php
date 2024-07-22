<?php
$con = mysqli_connect("localhost", "root","","fn_prod");

if(!$con || mysqli_connect_errno()) {
    echo "Failed to connect to MySQL: "  .mysqli_connect_error();
}

mysqli_set_charset($con,"utf8");

// EditText로 원하는 값 가져오기
@$financial_product_id = $_GET['finPrdtId'];


$res = mysqli_query($con, "SELECT 
aspo.pension_reception_term, aspo.pension_entry_age, aspo.monthly_payment_amount, 
aspo.payment_period, aspo.pension_start_age, aspo.pension_reception_amount
FROM annuity_saving_product_options aspo
WHERE aspo.financial_product_id = '$financial_product_id';");


$result = array();

while($row = mysqli_fetch_array($res)) {
    array_push($result, array('pension_reception_term'=>$row[0], 'pension_entry_age'=>$row[1], 'monthly_payment_amount'=>$row[2], 
    'payment_period'=>$row[3], 'pension_start_age'=>$row[4], 'pension_reception_amount'=>$row[5]));
}

echo json_encode(array("result"=>$result), JSON_UNESCAPED_UNICODE);

mysqli_close($con);

?>