<?php
defined('ABSPATH') or die('No script kiddies please!');

/*
Plugin Name: Paraşüt - Woocommerce Entegrasyonu
Plugin URI: http://www.iova.co.uk
Description: Paraşüt ile woocommerce entegrasyonu sağlar.<a href="https://www.iova.co.uk">IOVA</a>
Author: Agit Isik
Version: 1.0.0
Author URI: www.iova.co.uk
*/
include 'vendor/autoload.php';
include 'functions.php';
include 'intervals.php'; // Cron Intervals
include 'crons.php'; // All Cron Functions


/** Form  */
function parasutFunction()
{
    $args = array(
        'status' => get_option('parasut')['transfer_order_status'],
        'limit' => '10',
        'orderby' => 'date',
        'order' => 'DESC',
    );
    $orders = wc_get_orders( $args );
    foreach ($orders as $order){
    echo "<pre>"; print_r($order->get_data());

    }


    if (!current_user_can('manage_options')) {
        wp_die(__('You do not have sufficient permissions to access this page.'));
    }
    echo '<div class="wrap">';
    echo "<h2>" . __('Paraşüt API Bilgileri - Woocommerce Entegrasyonu - IOVA', 'parasut-iova') . "</h2>";

    if ($_POST['action'] == 'update') {
        update_option('parasut', $_POST['parasut']);
        successNotification();
    }
    ?>

    <form method="POST">
        <?php settings_fields('parasut');
        do_settings_sections(__FILE__);
        $options = get_option('parasut');
        ?>
        <table class="form-table">
            <tr>
                <th scope="row"><label for="parasut_client_id">Client ID</label></th>
                <td><input name="parasut[client_id]" type="text" id="parasut_client_id"
                           value="<?= @$options['client_id']; ?>" class="regular-text"/>
                </td>
            </tr>
            <tr>
                <th scope="row"><label for="parasut_client_secret">API Secret</label></th>
                <td><input name="parasut[client_secret]" type="password" id="parasut_client_secret"
                           value="<?= @$options['client_secret']; ?>"
                           class="regular-text"/></td>
            </tr>
            <tr>
                <th scope="row"><label for="Kullanıcı Adı">Paraşüt Kullanıcı Adı</label></th>
                <td><input name="parasut[username]" type="text" id="parasut_username"
                           value="<?= @$options['username']; ?>" class="regular-text"/>
                </td>
            </tr>
            <tr>
                <th scope="row"><label for="client_secret">Paraşüt Şifre</label></th>
                <td><input name="parasut[password]" type="password" id="parasut_password"
                           value="<?= @$options['password']; ?>" class="regular-text"/>
                </td>
            </tr>
            <tr>
                <th scope="row"><label for="client_secret">Şirket ID</label></th>
                <td><input name="parasut[company_id]" type="number" id="parasut_company_id"
                           value="<?= @$options['company_id']; ?>"
                           class="regular-text"/></td>
            </tr>
            <tr>
                <th scope="row"><label for="client_secret">Aktarılacak Sipariş Durumu</label></th>
                <td><select name="parasut[transfer_order_status]">
                        <option>Seçiniz</option>
                        <?php foreach (wc_get_order_statuses() as $key => $status){$code = trim(str_replace('wc-','',$key)); ?>
                            <option <?=@$options['transfer_order_status'] == $code ? 'selected' : '';?> value="<?=$code;?>"><?=$status;?></option>
                        <?php }?>
                    </select></td>
            </tr>
            <tr>
                <th scope="row"><label for="client_secret">Fatura Resmileştirme</label></th>
                <td><input type="checkbox" name="parasut[einvoice]" <?= @$options['einvoice'] ?'checked' : ''; ?> /> </td>
            </tr>
        </table>
        <p class="submit">
            <input type="submit" name="submit" id="submit" class="button button-primary"
                   value="<?php esc_attr_e('Save Changes') ?>"/>
    </form>

    <b>Entegrasyon Durumu
        : <?= @$options['status'] ? '<span style="color:green">Paraşüt Bağlantısı Mevcut</span>' : '<span style="color:red">Paraşüt ile bağlantı bulunmamaktadır.</span>'; ?>
    </b>
    <br/>
    <hr/>
    <h2>Cron Ayarları</h2>
    <span>wget --delete-after <?= get_site_url(); ?>/wp-cron.php</span>
    <br />
    <span>Disable Auto Cron : define('DISABLE_WP_CRON', true);</span>
    <!--wget -q -O - http://yourwebsite.com/wp-cron.php?doing_wp_cron >/dev/null 2>&1-->
    <br/>

    <?php
}