<?php
$con = mysqli_connect("localhost", "root","","fn_prod");

if(!$con || mysqli_connect_errno()) {
    echo "Failed to connect to MySQL: "  .mysqli_connect_error();
}

mysqli_set_charset($con,"utf8");

// @ 붙이면 warning 사라짐
@$history_id = $_GET['historyId'];
@$aaid = $_GET['aaid'];
@$prdtNum = substr($history_id, 0, 1);

// 해당 상품 데이터의 조회 수++
switch ($prdtNum) {
    case 1:
        $query = "UPDATE deposit_products dp
          JOIN user_view_histories uvh 
          ON dp.financial_product_id = SUBSTRING_INDEX(SUBSTRING(uvh.history_id, 3), '_', 1)
          SET dp.visit_count = dp.visit_count + 1
          WHERE dp.financial_product_id = SUBSTRING_INDEX(SUBSTRING('$history_id', 3), '_', 1)";
        break;
    case 2:
        $query = "UPDATE savings_products sp
          JOIN user_view_histories uvh 
          ON sp.financial_product_id = SUBSTRING_INDEX(SUBSTRING(uvh.history_id, 3), '_', 1)
          SET sp.visit_count = sp.visit_count + 1
          WHERE sp.financial_product_id = SUBSTRING_INDEX(SUBSTRING('$history_id', 3), '_', 1)";
        break;
    case 3:
        $query = "UPDATE annuity_saving_products asp
          JOIN user_view_histories uvh 
          ON asp.financial_product_id = SUBSTRING_INDEX(SUBSTRING(uvh.history_id, 3), '_', 1)
          SET asp.visit_count = asp.visit_count + 1
          WHERE asp.financial_product_id = SUBSTRING_INDEX(SUBSTRING('$history_id', 3), '_', 1)";
        break;
    case 4:
        $query = "UPDATE rent_house_loan_products lp1
            JOIN rent_house_loan_product_options lpo1 ON lp1.financial_product_id = lpo1.financial_product_id
          JOIN user_view_histories uvh 
          ON lpo1.option_id = SUBSTRING_INDEX(SUBSTRING(uvh.history_id, 3), '_', 1)
          SET lp1.visit_count = lp1.visit_count + 1
          WHERE lpo1.option_id = SUBSTRING_INDEX(SUBSTRING('$history_id', 3), '_', 1)";
        break;
    case 5:
        $query = "UPDATE mortgage_loan_products lp2
            JOIN mortgage_loan_product_options lpo2 ON lp2.financial_product_id = lpo2.financial_product_id
          JOIN user_view_histories uvh 
          ON lpo2.option_id = SUBSTRING_INDEX(SUBSTRING(uvh.history_id, 3), '_', 1)
          SET lp2.visit_count = lp2.visit_count + 1
          WHERE lpo2.option_id = SUBSTRING_INDEX(SUBSTRING('$history_id', 3), '_', 1)";
        break;
    case 6:
        $query = "UPDATE credit_loan_products lp3
            JOIN credit_loan_product_options lpo3 ON lp3.financial_product_id = lpo3.financial_product_id
          JOIN user_view_histories uvh 
          ON lpo3.option_id = SUBSTRING_INDEX(SUBSTRING(uvh.history_id, 3), '_', 1)
          SET lp3.visit_count = lp3.visit_count + 1
          WHERE lpo3.option_id = SUBSTRING_INDEX(SUBSTRING('$history_id', 3), '_', 1)";
        break;
    default:
        echo "Invalid prdtNum";
}

if (!mysqli_query($con, $query)) {
    echo "Error: " . $query . "<br>" . mysqli_error($con) . "<br>";
}


$result1 = mysqli_query($con, "SELECT * FROM user_view_histories WHERE aaid = '$aaid' AND LEFT(history_id, 1) = '$prdtNum';");
$rowcount1 = mysqli_num_rows($result1);

$result2 = mysqli_query($con, "SELECT * FROM user_view_histories WHERE history_id = '$history_id' AND aaid = '$aaid';");
$rowcount2 = mysqli_num_rows($result2);

if ($rowcount2 > 0) echo $rowcount1 . " true";
else echo $rowcount1 . " false";

mysqli_close($con);

?>