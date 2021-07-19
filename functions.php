<?php

use Parasut\Client;

// Add Action Plugin
add_action('admin_menu', 'parasutMenu');
add_action('admin_init', 'parasut_register_settings');

// Plugin Functions
function parasutMenu()
{
    add_menu_page('Paraşüt - Woocommerce Entegrasyonu - IOVA', 'Paraşüt IOVA', 'manage_options', 'parasut-iova', 'parasutFunction');
}

function parasut_register_settings()
{
    register_setting('parasut', 'parasut', 'parasut_validate');
}

function parasut_validate($args)
{
    $status = False;
    $parasut = new Client([
        'client_id' => $args['client_id'],
        'client_secret' => $args['client_secret'],
        'username' => $args['username'],
        'password' => $args['password'],
        'company_id' => $args['company_id'],
        'grant_type' => 'password',
        'redirect_uri' => 'urn:ietf:wg:oauth:2.0:oob',
    ]);
    try {
        $parasut->authorize();
        $status = True;
        successNotification("Paraşüt bağlantısı başarı ile gerçekleşti.");
    } catch (Exception $e) {
        errorNotification("Paraşüt bağlantısı yapılamadı Hata  :  " . $e->getMessage());
    }
    $args['status'] = $status;
    //add_settings_error('wpse61431_settings', 'wpse61431_invalid_email', 'Please enter a valid email!', $type = 'error');
    return $args;
}

function successNotification($message = "Güncelleme Başarılı")
{
    ?>
    <div class="notice notice-success is-dismissible">
        <p><?php _e($message, 'sample-text-domain'); ?></p>
    </div>
    <?php
}

function errorNotification($message = "Paraşüt bağlantısı yapılamadı")
{
    $class = 'notice notice-error';
    $message = __($message, 'sample-text-domain');

    printf('<div class="%1$s"><p>%2$s</p></div>', esc_attr($class), esc_html($message));
}


function add_cron($next_run, $schedule, $hookname, $args)
{
    $next_run = strtotime($next_run);
    if (false === $next_run) {
        $next_run = time();
    } else {
        $next_run = get_gmt_from_date(date('Y-m-d H:i:s', $next_run), 'U');
    }
    if (!is_array($args)) {
        $args = array();
    }
    if ('_oneoff' === $schedule) {
        return wp_schedule_single_event($next_run, $hookname, $args) === null;
    } else {
        if (!wp_next_scheduled($hookname)) {
            return wp_schedule_event($next_run, $schedule, $hookname, $args) === null;
        }
    }
}

function delete_cron($to_delete, $sig, $next_run)
{
    $crons = _get_cron_array();
    if (isset($crons[$next_run][$to_delete][$sig])) {
        $args = $crons[$next_run][$to_delete][$sig]['args'];
        wp_unschedule_event($next_run, $to_delete, $args);
        return true;
    }
    return false;
}
