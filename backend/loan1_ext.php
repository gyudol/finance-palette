<?php
$con = mysqli_connect("localhost", "root","","fn_prod");

if(!$con || mysqli_connect_errno()) {
    echo "Failed to connect to MySQL: "  .mysqli_connect_error();
}

mysqli_set_charset($con,"utf8");

@$a = $_GET['a'];
@$b = $_GET['b'];
@$c = $_GET['c'];

$query = "SELECT lp1.fin_prdt_nm, lpo1.opt_num, c.kor_co_nm, lpo1.lend_rate_min, lpo1.lend_rate_type_nm, lpo1.rpay_type_nm
          FROM rentHouseLoanProductsOptions lpo1
          JOIN rentHouseLoanProducts lp1 ON lpo1.fin_prdt_cd = lp1.fin_prdt_cd
          JOIN company c ON lp1.fin_co_no = c.fin_co_no";

if ($a == 1) {
    $query .= " WHERE c.topFinGrpNo = '020000'";
} elseif ($a == 2) {
    $query .= " WHERE c.topFinGrpNo != '020000'";
}

if ($b == 1) {
    $query .= ($a == 1 || $a == 2) ? " AND lpo1.rpay_type_nm = '분할상환방식'" : " WHERE lpo1.rpay_type_nm = '분할상환방식'";
} elseif ($b == 2) {
    $query .= ($a == 1 || $a == 2) ? " AND lpo1.rpay_type_nm = '만기일시상환방식'" : " WHERE lpo1.rpay_type_nm = '만기일시상환방식'";
}

if ($c == 1) {
    $query .= ($a == 1 || $a == 2 || $b == 1 || $b == 2) ? " AND lpo1.lend_rate_type_nm = '고정금리'" : " WHERE lpo1.lend_rate_type_nm = '고정금리'";
} elseif ($c == 2) {
    $query .= ($a == 1 || $a == 2 || $b == 1 || $b == 2) ? " AND lpo1.lend_rate_type_nm = '변동금리'" : " WHERE lpo1.lend_rate_type_nm = '변동금리'";
}

$query .= " GROUP BY lp1.fin_prdt_nm, c.kor_co_nm
            ORDER BY lend_rate_min ASC";

$res = mysqli_query($con, $query);
$result = array();


while($row = mysqli_fetch_array($res)) {
    array_push($result, array('fin_prdt_nm'=>$row[0], 'opt_num'=>$row[1], 'kor_co_nm'=>$row[2],
    'lend_rate_min'=>$row[3], 'lend_rate_type_nm'=>$row[4], 'rpay_type_nm'=>$row[5]));
}

echo json_encode(array("result"=>$result), JSON_UNESCAPED_UNICODE);

mysqli_close($con);

?>