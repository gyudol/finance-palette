<?php
$con = mysqli_connect("localhost", "root","","fn_prod");

if(!$con || mysqli_connect_errno()) {
    echo "Failed to connect to MySQL: "  .mysqli_connect_error();
}

mysqli_set_charset($con,"utf8");

// EditText로 원하는 값 가져오기
@$optNum = $_GET['optNum'];


$res = mysqli_query($con, "SELECT 
lpo3.crdt_grad_1, lpo3.crdt_grad_4, lpo3.crdt_grad_5, lpo3.crdt_grad_6, 
lpo3.crdt_grad_10, lpo3.crdt_grad_11, lpo3.crdt_grad_12, lpo3.crdt_grad_13
FROM creditLoanProductsOptions lpo3
WHERE lpo3.opt_num = '$optNum';");


$result = array();

while($row = mysqli_fetch_array($res)) {
    array_push($result, array('crdt_grad_1'=>$row[0], 'crdt_grad_4'=>$row[1], 'crdt_grad_5'=>$row[2], 
    'crdt_grad_6'=>$row[3], 'crdt_grad_10'=>$row[4], 'crdt_grad_11'=>$row[5], 'crdt_grad_12'=>$row[4], 'crdt_grad_13'=>$row[5]));
}

echo json_encode(array("result"=>$result), JSON_UNESCAPED_UNICODE);

mysqli_close($con);

?>