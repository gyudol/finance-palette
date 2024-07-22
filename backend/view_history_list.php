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
    $query = "SELECT dp.financial_product_name, dp.financial_product_id, c.financial_company_name, MAX(dpo.maximum_interest_rate) AS highest_interest_rate, dpo.interest_rate_type
                FROM deposit_products dp
                JOIN financial_companies c ON dp.financial_company_id = c.financial_company_id
                JOIN deposit_product_options dpo ON dp.financial_product_id = dpo.financial_product_id
                JOIN user_view_histories uvh ON 
                    dp.financial_product_id = SUBSTRING_INDEX(SUBSTRING(uvh.history_id, 3), '_', 1)
                AND dpo.interest_rate_type = SUBSTRING_INDEX(SUBSTRING_INDEX(uvh.history_id, '_', -1), '_', 1)
                WHERE LEFT(uvh.history_id, 1) = '$prdtNum' AND uvh.aaid = '$aaid'
                GROUP BY dp.financial_product_name, c.financial_company_name, dpo.interest_rate_type
                ORDER BY uvh.view_date_time DESC;";

    $res = mysqli_query($con, $query);
    $result = array();


    while($row = mysqli_fetch_array($res)) {
        array_push($result, array('financial_product_name'=>$row[0], 'financial_product_id'=>$row[1], 'financial_company_name'=>$row[2],
        'highest_interest_rate'=>$row[3], 'interest_rate_type'=>$row[4]));
    }      
}
else if($prdtNum == 2) {
    $query = "SELECT sp.financial_product_name, sp.financial_product_id, c.financial_company_name, MAX(spo.maximum_interest_rate) AS highest_interest_rate, spo.interest_rate_type, spo.accrual_type
          FROM savings_products sp
          JOIN financial_companies c ON sp.financial_company_id = c.financial_company_id
          JOIN savings_product_options spo ON sp.financial_product_id = spo.financial_product_id
          JOIN user_view_histories uvh ON 
                sp.financial_product_id = SUBSTRING_INDEX(SUBSTRING(uvh.history_id, 3), '_', 1)
                AND spo.interest_rate_type = SUBSTRING_INDEX(SUBSTRING_INDEX(uvh.history_id, '_', -2), '_', 1)
                AND spo.accrual_type = SUBSTRING_INDEX(SUBSTRING_INDEX(uvh.history_id, '_', -1), '_', 1)
                WHERE LEFT(uvh.history_id, 1) = '$prdtNum' AND uvh.aaid = '$aaid'
          GROUP BY sp.financial_product_name, c.financial_company_name, spo.interest_rate_type, spo.accrual_type
            ORDER BY uvh.view_date_time DESC";
    
    $res = mysqli_query($con, $query);
    $result = array();

    while($row = mysqli_fetch_array($res)) {
        array_push($result, array('financial_product_name'=>$row[0], 'financial_product_id'=>$row[1], 'financial_company_name'=>$row[2],
        'highest_interest_rate'=>$row[3], 'interest_rate_type'=>$row[4], 'accrual_type'=>$row[5]));
    }
}
else if($prdtNum == 3) {
    $query = "SELECT asp.financial_product_name, asp.financial_product_id, c.financial_company_name, asp.average_profit_rate, asp.pension_type_name
                FROM annuity_saving_products asp
                JOIN financial_companies c ON asp.financial_company_id = c.financial_company_id
                JOIN annuity_saving_product_options aspo ON asp.financial_product_id = aspo.financial_product_id
                JOIN user_view_histories uvh ON 
                    asp.financial_product_id = SUBSTRING_INDEX(SUBSTRING(uvh.history_id, 3), '_', 1)
                WHERE LEFT(uvh.history_id, 1) = '$prdtNum' AND uvh.aaid = '$aaid'
                GROUP BY asp.financial_product_name, c.financial_company_name
                ORDER BY uvh.view_date_time DESC";

    $res = mysqli_query($con, $query);
    $result = array();


    while($row = mysqli_fetch_array($res)) {
        array_push($result, array('financial_product_name'=>$row[0], 'financial_product_id'=>$row[1], 'financial_company_name'=>$row[2],
        'average_profit_rate'=>$row[3], 'pension_type_name'=>$row[4]));
    }
}
else if($prdtNum == 4) {
    $query = "SELECT lp1.financial_product_name, lpo1.option_id, c.financial_company_name, lpo1.minimum_loan_rate, lpo1.loan_rate_type, lpo1.loan_repayment_type
                FROM rent_house_loan_product_options lpo1
                JOIN rent_house_loan_products lp1 ON lpo1.financial_product_id = lp1.financial_product_id
                JOIN financial_companies c ON lp1.financial_company_id = c.financial_company_id
                JOIN user_view_histories uvh ON 
                    lpo1.option_id = SUBSTRING_INDEX(SUBSTRING(uvh.history_id, 3), '_', 1)
                WHERE LEFT(uvh.history_id, 1) = '$prdtNum' AND uvh.aaid = '$aaid'
                GROUP BY lp1.financial_product_name, c.financial_company_name
                ORDER BY uvh.view_date_time DESC";

    $res = mysqli_query($con, $query);
    $result = array();


    while($row = mysqli_fetch_array($res)) {
        array_push($result, array('financial_product_name'=>$row[0], 'option_id'=>$row[1], 'financial_company_name'=>$row[2],
        'minimum_loan_rate'=>$row[3], 'loan_rate_type'=>$row[4], 'loan_repayment_type'=>$row[5]));
    }
}
else if($prdtNum == 5) {
    $query = "SELECT lp2.financial_product_name, lpo2.option_id, c.financial_company_name, lpo2.minimum_loan_rate, lpo2.loan_rate_type, lpo2.loan_repayment_type
                FROM mortgage_loan_product_options lpo2
                JOIN mortgage_loan_products lp2 ON lpo2.financial_product_id = lp2.financial_product_id
                JOIN financial_companies c ON lp2.financial_company_id = c.financial_company_id
                JOIN user_view_histories uvh ON 
                    lpo2.option_id = SUBSTRING_INDEX(SUBSTRING(uvh.history_id, 3), '_', 1)
                WHERE LEFT(uvh.history_id, 1) = '$prdtNum' AND uvh.aaid = '$aaid'
                GROUP BY lp2.financial_product_name, c.financial_company_name
                ORDER BY uvh.view_date_time DESC";

    $res = mysqli_query($con, $query);
    $result = array();


    while($row = mysqli_fetch_array($res)) {
        array_push($result, array('financial_product_name'=>$row[0], 'option_id'=>$row[1], 'financial_company_name'=>$row[2],
        'minimum_loan_rate'=>$row[3], 'loan_rate_type'=>$row[4], 'loan_repayment_type'=>$row[5]));
    }
}
else  {
    $query = "SELECT lp3.financial_product_name, lpo3.option_id, c.financial_company_name, lp3.credit_product_type_name, LEAST(MIN(COALESCE(credit_score_above_900, 100)), MIN(COALESCE(credit_score_801_to_900, 100)), MIN(COALESCE(credit_score_701_to_800, 100)), 
                MIN(COALESCE(credit_score_601_to_700, 100)), MIN(COALESCE(credit_score_501_to_600, 100)), MIN(COALESCE(credit_score_401_to_500, 100)), MIN(COALESCE(credit_score_301_to_400, 100)), MIN(COALESCE(credit_score_below_300, 100))) AS lowest_loan_rate
                FROM credit_loan_product_options lpo3
                JOIN credit_loan_products lp3 ON lpo3.financial_product_id = lp3.financial_product_id
                JOIN financial_companies c ON lp3.financial_company_id = c.financial_company_id
                JOIN user_view_histories uvh ON 
                    lpo3.option_id = SUBSTRING_INDEX(SUBSTRING(uvh.history_id, 3), '_', 1)
                WHERE LEFT(uvh.history_id, 1) = '$prdtNum' AND uvh.aaid = '$aaid'
                GROUP BY lp3.financial_product_name, c.financial_company_name, lpo3.credit_loan_rate_type_name
                ORDER BY uvh.view_date_time DESC";

    $res = mysqli_query($con, $query);
    $result = array();


    while($row = mysqli_fetch_array($res)) {
        array_push($result, array('financial_product_name'=>$row[0], 'option_id'=>$row[1], 'financial_company_name'=>$row[2],
        'credit_product_type_name'=>$row[3], 'lowest_loan_rate'=>$row[4]));
    }
}

echo json_encode(array("result"=>$result), JSON_UNESCAPED_UNICODE);
mysqli_close($con);

?>