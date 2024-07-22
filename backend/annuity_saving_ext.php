<?php
$con = mysqli_connect("localhost", "root","","fn_prod");

if(!$con || mysqli_connect_errno()) {
    echo "Failed to connect to MySQL: "  .mysqli_connect_error();
}

mysqli_set_charset($con,"utf8");

@$sp1 = $_GET['sp1'];

$query = "SELECT asp.financial_product_name, asp.financial_product_id, c.financial_company_name, asp.average_profit_rate, asp.pension_type_name
          FROM annuity_saving_products asp
          JOIN financial_companies c ON asp.financial_company_id = c.financial_company_id
          JOIN annuity_saving_product_options aspo ON asp.financial_product_id = aspo.financial_product_id";

if ($sp1 == 1) {
    $query .= " WHERE asp.pension_type_name = '연금저축펀드'";
} elseif ($sp1 == 2) {
    $query .= " WHERE asp.pension_type_name = '연금저축보험(생명)'";
} elseif ($sp1 == 3) {
    $query .= " WHERE asp.pension_type_name = '연금저축보험(손해)'";
}

$query .= " GROUP BY asp.financial_product_name, c.financial_company_name
            ORDER BY average_profit_rate DESC";

$res = mysqli_query($con, $query);
$result = array();


while($row = mysqli_fetch_array($res)) {
    array_push($result, array('financial_product_name'=>$row[0], 'financial_product_id'=>$row[1], 'financial_company_name'=>$row[2],
    'average_profit_rate'=>$row[3], 'pension_type_name'=>$row[4]));
}

echo json_encode(array("result"=>$result), JSON_UNESCAPED_UNICODE);

mysqli_close($con);

?>