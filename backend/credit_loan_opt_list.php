<?php
$con = mysqli_connect("localhost", "root","","fn_prod");

if(!$con || mysqli_connect_errno()) {
    echo "Failed to connect to MySQL: "  .mysqli_connect_error();
}

mysqli_set_charset($con,"utf8");

// EditText로 원하는 값 가져오기
@$option_id = $_GET['optionId'];
clpo

$res = mysqli_query($con, "SELECT 
clpo.credit_score_above_900, clpo.credit_score_801_to_900, clpo.credit_score_701_to_800, clpo.credit_score_601_to_700, 
clpo.credit_score_501_to_600, clpo.credit_score_401_to_500, clpo.credit_score_301_to_400, clpo.credit_score_below_300
FROM credit_loan_product_options clpo
WHERE clpo.option_id = '$option_id';");


$result = array();

while($row = mysqli_fetch_array($res)) {
    array_push($result, array('credit_score_above_900'=>$row[0], 'credit_score_801_to_900'=>$row[1], 'credit_score_701_to_800'=>$row[2], 
    'credit_score_601_to_700'=>$row[3], 'credit_score_501_to_600'=>$row[4], 'credit_score_401_to_500'=>$row[5], 'credit_score_301_to_400'=>$row[4], 
    'credit_score_below_300'=>$row[5]));
}

echo json_encode(array("result"=>$result), JSON_UNESCAPED_UNICODE);

mysqli_close($con);

?>