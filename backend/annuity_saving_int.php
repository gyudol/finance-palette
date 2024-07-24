<?php
$con = mysqli_connect("localhost", "root","","fn_prod");

if(!$con || mysqli_connect_errno()) {
    echo "Failed to connect to MySQL: "  .mysqli_connect_error();
}

mysqli_set_charset($con,"utf8");

// EditText로 원하는 값 가져오기
@$financial_product_id = $_GET['finPrdtId'];


$res = mysqli_query($con, "SELECT 
asp.financial_product_name, asp.pension_type_name, asp.product_type_name, asp.average_profit_rate, asp.past_profit_rate_1, asp.past_profit_rate_2, asp.past_profit_rate_3, 
asp.sale_start_date, asp.join_way, asp.sales_company, asp.remarks, asp.maintenance_count, asp.disclosure_interest_rate, asp.guaranteed_interest_rate,
c.financial_company_name, c.disclosure_officer, c.homepage_url, 
CONCAT(asp.disclosure_start_date, IFNULL(CONCAT(' ~ ', asp.disclosure_end_date), ' ~ ')) AS disclosure_start_to_end_date
FROM annuity_saving_products asp 
JOIN financial_companies c ON asp.financial_company_id = c.financial_company_id 
WHERE asp.financial_product_id = '$financial_product_id';");


$result = array();

while($row = mysqli_fetch_array($res)) {
    array_push($result, array('financial_product_name'=>$row[0], 'pension_type_name'=>$row[1], 'product_type_name'=>$row[2], 'average_profit_rate'=>$row[3], 'past_profit_rate_1'=>$row[4], 
    'past_profit_rate_2'=>$row[5], 'past_profit_rate_3'=>$row[6], 'sale_start_date'=>$row[7], 'join_way'=>$row[8], 'sales_company'=>$row[9], 'remarks'=>$row[10],
    'maintenance_count'=>$row[11], 'disclosure_interest_rate'=>$row[12], 'guaranteed_interest_rate'=>$row[13], 'financial_company_name'=>$row[14], 'disclosure_officer'=>$row[15], 
    'homepage_url'=>$row[16], 'disclosure_start_to_end_date'=>$row[17]));
}

echo json_encode(array("result"=>$result), JSON_UNESCAPED_UNICODE);

mysqli_close($con);

?>