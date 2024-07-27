<?php
$con = mysqli_connect("localhost", "root","","fn_prod");

if(!$con || mysqli_connect_errno()) {
    echo "Failed to connect to MySQL: "  .mysqli_connect_error();
}

mysqli_set_charset($con,"utf8");

@$sp1 = $_GET['sp1'];
@$sp2 = $_GET['sp2'];

$query = "SELECT clp.financial_product_name, clpo.option_id, c.financial_company_name, clp.credit_product_type_name, LEAST(MIN(COALESCE(credit_score_above_900, 100)), MIN(COALESCE(credit_score_801_to_900, 100)), MIN(COALESCE(credit_score_701_to_800, 100)), 
            MIN(COALESCE(credit_score_601_to_700, 100)), MIN(COALESCE(credit_score_501_to_600, 100)), MIN(COALESCE(credit_score_401_to_500, 100)), MIN(COALESCE(credit_score_301_to_400, 100)), MIN(COALESCE(credit_score_below_300, 100))) AS lowest_loan_rate
          FROM credit_loan_product_options clpo
          JOIN credit_loan_products clp ON clpo.financial_product_id = clp.financial_product_id
          JOIN financial_companies c ON clp.financial_company_id = c.financial_company_id";

if ($sp1 == 1) {
    $query .= " WHERE c.region_code = '020000'";
} elseif ($sp1 == 2) {
    $query .= " WHERE c.region_code != '020000'";
}

if ($sp2 == 1) {
    $query .= ($sp1 == 1 || $sp1 == 2) ? " AND clp.credit_product_type_name = '일반신용대출'" : " WHERE clp.credit_product_type_name = '일반신용대출'";
} elseif ($sp2 == 2) {
    $query .= ($sp1 == 1 || $sp1 == 2) ? " AND clp.credit_product_type_name = '마이너스한도대출'" : " WHERE clp.credit_product_type_name = '마이너스한도대출'";
} elseif ($sp2 == 3) {
    $query .= ($sp1 == 1 || $sp1 == 2) ? " AND clp.credit_product_type_name = '장기카드대출(카드론)'" : " WHERE clp.credit_product_type_name = '장기카드대출(카드론)'";
}

$query .= " GROUP BY clp.financial_product_name, c.financial_company_name, clpo.credit_loan_rate_type_name
            ORDER BY lowest_loan_rate ASC";

$res = mysqli_query($con, $query);
$result = array();


while($row = mysqli_fetch_array($res)) {
    array_push($result, array('financial_product_name'=>$row[0], 'option_id'=>$row[1], 'financial_company_name'=>$row[2],
    'credit_product_type_name'=>$row[3], 'lowest_loan_rate'=>$row[4]));
}

echo json_encode(array("result"=>$result), JSON_UNESCAPED_UNICODE);

mysqli_close($con);

?>