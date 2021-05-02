<?php

/**
 * Plugin Name: Cryptum NFT Plugin
 * Plugin URI: https://github.com/blockforce-official/cryptum-nft-wordpress-plugin
 * Description: Cryptum NFT Plugin
 * Version: 1.0.0
 * Author: Blockforce
 * Author URI: https://blockforce.in
 * License: GNU General Public License v3.0
 * License URI: http://www.gnu.org/licenses/gpl-3.0.html
 */

defined('ABSPATH') or exit;

// Make sure WooCommerce is active
if (!in_array('woocommerce/woocommerce.php', apply_filters('active_plugins', get_option('active_plugins')))) {
	add_action('admin_notices', function () {
		echo '<div id="setting-error-settings_updated" class="notice notice-error">
			<p>' . __("Cryptum Checkout Plugin needs Woocommerce enabled to work correctly. Please install and/or enable Woocommerce plugin", 'cryptum_nft') . '</p>
		</div>';
	});
	return;
}

if (is_admin() && (!defined('DOING_AJAX') || !DOING_AJAX)) {
	require 'class-admin.php';
}

function cryptum_nft_plugin_loaded()
{
	add_filter('woocommerce_product_data_tabs', function ($tabs) {
		$tabs['cryptum_nft_options'] = [
			'label' => __('Cryptum NFT Options', 'txtdomain'),
			'target' => 'cryptum_nft_options'
		];
		return $tabs;
	});

	add_action('woocommerce_product_data_panels', function () {
		echo '<div id="cryptum_nft_options" class="panel woocommerce_options_panel hidden">';

		woocommerce_wp_text_input(
			array(
				'id' => '_cryptum_nft_options_contract_id',
				'placeholder' => '',
				'label' => __('NFT contract id', 'woocommerce'),
				'description' => __('Contract id generated in Cryptum Dashboard'),
				'desc_tip' => 'true'
			)
		);
		woocommerce_wp_text_input(
			array(
				'id' => '_cryptum_nft_options_token_uri',
				'placeholder' => 'Ex: https://example.com/',
				'label' => __('Token URI', 'woocommerce'),
				'description' => __('Token URI'),
				'desc_tip' => 'true'
			)
		);

		echo '</div>';
	});

	add_action('woocommerce_process_product_meta', function ($post_id) {
		// _log('Saving custom fields ' . $post_id . json_encode($_POST));
		$product = wc_get_product($post_id);

		$cryptum_nft_options_contract_id = isset($_POST['_cryptum_nft_options_contract_id']) ? $_POST['_cryptum_nft_options_contract_id'] : '';
		$cryptum_nft_options_token_uri = isset($_POST['_cryptum_nft_options_token_uri']) ? $_POST['_cryptum_nft_options_token_uri'] : '';

		$product->update_meta_data('_cryptum_nft_options_contract_id', sanitize_text_field($cryptum_nft_options_contract_id));
		$product->update_meta_data('_cryptum_nft_options_token_uri', sanitize_text_field($cryptum_nft_options_token_uri));
		$product->save();
	});
}

function _log($message, $level = 'info')
{
	wc_get_logger()->log($level, $message, array('source' => 'cryptum_nft'));
}

function cryptum_nft_plugin_links($links)
{
	$plugin_links = array(
		'<a href="options-general.php?page=cryptum_nft">' . __('Settings', 'cryptum_nft') . '</a>'
	);
	return array_merge($plugin_links, $links);
}
add_filter('plugin_action_links_' . plugin_basename(__FILE__), 'cryptum_nft_plugin_links');

add_action('plugins_loaded', 'cryptum_nft_plugin_loaded', 11);
