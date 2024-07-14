<?php
$con = mysqli_connect("localhost", "root","","fn_prod");

if(!$con || mysqli_connect_errno()) {
    echo "Failed to connect to MySQL: "  .mysqli_connect_error();
}

mysqli_set_charset($con,"utf8");

@$a = $_GET['a'];
@$b = $_GET['b'];
@$c = $_GET['c'];

$query = "SELECT sp.fin_prdt_nm, sp.fin_prdt_cd, c.kor_co_nm, MAX(spo.intr_rate2) AS highest_intr_rate, spo.intr_rate_type, spo.rsrv_type
          FROM savingproducts sp
          JOIN company c ON sp.fin_co_no = c.fin_co_no
          JOIN savingproductsoptions spo ON sp.fin_prdt_cd = spo.fin_prdt_cd";

if ($a == 1) {
    $query .= " WHERE c.topFinGrpNo = '020000'";
} elseif ($a == 2) {
    $query .= " WHERE c.topFinGrpNo != '020000'";
}

if ($b == 1) {
    $query .= ($a == 1 || $a == 2) ? " AND spo.intr_rate_type_nm = '단리'" : " WHERE spo.intr_rate_type_nm = '단리'";
} elseif ($b == 2) {
    $query .= ($a == 1 || $a == 2) ? " AND spo.intr_rate_type_nm = '복리'" : " WHERE spo.intr_rate_type_nm = '복리'";
}

if ($c == 1) {
    $query .= ($a == 1 || $a == 2 || $b == 1 || $b == 2) ? " AND spo.rsrv_type_nm = '자유적립식'" : " WHERE spo.rsrv_type_nm = '자유적립식'";
} elseif ($c == 2) {
    $query .= ($a == 1 || $a == 2 || $b == 1 || $b == 2) ? " AND spo.rsrv_type_nm = '정액적립식'" : " WHERE spo.rsrv_type_nm = '정액적립식'";
}

$query .= " GROUP BY sp.fin_prdt_nm, c.kor_co_nm, spo.intr_rate_type, spo.rsrv_type
            ORDER BY highest_intr_rate DESC";

$res = mysqli_query($con, $query);
$result = array();


while($row = mysqli_fetch_array($res)) {
    array_push($result, array('fin_prdt_nm'=>$row[0], 'fin_prdt_cd'=>$row[1], 'kor_co_nm'=>$row[2],
    'highest_intr_rate'=>$row[3], 'intr_rate_type'=>$row[4], 'rsrv_type'=>$row[5]));
}

echo json_encode(array("result"=>$result), JSON_UNESCAPED_UNICODE);

mysqli_close($con);

?>