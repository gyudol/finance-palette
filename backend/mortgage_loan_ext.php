<?php
$con = mysqli_connect("localhost", "root","","fn_prod");

if(!$con || mysqli_connect_errno()) {
    echo "Failed to connect to MySQL: "  .mysqli_connect_error();
}

mysqli_set_charset($con,"utf8");

@$sp1 = $_GET['sp1'];
@$sp2 = $_GET['sp2'];
@$sp3 = $_GET['sp3'];

$query = "SELECT mlp.financial_product_name, mlpo.option_id, c.financial_company_name, mlpo.minimum_loan_rate, mlpo.loan_rate_type, mlpo.loan_repayment_type
          FROM mortgage_loan_product_options mlpo
          JOIN mortgage_loan_products mlp ON mlpo.financial_product_id = mlp.financial_product_id
          JOIN financial_companies c ON mlp.financial_company_id = c.financial_company_id";

if ($sp1 == 1) {
    $query .= " WHERE c.region_code = '020000'";
} elseif ($sp1 == 2) {
    $query .= " WHERE c.region_code != '020000'";
}

if ($sp2 == 1) {
    $query .= ($sp1 == 1 || $sp1 == 2) ? " AND mlpo.loan_repayment_type = '분할상환방식'" : " WHERE mlpo.loan_repayment_type = '분할상환방식'";
} elseif ($sp2 == 2) {
    $query .= ($sp1 == 1 || $sp1 == 2) ? " AND mlpo.loan_repayment_type = '만기일시상환방식'" : " WHERE mlpo.loan_repayment_type = '만기일시상환방식'";
}

if ($sp3 == 1) {
    $query .= ($sp1 == 1 || $sp1 == 2 || $sp2 == 1 || $sp2 == 2) ? " AND mlpo.loan_rate_type = '고정금리'" : " WHERE mlpo.loan_rate_type = '고정금리'";
} elseif ($sp3 == 2) {
    $query .= ($sp1 == 1 || $sp1 == 2 || $sp2 == 1 || $sp2 == 2) ? " AND mlpo.loan_rate_type = '변동금리'" : " WHERE mlpo.loan_rate_type = '변동금리'";
}

$query .= " GROUP BY mlp.financial_product_name, c.financial_company_name
            ORDER BY minimum_loan_rate ASC";

$res = mysqli_query($con, $query);
$result = array();


while($row = mysqli_fetch_array($res)) {
    array_push($result, array('financial_product_name'=>$row[0], 'option_id'=>$row[1], 'financial_company_name'=>$row[2],
    'minimum_loan_rate'=>$row[3], 'loan_rate_type'=>$row[4], 'loan_repayment_type'=>$row[5]));
}

echo json_encode(array("result"=>$result), JSON_UNESCAPED_UNICODE);

mysqli_close($con);

?>