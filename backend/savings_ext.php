<?php
$con = mysqli_connect("localhost", "root","","fn_prod");

if(!$con || mysqli_connect_errno()) {
    echo "Failed to connect to MySQL: "  .mysqli_connect_error();
}

mysqli_set_charset($con,"utf8");

@$sp1 = $_GET['sp1'];   // spinner 1, 2, 3
@$sp2 = $_GET['sp2'];
@$sp3 = $_GET['sp3'];

$query = "SELECT sp.financial_product_name, sp.financial_product_id, c.financial_company_name, MAX(spo.maximum_interest_rate) AS highest_interest_rate, spo.interest_rate_type, spo.accrual_type
          FROM savings_products sp
          JOIN financial_companies c ON sp.financial_company_id = c.financial_company_id
          JOIN savings_product_options spo ON sp.financial_product_id = spo.financial_product_id";

if ($sp1 == 1) {
    $query .= " WHERE c.region_code = '020000'";
} elseif ($sp1 == 2) {
    $query .= " WHERE c.region_code != '020000'";
}

if ($sp2 == 1) {
    $query .= ($sp1 == 1 || $sp1 == 2) ? " AND spo.interest_rate_type_name = '단리'" : " WHERE spo.interest_rate_type_name = '단리'";
} elseif ($sp2 == 2) {
    $query .= ($sp1 == 1 || $sp1 == 2) ? " AND spo.interest_rate_type_name = '복리'" : " WHERE spo.interest_rate_type_name = '복리'";
}

if ($sp3 == 1) {
    $query .= ($sp1 == 1 || $sp1 == 2 || $sp2 == 1 || $sp2 == 2) ? " AND spo.accrual_type_name = '자유적립식'" : " WHERE spo.accrual_type_name = '자유적립식'";
} elseif ($sp3 == 2) {
    $query .= ($sp1 == 1 || $sp1 == 2 || $sp2 == 1 || $sp2 == 2) ? " AND spo.accrual_type_name = '정액적립식'" : " WHERE spo.accrual_type_name = '정액적립식'";
}

$query .= " GROUP BY sp.financial_product_name, c.financial_company_name, spo.interest_rate_type, spo.accrual_type
            ORDER BY highest_interest_rate DESC";

$res = mysqli_query($con, $query);
$result = array();


while($row = mysqli_fetch_array($res)) {
    array_push($result, array('financial_product_name'=>$row[0], 'financial_product_id'=>$row[1], 'financial_company_name'=>$row[2],
    'highest_interest_rate'=>$row[3], 'interest_rate_type'=>$row[4], 'accrual_type'=>$row[5]));
}

echo json_encode(array("result"=>$result), JSON_UNESCAPED_UNICODE);

mysqli_close($con);

?>