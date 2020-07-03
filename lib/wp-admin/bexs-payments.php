<?php

function bcbCreateTable()
{
    global $wpdb;
    $dbName = $wpdb->prefix . 'bexs_payments';

    if (!empty($wpdb->charset))
            $charset_collate = "DEFAULT CHARACTER SET $wpdb->charset";

    if (!empty($wpdb->collate))
        $charset_collate .= " COLLATE $wpdb->collate";

    $dbQuery = "CREATE TABLE " . $dbName . " (
        `id` int(10) NOT NULL AUTO_INCREMENT,
        `bexs_id` varchar(100) NOT NULL,
        `bexs_tax` varchar(25) NOT NULL,
        `brl_value` varchar(25) NOT NULL,
        `foreign_value` varchar(25) NOT NULL,
        `name` varchar(255) NOT NULL,
        `email` varchar(255) NOT NULL,
        `national_id` varchar(255) NOT NULL,
        `last_cc_number` int(10) NOT NULL,
        `exp_date` varchar(10) NOT NULL,
        `installments` int(10) NOT NULL,
        `tmstp` DATETIME NOT NULL,
        PRIMARY KEY (id)
    ) $charset_collate;";

    require_once(ABSPATH . "/wp-admin/includes/upgrade.php");
    dbDelta($dbQuery);
}

function bcbDropTable()
{
    global $wpdb;
    $dbName = $wpdb->prefix . 'bexs_payments';
    $dbQuery = "DROP TABLE " . $dbName . ";";
    $wpdb->query($dbQuery);
}

function bcbInsertPaymentRegister($payment, $bexs)
{
    global $wpdb;
    $dbName = $wpdb->prefix . 'bexs_payments';
    $wpdb->insert($dbName, [
        'name' => $payment['ccname'],
        'email' => $payment['email'],
        'national_id' => $_POST['national-id'],
        'bexs_id' => $bexs['id'],
        'bexs_tax' => $bexs['amount_info']['financial_tax'],
        'brl_value' => $bexs['amount_info']['gross_amount'],
        'foreign_value' => $bexs['amount_info']['foreign_gross_amount'],
        'last_cc_number' => (int) substr($payment['cardnumber'], strlen($payment['cardnumber']) - 4, 4),
        'exp_date' => $payment['cc-exp'],
        'installments' => (int) $payment['installments'],
        'tmstp' => (new DateTime('NOW'))->format('Y-m-d H:i:s'),
    ]);

    return $wpdb->insert_id;
}

function bexsPaymentPage()
{
    global $wpdb;
    $dbName = $wpdb->prefix . 'bexs_payments';
    $pageLimit = 20;
    $pageSkip = 0;
    $dbQuery = "SELECT * FROM $dbName LIMIT 10";
    $rows = $wpdb->get_results($dbQuery);

    ?>
        <style>
            .bcb-title h1{
                padding: 40px 10px 10px;
                margin: 0;
            }

            .bcb-table {
                box-shadow: 0px 2px 1px -1px rgba(0,0,0,0.2), 0px 1px 1px 0px rgba(0,0,0,0.14), 0px 1px 3px 0px rgba(0,0,0,0.12);
                color: rgba(0, 0, 0, 0.87);
                background-color: #fff;
                border-radius: 4px;
                margin-top: 20px;
                border-spacing: 0;
                border-collapse: collapse;
            }

            .bcb-table-wrap {
                width: 100%;
                overflow-x: scroll;
                padding: 0 0 20px;
            }

            .bcb-table tr {
                border-bottom: 1px solid rgba(125, 125, 125, .4);
            }

            .bcb-table tbody tr {
                border-bottom: 1px solid rgba(125, 125, 125, .2);
            }

            .bcb-table th,
            .bcb-table td {
                padding: 10px 20px;
                text-align: center;
                white-space: nowrap;
            }
        </style>
    <?php if (count($rows) == 0) { ?>
        <div class="bcb-title">
            <h1>No payments registered</h1>
        </div>
    <?php } else { ?>
        <div class="bcb-title">
            <h1>Payment History</h1>
        </div>
        <div class="bcb-table-wrap">
            <table class="bcb-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>BexsID</th>
                        <th>Timestamp</th>
                        <th><?= bcb_get_api_prop('coin_kind') ?></th>
                        <th>RS</th>
                        <th>Bexs Tax</th>
                        <th>X</th>
                        <th>Consumer Email</th>
                        <th>Consumer Name</th>
                        <th>National ID</th>
                        <th>Last Digits</th>
                        <th>Exp Date</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($rows as $payment) { ?>
                        <tr>
                            <td><?= $payment->id ?></td>
                            <td><?= $payment->bexs_id ?></td>
                            <td><?= $payment->tmstp ?></td>
                            <td><?= $payment->foreign_value ?></td>
                            <td><?= $payment->brl_value ?></td>
                            <td><?= $payment->bexs_tax ?></td>
                            <td><?= $payment->installments ?></td>
                            <td><?= $payment->email ?></td>
                            <td><?= $payment->name ?></td>
                            <td><?= $payment->national_id ?></td>
                            <td><?= $payment->last_cc_number ?></td>
                            <td><?= $payment->exp_date ?></td>
                        <tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    <?php }
}
