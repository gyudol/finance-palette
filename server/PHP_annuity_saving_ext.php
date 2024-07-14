<?php
$con = mysqli_connect("localhost", "root","","fn_prod");

if(!$con || mysqli_connect_errno()) {
    echo "Failed to connect to MySQL: "  .mysqli_connect_error();
}

mysqli_set_charset($con,"utf8");

@$a = $_GET['a'];

$query = "SELECT asp.fin_prdt_nm, asp.fin_prdt_cd, c.kor_co_nm, asp.avg_prft_rate, asp.pnsn_kind_nm
          FROM annuitysavingproducts asp
          JOIN company c ON asp.fin_co_no = c.fin_co_no
          JOIN annuitysavingproductsoptions aspo ON asp.fin_prdt_cd = aspo.fin_prdt_cd";

if ($a == 1) {
    $query .= " WHERE asp.pnsn_kind_nm = '연금저축펀드'";
} elseif ($a == 2) {
    $query .= " WHERE asp.pnsn_kind_nm = '연금저축보험(생명)'";
} elseif ($a == 3) {
    $query .= " WHERE asp.pnsn_kind_nm = '연금저축보험(손해)'";
}

$query .= " GROUP BY asp.fin_prdt_nm, c.kor_co_nm
            ORDER BY avg_prft_rate DESC";

$res = mysqli_query($con, $query);
$result = array();


while($row = mysqli_fetch_array($res)) {
    array_push($result, array('fin_prdt_nm'=>$row[0], 'fin_prdt_cd'=>$row[1], 'kor_co_nm'=>$row[2],
    'avg_prft_rate'=>$row[3], 'pnsn_kind_nm'=>$row[4]));
}

echo json_encode(array("result"=>$result), JSON_UNESCAPED_UNICODE);

mysqli_close($con);

?>