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

		woocommerce_wp_checkbox(
			array(
				'id' => '_cryptum_nft_options_token_enable',
				'placeholder' => '',
				'label' => __('Enable token link', 'woocommerce'),
				'description' => __('Enable/Disable link between this product and a token'),
				'desc_tip' => 'true'
			)
		);
		echo '<hr>';
		woocommerce_wp_text_input(
			array(
				'id' => '_cryptum_nft_options_token_uri',
				'placeholder' => 'Ex: https://example.com/',
				'label' => __('Token URI', 'woocommerce'),
				'description' => __('Token URI pointing to the location that has this token attributes'),
				'desc_tip' => 'true'
			)
		);

		wc_enqueue_js("
			function handleTokenEnableCheckbox() {
				if (jQuery('#_cryptum_nft_options_token_enable').is(':checked')) {
					jQuery('.form-field._cryptum_nft_options_token_uri_field').show();
				} else {
					jQuery('.form-field._cryptum_nft_options_token_uri_field').hide();
				}
			}
			jQuery('#_cryptum_nft_options_token_enable').click(function() {
				handleTokenEnableCheckbox();
			});
			handleTokenEnableCheckbox();
		");
		echo '</div>';
	});

	add_action('woocommerce_process_product_meta', function ($post_id) {
		_log('Saving custom fields ' . $post_id . json_encode($_POST));
		$product = wc_get_product($post_id);
		$product->update_meta_data('_cryptum_nft_options_token_enable', $_POST['_cryptum_nft_options_token_enable']);
		$product->update_meta_data('_cryptum_nft_options_token_uri', $_POST['_cryptum_nft_options_token_uri']);
		$product->save();
	});

	add_action('woocommerce_product_thumbnails', function () {
		$contractAddress = '0xbD72Dde3b53fDC81b33BA6615403DDf802bE7c57';
		$blockchain = 'Celo';
		echo (
			'<div id="_cryptum_nft_info" style="background-color: #75757526; padding: 10px;">
				<div style="display: flex;">
					<h4 style="flex-grow:1;">' . __('Chain info') . '</h4>
					<span id="_cryptum_nft_token_info" title="' . __('When you buy this product, you will receive a non-fungible token from the ' . $blockchain . ' network. The redemption instructions will be sent by email.') . '">
						<i class="dashicons dashicons-info"></i>
					</span>
				</div>
				<hr style="margin: 5px 0;">
				<p style="font-size: 14px;">' . __('Contract Address') . ': ' . $contractAddress . '</p>
				<p style="font-size: 14px;">Blockchain: ' . $blockchain . '</p>
			</div>
			<style>
			.ui-tooltip {
				padding: 10px 20px;
				color: white;
				border-radius: 20px;
				font-size: 14px sans-serif;
				box-shadow: 0 0 7px grey;
			}
			</style>
			'
		);

		wp_enqueue_style('jquery-ui', 'http://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css');
		wp_enqueue_script('jquery-ui', 'https://code.jquery.com/ui/1.12.1/jquery-ui.js');
		wc_enqueue_js("
			jQuery(function() {
				jQuery('#_cryptum_nft_token_info').tooltip({
					position: { my: 'left+15 center', at: 'right center' }
				});
			});
		");
	}, 19);
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
