<?php
$con = mysqli_connect("localhost", "root","","fn_prod");

if(!$con || mysqli_connect_errno()) {
    echo "Failed to connect to MySQL: "  .mysqli_connect_error();
}

mysqli_set_charset($con,"utf8");

// @ 붙이면 warning 사라짐
@$prdtNum = $_GET['prdtNum'];
@$aaid = $_GET['aaid'];

if($prdtNum == 1) {
    $query = "SELECT dp.fin_prdt_nm, dp.fin_prdt_cd, c.kor_co_nm, MAX(dpo.intr_rate2) AS highest_intr_rate, dpo.intr_rate_type
                FROM depositproducts dp
                JOIN company c ON dp.fin_co_no = c.fin_co_no
                JOIN depositproductsoptions dpo ON dp.fin_prdt_cd = dpo.fin_prdt_cd
                JOIN userBookmark ub ON 
                    dp.fin_prdt_cd = SUBSTRING_INDEX(SUBSTRING(ub.fin_prdt_num_cd, 3), '_', 1)
                AND dpo.intr_rate_type = SUBSTRING_INDEX(SUBSTRING_INDEX(ub.fin_prdt_num_cd, '_', -1), '_', 1)
                WHERE LEFT(ub.fin_prdt_num_cd, 1) = '$prdtNum' AND ub.aaid = '$aaid'
                GROUP BY dp.fin_prdt_nm, c.kor_co_nm, dpo.intr_rate_type
                ORDER BY highest_intr_rate DESC;";

    $res = mysqli_query($con, $query);
    $result = array();


    while($row = mysqli_fetch_array($res)) {
        array_push($result, array('fin_prdt_nm'=>$row[0], 'fin_prdt_cd'=>$row[1], 'kor_co_nm'=>$row[2],
        'highest_intr_rate'=>$row[3], 'intr_rate_type'=>$row[4]));
    }      
}
else if($prdtNum == 2) {
    $query = "SELECT sp.fin_prdt_nm, sp.fin_prdt_cd, c.kor_co_nm, MAX(spo.intr_rate2) AS highest_intr_rate, spo.intr_rate_type, spo.rsrv_type
          FROM savingproducts sp
          JOIN company c ON sp.fin_co_no = c.fin_co_no
          JOIN savingproductsoptions spo ON sp.fin_prdt_cd = spo.fin_prdt_cd
          JOIN userBookmark ub ON 
                sp.fin_prdt_cd = SUBSTRING_INDEX(SUBSTRING(ub.fin_prdt_num_cd, 3), '_', 1)
                AND spo.intr_rate_type = SUBSTRING_INDEX(SUBSTRING_INDEX(ub.fin_prdt_num_cd, '_', -2), '_', 1)
                AND spo.rsrv_type = SUBSTRING_INDEX(SUBSTRING_INDEX(ub.fin_prdt_num_cd, '_', -1), '_', 1)
                WHERE LEFT(ub.fin_prdt_num_cd, 1) = '$prdtNum' AND ub.aaid = '$aaid'
          GROUP BY sp.fin_prdt_nm, c.kor_co_nm, spo.intr_rate_type, spo.rsrv_type
            ORDER BY highest_intr_rate DESC";
    
    $res = mysqli_query($con, $query);
    $result = array();

    while($row = mysqli_fetch_array($res)) {
        array_push($result, array('fin_prdt_nm'=>$row[0], 'fin_prdt_cd'=>$row[1], 'kor_co_nm'=>$row[2],
        'highest_intr_rate'=>$row[3], 'intr_rate_type'=>$row[4], 'rsrv_type'=>$row[5]));
    }
}
else if($prdtNum == 3) {
    $query = "SELECT asp.fin_prdt_nm, asp.fin_prdt_cd, c.kor_co_nm, asp.avg_prft_rate, asp.pnsn_kind_nm
                FROM annuitysavingproducts asp
                JOIN company c ON asp.fin_co_no = c.fin_co_no
                JOIN annuitysavingproductsoptions aspo ON asp.fin_prdt_cd = aspo.fin_prdt_cd
                JOIN userBookmark ub ON 
                    asp.fin_prdt_cd = SUBSTRING_INDEX(SUBSTRING(ub.fin_prdt_num_cd, 3), '_', 1)
                WHERE LEFT(ub.fin_prdt_num_cd, 1) = '$prdtNum' AND ub.aaid = '$aaid'
                GROUP BY asp.fin_prdt_nm, c.kor_co_nm
                ORDER BY avg_prft_rate DESC";

    $res = mysqli_query($con, $query);
    $result = array();


    while($row = mysqli_fetch_array($res)) {
        array_push($result, array('fin_prdt_nm'=>$row[0], 'fin_prdt_cd'=>$row[1], 'kor_co_nm'=>$row[2],
        'avg_prft_rate'=>$row[3], 'pnsn_kind_nm'=>$row[4]));
    }
}
else if($prdtNum == 4) {
    $query = "SELECT lp1.fin_prdt_nm, lpo1.opt_num, c.kor_co_nm, lpo1.lend_rate_min, lpo1.lend_rate_type_nm, lpo1.rpay_type_nm
                FROM rentHouseLoanProductsOptions lpo1
                JOIN rentHouseLoanProducts lp1 ON lpo1.fin_prdt_cd = lp1.fin_prdt_cd
                JOIN company c ON lp1.fin_co_no = c.fin_co_no
                JOIN userBookmark ub ON 
                    lpo1.opt_num = SUBSTRING_INDEX(SUBSTRING(ub.fin_prdt_num_cd, 3), '_', 1)
                WHERE LEFT(ub.fin_prdt_num_cd, 1) = '$prdtNum' AND ub.aaid = '$aaid'
                GROUP BY lp1.fin_prdt_nm, c.kor_co_nm
                ORDER BY lend_rate_min ASC";

    $res = mysqli_query($con, $query);
    $result = array();


    while($row = mysqli_fetch_array($res)) {
        array_push($result, array('fin_prdt_nm'=>$row[0], 'opt_num'=>$row[1], 'kor_co_nm'=>$row[2],
        'lend_rate_min'=>$row[3], 'lend_rate_type_nm'=>$row[4], 'rpay_type_nm'=>$row[5]));
    }
}
else if($prdtNum == 5) {
    $query = "SELECT lp2.fin_prdt_nm, lpo2.opt_num, c.kor_co_nm, lpo2.lend_rate_min, lpo2.lend_rate_type_nm, lpo2.rpay_type_nm
                FROM mortgageLoanProductsOptions lpo2
                JOIN mortgageLoanProducts lp2 ON lpo2.fin_prdt_cd = lp2.fin_prdt_cd
                JOIN company c ON lp2.fin_co_no = c.fin_co_no
                JOIN userBookmark ub ON 
                    lpo2.opt_num = SUBSTRING_INDEX(SUBSTRING(ub.fin_prdt_num_cd, 3), '_', 1)
                WHERE LEFT(ub.fin_prdt_num_cd, 1) = '$prdtNum' AND ub.aaid = '$aaid'
                GROUP BY lp2.fin_prdt_nm, c.kor_co_nm
                ORDER BY lend_rate_min ASC";

    $res = mysqli_query($con, $query);
    $result = array();


    while($row = mysqli_fetch_array($res)) {
        array_push($result, array('fin_prdt_nm'=>$row[0], 'opt_num'=>$row[1], 'kor_co_nm'=>$row[2],
        'lend_rate_min'=>$row[3], 'lend_rate_type_nm'=>$row[4], 'rpay_type_nm'=>$row[5]));
    }
}
else  {
    $query = "SELECT lp3.fin_prdt_nm, lpo3.opt_num, c.kor_co_nm, lp3.crdt_prdt_type_nm, LEAST(MIN(COALESCE(crdt_grad_1, 100)), MIN(COALESCE(crdt_grad_4, 100)), MIN(COALESCE(crdt_grad_5, 100)), 
                MIN(COALESCE(crdt_grad_6, 100)), MIN(COALESCE(crdt_grad_10, 100)), MIN(COALESCE(crdt_grad_11, 100)), MIN(COALESCE(crdt_grad_12, 100)), MIN(COALESCE(crdt_grad_13, 100))) AS lend_rate_min
                FROM creditLoanProductsOptions lpo3
                JOIN creditLoanProducts lp3 ON lpo3.fin_prdt_cd = lp3.fin_prdt_cd
                JOIN company c ON lp3.fin_co_no = c.fin_co_no
                JOIN userBookmark ub ON 
                    lpo3.opt_num = SUBSTRING_INDEX(SUBSTRING(ub.fin_prdt_num_cd, 3), '_', 1)
                WHERE LEFT(ub.fin_prdt_num_cd, 1) = '$prdtNum' AND ub.aaid = '$aaid'
                GROUP BY lp3.fin_prdt_nm, c.kor_co_nm, lpo3.crdt_lend_rate_type_nm
                ORDER BY lend_rate_min ASC";

    $res = mysqli_query($con, $query);
    $result = array();


    while($row = mysqli_fetch_array($res)) {
        array_push($result, array('fin_prdt_nm'=>$row[0], 'opt_num'=>$row[1], 'kor_co_nm'=>$row[2],
        'crdt_prdt_type_nm'=>$row[3], 'lend_rate_min'=>$row[4]));
    }
}

echo json_encode(array("result"=>$result), JSON_UNESCAPED_UNICODE);
mysqli_close($con);

?>