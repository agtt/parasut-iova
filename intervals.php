<?php
//echo "<pre>";print_r(wp_get_schedules());echo "</pre>";
function cron_add_weekly( $schedules ) {
    // Adds once weekly to the existing schedules.
    $schedules['weekly'] = array(
        'interval' => 604800,
        'display' => __( 'Once Weekly' )
    );
    return $schedules;
}
add_filter( 'cron_schedules', 'cron_add_weekly' );

// add another interval
function cron_add_minute( $schedules ) {
    // Adds once every minute to the existing schedules.
    $schedules['everyminute'] = array(
        'interval' => 60,
        'display' => __( 'Once Every Minute' )
    );
    return $schedules;
}
add_filter( 'cron_schedules', 'cron_add_minute' );

// add another interval
//function cron_add_always( $schedules ) {
//    // Adds once every minute to the existing schedules.
//    $schedules['always'] = array(
//        'interval' => 1,
//        'display' => __( 'Once always' )
//    );
//    return $schedules;
//}
//add_filter( 'cron_schedules', 'cron_add_always' );