<?php
/**
 * Plugin Name: Whook security
 * Plugin URI: http://www.darteweb.com
 * Description: Scan installed plugins for vulnerabilities security
 * Version: 1.3
 * Author: D'arteweb
 * Author URI: http://www.darteweb.com
 * Requires at least: 3.0
 * Tested up to: 4.9.4
 *
 * Text Domain: 
 * Domain Path: 
 *
 */

define('whook_secuity_path',plugin_dir_path( __FILE__));
define('whook_secuity_plugin_url',plugin_dir_url(__FILE__));


function Whook_LoadScripts()
{
	wp_register_script('whook-tooltip-js',plugins_url('js/tooltipster.bundle.min.js',__FILE__));
	wp_enqueue_script('whook-tooltip-js');

	wp_register_style('whook-tooltip-style',plugins_url('css/tooltipster.bundle.min.css',__FILE__));
	wp_enqueue_style('whook-tooltip-style');

	wp_register_script('whook-plugin-script',plugins_url('js/whook-js.js',__FILE__));
	wp_enqueue_script('whook-plugin-script');
	
	wp_register_style('whook-style',plugins_url('css/whook-style.css',__FILE__));
	wp_enqueue_style('whook-style');
	
} 
add_action('admin_enqueue_scripts','Whook_LoadScripts'); 

add_action("wp_dashboard_setup", "whook_secuity_dashboard","high"); 

function whook_secuity_dashboard()
{
    wp_add_dashboard_widget("whook-wecuity-7", "Whook Secuity - Check Vulnerabilities ", "display_whook_secuity");
}

//callback to display the content in the widget
function display_whook_secuity()
{
	global $wp_version;
	$whook_obj = new Whook_Scanner();

    $scan_result = $whook_obj->Whook_ScanWordPress($wp_version);
	
	echo "<table class='whook-security-area'><tr><td>";
	echo '<b>Wordpress Version : '.$wp_version.'</b>';
	echo "</td></tr>
	      <tr><td>";
		  
	$msg = "<div style='clear:both;'></div><div class='green-area msg-box'><img src='".whook_secuity_plugin_url."/images/check.png' style='width: 20px;height: 20px;'> <span>Woot! No issue detected.</span></div>";
    
	if(isset($scan_result['status']['vulnerable']['vulnerable_status']) && $scan_result['status']['vulnerable']['vulnerable_status'] == 0)
	{
    	$msg = "<div style='clear:both;'></div><div class='green-area msg-box'><img src='".whook_secuity_plugin_url."/images/check.png' style='width: 20px;height: 20px;'> <span>Woot! No issue detected.</span></div>";
	}elseif(isset($scan_result['status']['vulnerable']['vulnerable_status']) && $scan_result['status']['vulnerable']['vulnerable_status'] == 1)
	{
		$error_msg = '';
		$i = 1;
		foreach($scan_result['status']['vulnerable']['vulnerable_error'] as $val)
		{
		   $error_msg.= $i.'.) '.$val.'<br/>';
		$i++;
		}
		$msg = "<div class='red-area msg-box'><img src='".whook_secuity_plugin_url."/images/close.png' style='width: 20px;height: 20px;'> <span>Vulnerble Wordpress, this may harm your website.</span><span class='whook-tooltip' title='".$error_msg."'><img src='".whook_secuity_plugin_url."/images/tooltip.png' style='width: 20px;height: 20px;'></span></div>";
	}elseif(isset($scan_result['status']['vulnerable']['vulnerable_status']) && $scan_result['status']['vulnerable']['vulnerable_status'] == 2)
	{
		$error_msg = '';
		$i = 1;
		foreach($scan_result['status']['vulnerable']['vulnerable_error'] as $val)
		{
		   $error_msg.= $i.'.) '.$val.'<br/>';
		$i++;
		}
		$msg = "<div class='yellow-area msg-box'><img src='".whook_secuity_plugin_url."/images/exc.png' style='width: 20px;height: 20px;'> <span>Vulnerability found, please upgrade wordpress.</span><span class='whook-tooltip' title='".$error_msg."'><img src='".whook_secuity_plugin_url."/images/tooltip.png' style='width: 20px;height: 20px;'></span></div>";
	}  
	echo $msg;
	echo "</td></tr></table>"; 
	
	$scan_result = array();

	$theme_details = wp_get_theme();
	$theme_template = $theme_details->get('TextDomain');
	$theme_version = $theme_details->get('Version');
	$theme_name = $theme_details->get('Name');

    $scan_result = $whook_obj->Whook_ScanThemes($theme_template,$theme_version);

	echo "<table class='whook-security-area'><tr><td>";
	
	 echo '<b>Active Theme : '.$theme_name;
	 echo '<br>Version : '.$theme_version.'</b>';

	echo "</td></tr>
	      <tr><td>";

	$msg = "<div style='clear:both;'></div><div class='green-area msg-box'><img src='".whook_secuity_plugin_url."/images/check.png' style='width: 20px;height: 20px;'> <span>Woot! No issue detected.</span></div>";

    
	if(isset($scan_result['status']['vulnerable']['vulnerable_status']) && $scan_result['status']['vulnerable']['vulnerable_status'] == 0)
	{
    	$msg = "<div style='clear:both;'></div><div class='green-area msg-box'><img src='".whook_secuity_plugin_url."/images/check.png' style='width: 20px;height: 20px;'> <span>Woot! No issue detected.</span></div>";
	}elseif(isset($scan_result['status']['vulnerable']['vulnerable_status']) && $scan_result['status']['vulnerable']['vulnerable_status'] == 1)
	{
		$error_msg = '';
		$i = 1;
		foreach($scan_result['status']['vulnerable']['vulnerable_error'] as $val)
		{
		   $error_msg.= $i.'.) '.$val.'<br/>';
		$i++;
		}
		$msg = "<div class='red-area msg-box'><img src='".whook_secuity_plugin_url."/images/close.png' style='width: 20px;height: 20px;'> <span>Vulnerble Theme, this may harm your website.</span><span class='whook-tooltip' title='".$error_msg."'><img src='".whook_secuity_plugin_url."/images/tooltip.png' style='width: 20px;height: 20px;'></span></div>";
	}elseif(isset($scan_result['status']['vulnerable']['vulnerable_status']) && $scan_result['status']['vulnerable']['vulnerable_status'] == 2)
	{
		$error_msg = '';
		$i = 1;
		foreach($scan_result['status']['vulnerable']['vulnerable_error'] as $val)
		{
		   $error_msg.= $i.'.) '.$val.'<br/>';
		$i++;
		}
		$msg = "<div class='yellow-area msg-box'><img src='".whook_secuity_plugin_url."/images/exc.png' style='width: 20px;height: 20px;'> <span>Vulnerability found, please upgrade theme.</span><span class='whook-tooltip' title='".$error_msg."'><img src='".whook_secuity_plugin_url."/images/tooltip.png' style='width: 20px;height: 20px;'></span></div>";
	}  
	echo $msg;
	echo "</td></tr></table>"; 
	
	echo "<div style='clear:both;'></div>";
}


function whook_jquery_plg_url() {
?>
<script type="text/javascript">
	var Whook_Plg_Url = '<?php echo plugin_dir_url(__FILE__); ?>';
</script>
<?php
}

add_filter('admin_head', 'whook_jquery_plg_url');


$Whook_Class_Path = plugin_dir_path(__FILE__).'include-classes/whook-class.php';
require_once $Whook_Class_Path;

Whook_Scanner::Whook_Add_Scanner();

