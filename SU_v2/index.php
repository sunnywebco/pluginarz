<?php
/*
Plugin Name: نمایش اطلاعات ارز - سانی وب
Plugin URI:  https://api.sunnyweb.ir
Description: نمایش قیمت های به روز و بلادرنگ قیمت ها ارز
Version:     1.1.0
Author:      تیم برنامه نویسی سانی وب
Author URI:  http://sunnyweb.ir
License:     GPL2
License URI: https://www.gnu.org/licenses/gpl-2.0.html
*/



// add menu
add_action('admin_menu', 'SUN_API___');
function SUN_API___() {

    //create new top-level menu
    add_menu_page('وب سرویس سانی وب نمایش اطلاعات ارز', 'قیمت ارز', 'administrator', __FILE__, 'my_cool_plugin_settings_page' , plugins_url('/images/logo.png', __FILE__) );

    //call register settings function
    add_action( 'admin_init', 'SUN_API__' );
}
// add menu


//create row in tab setting options
function SUN_API__() {
    register_setting( 'SUN_API_', 'api' );
    register_setting( 'SUN_API_', 'symbolics' );
}
//create row in tab setting options


//SELECT row in tab setting options
$api_e = esc_attr(get_option('api'));
$symbolics = esc_attr(get_option('symbolics'));

//SELECT row in tab setting options


// SHORT CODE
function SUN_API____($atts) {
    global $api_e;
    global $symbolics;
    $json = json_decode(file_get_contents('https://api.sunnyweb.ir/api/'.$api_e),true);
    $array = explode( ',', $symbolics);
    echo '
    <table>
      <tr>
        <th>نماد</th>
        <th>پرچم</th>
        <th>قیمت ریال</th>
      </tr>
    ';
    foreach ($array as $item):
            echo'
            <tr>
            <td>'.$item.'</td>
            <td><img src="https://api.sunnyweb.ir/images/'.strtolower($item).'.svg" style="width: 30px;"></td>
            <td>'.number_format($json[0][$item],0).'</td>
            </tr>
            ';
    endforeach;
    echo '</table>';
}

add_shortcode('SUN_A', 'SUN_API____');
// SHORT CODE


function my_cool_plugin_settings_page() {
    ?>
    <div class="wrap">
        <h1>نمایش جدول اطلاعات ارز سانی وب</h1>
        <p>برای تهیه توکن  سانی وب می توانید به <a href="https://api.sunnyweb.ir" target="_blank">اینجا</a> مراجعه و اشراک خود را تهیه نمایید</p>
        <form method="post" action="options.php">
            <?php settings_fields( 'SUN_API_' ); ?>
            <?php do_settings_sections( 'SUN_API_' ); ?>
            <table class="form-table">
                <tr valign="top">
                    <th scope="row">توکن کلید وب سرویس</th>
                    <td><input type="text" name="api" style="width: 30%;" value="<?php echo esc_attr(get_option('api')); ?>"  /></td>
                </tr>
                <tr valign="top">
                    <th scope="row">مخفف لاتین ارز (انگلیسی)</th>
                    <td><input type="text" name="symbolics" style="width: 100%;" value="<?php echo esc_attr( get_option('symbolics') ); ?>" placeholder="USD,EUR,OMR,GEL" /></td>
                </tr>
            </table>
            <h2>راهنما</h2>
            <p> جهت نمایش جدول از شورت کد [SUN_A] استفاده نمایید</p>
            <?php submit_button(); ?>
        </form>
    </div>
<?php } ?>