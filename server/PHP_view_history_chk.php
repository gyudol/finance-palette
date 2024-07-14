<?php
$con = mysqli_connect("localhost", "root","","fn_prod");

if(!$con || mysqli_connect_errno()) {
    echo "Failed to connect to MySQL: "  .mysqli_connect_error();
}

mysqli_set_charset($con,"utf8");

// @ 붙이면 warning 사라짐
@$fin_prdt_num_cd = $_GET['fin_prdt_num_cd'];
@$aaid = $_GET['aaid'];
@$prdtNum = substr($fin_prdt_num_cd, 0, 1);

// 해당 상품 데이터의 조회 수++
switch ($prdtNum) {
    case 1:
        $query = "UPDATE depositProducts dp
          JOIN userViewHistory uvh 
          ON dp.fin_prdt_cd = SUBSTRING_INDEX(SUBSTRING(uvh.fin_prdt_num_cd, 3), '_', 1)
          SET dp.visit_count = dp.visit_count + 1
          WHERE dp.fin_prdt_cd = SUBSTRING_INDEX(SUBSTRING('$fin_prdt_num_cd', 3), '_', 1)";
        break;
    case 2:
        $query = "UPDATE savingproducts sp
          JOIN userViewHistory uvh 
          ON sp.fin_prdt_cd = SUBSTRING_INDEX(SUBSTRING(uvh.fin_prdt_num_cd, 3), '_', 1)
          SET sp.visit_count = sp.visit_count + 1
          WHERE sp.fin_prdt_cd = SUBSTRING_INDEX(SUBSTRING('$fin_prdt_num_cd', 3), '_', 1)";
        break;
    case 3:
        $query = "UPDATE annuitysavingproducts asp
          JOIN userViewHistory uvh 
          ON asp.fin_prdt_cd = SUBSTRING_INDEX(SUBSTRING(uvh.fin_prdt_num_cd, 3), '_', 1)
          SET asp.visit_count = asp.visit_count + 1
          WHERE asp.fin_prdt_cd = SUBSTRING_INDEX(SUBSTRING('$fin_prdt_num_cd', 3), '_', 1)";
        break;
    case 4:
        $query = "UPDATE rentHouseLoanProducts lp1
            JOIN rentHouseLoanProductsOptions lpo1 ON lp1.fin_prdt_cd = lpo1.fin_prdt_cd
          JOIN userViewHistory uvh 
          ON lpo1.opt_num = SUBSTRING_INDEX(SUBSTRING(uvh.fin_prdt_num_cd, 3), '_', 1)
          SET lp1.visit_count = lp1.visit_count + 1
          WHERE lpo1.opt_num = SUBSTRING_INDEX(SUBSTRING('$fin_prdt_num_cd', 3), '_', 1)";
        break;
    case 5:
        $query = "UPDATE mortgageLoanProducts lp2
            JOIN mortgageLoanProductsOptions lpo2 ON lp2.fin_prdt_cd = lpo2.fin_prdt_cd
          JOIN userViewHistory uvh 
          ON lpo2.opt_num = SUBSTRING_INDEX(SUBSTRING(uvh.fin_prdt_num_cd, 3), '_', 1)
          SET lp2.visit_count = lp2.visit_count + 1
          WHERE lpo2.opt_num = SUBSTRING_INDEX(SUBSTRING('$fin_prdt_num_cd', 3), '_', 1)";
        break;
    case 6:
        $query = "UPDATE creditLoanProducts lp3
            JOIN creditLoanProductsOptions lpo3 ON lp3.fin_prdt_cd = lpo3.fin_prdt_cd
          JOIN userViewHistory uvh 
          ON lpo3.opt_num = SUBSTRING_INDEX(SUBSTRING(uvh.fin_prdt_num_cd, 3), '_', 1)
          SET lp3.visit_count = lp3.visit_count + 1
          WHERE lpo3.opt_num = SUBSTRING_INDEX(SUBSTRING('$fin_prdt_num_cd', 3), '_', 1)";
        break;
    default:
        echo "Invalid prdtNum";
}

if (!mysqli_query($con, $query)) {
    echo "Error: " . $query . "<br>" . mysqli_error($con) . "<br>";
}


$result1 = mysqli_query($con, "SELECT * FROM userViewHistory WHERE aaid = '$aaid' AND LEFT(fin_prdt_num_cd, 1) = '$prdtNum';");
$rowcount1 = mysqli_num_rows($result1);

$result2 = mysqli_query($con, "SELECT * FROM userViewHistory WHERE fin_prdt_num_cd = '$fin_prdt_num_cd' AND aaid = '$aaid';");
$rowcount2 = mysqli_num_rows($result2);

if ($rowcount2 > 0) echo $rowcount1 . " true";
else echo $rowcount1 . " false";

mysqli_close($con);

?>