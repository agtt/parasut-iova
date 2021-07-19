<?php
/**
 * Worker Cron - Parasut Integration
 */

function transfer_orders()
{
    $options = get_option('parasut');
    echo "Cron Çalıştı";
//    $args = array(
//        'status' => 'completed',
//    );
//    $orders = wc_get_orders($args);

}

add_action('mycronjob', 'transfer_orders');
add_cron(time(),"everyminute","mycronjob",[]);

/*
//https://github.com/woocommerce/woocommerce/wiki/wc_get_orders-and-WC_Order_Query
$args = array(
    'status' => 'completed',
);
$orders = wc_get_orders($args);
//    $query = new WC_Order_Query( array(
//        'limit' => 10,
//        'orderby' => 'date',
//        'order' => 'DESC',
//        //'return' => 'ids',
//    ) );
//    $orders = $query->get_orders();
//echo "<pre>";print_r($orders);echo "</pre>";