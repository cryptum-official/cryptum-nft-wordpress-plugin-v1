<?php

function cryptum_nft_init()
{
	register_setting('cryptum_nft_options', 'cryptum_nft', function ($input) {
		// $options = get_option('cryptum_nft');
		// $url = $input['environment'] == 'production' ? 'https://api.cryptum.io' : 'https://api-dev.cryptum.io';
		// $contractAddress = $input['contractAddress'];

		// $response = wp_safe_remote_get("$url/issue?protocol=", ['x-api-key' => $input['apikey']]);

		// if (is_wp_error($response)) {
		// 	_log(json_encode($response, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
		// 	add_settings_error(
		// 		'cryptum_nft',
		// 		'Configuration error',
		// 		__($response->get_error_message(), 'cryptum_nft'),
		// 		'error'
		// 	);
		// 	return $options;
		// }
		// $responseBody = json_decode($response['body'], true);

		// if (isset($responseBody['error'])) {
		// 	$error_message = $responseBody['error']['message'];
		// 	_log(json_encode($response, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
		// 	add_settings_error(
		// 		'cryptum_nft',
		// 		'Configuration error',
		// 		__($error_message, 'cryptum_nft'),
		// 		'error'
		// 	);
		// 	return $options;
		// }

		return $input;
	});
}
add_action('admin_init', 'cryptum_nft_init');

/**
 * Add menu item
 * @return [type] [description]
 */
function show_cryptum_nft_options()
{
	add_options_page('Cryptum NFT Options', 'Cryptum NFT', 'manage_options', 'cryptum_nft', 'cryptum_nft_options');
}
add_action('admin_menu', 'show_cryptum_nft_options');

/**
 * [cryptum_nft_options description]
 * @return [type] [description]
 */
function cryptum_nft_options()
{
?>
	<link href="<?php echo plugins_url('admin.css', __FILE__); ?>" rel="stylesheet" type="text/css">
	<div class="cryptum_nft_admin_wrap">
		<div class="cryptum_nft_admin_top">
			<h1><?php echo __('Cryptum NFT Settings') ?></h1>
			<hr>
		</div>
		<div class="cryptum_nft_admin_main_wrap">
			<div class="cryptum_nft_admin_main_left">
				<p class="cryptum_nft_admin_main_p">
					<?php echo __('This plugin allows to configure your store environment. It is necessary to create an account in
					Cryptum Dashboard to receive the store id and API key to fill out the fields below.') ?>
				</p>
				<p class="cryptum_nft_admin_main_p">
					<strong>Obs:</strong>

					<br>
					<?php echo __('If you are just testing the plugin, you should go to the ') ?>
					<a href="https://backoffice-dev.cryptum.io/" target="_blank">sandbox dashboard</a>
					<?php echo __(' to get the store id and API key for testnet.') ?>

					<br>
					<?php echo __('If you will use the plugin in production mode, you should go to the ') ?>
					<a href="https://backoffice.cryptum.io/" target="_blank">production dashboard</a>
					<?php echo __('to get the store id and API key for production.') ?>
				</p>
				<br>
				<form method="post" action="options.php" id="options">

					<?php
					settings_fields('cryptum_nft_options');
					$options = get_option('cryptum_nft');
					?>
					<table class="form-table">

						<tr valign="top">
							<th scope="row"><label for="order"><?php echo __('Environment'); ?></label></th>
							<td>
								<select name="cryptum_nft[environment]">
									<option value="production" <?php if ($options['environment'] == 'production') {
																	echo ' selected="selected"';
																} ?>>Production</option>
									<option value="test" <?php if ($options['environment'] == 'test') {
																echo ' selected="selected"';
															} ?>>Test</option>
								</select>
								<br>
								<p><?php echo __('Choose your environment. The Test environment should be used for testing only.'); ?></p>
							</td>
						</tr>
						<tr valign="top">
							<th scope="row"><label for="storeId">Store Id</label></th>
							<td>
								<input id="storeId" type="text" name="cryptum_nft[storeId]" value="<?php echo $options['storeId']; ?>" style="width: 70%" />
								<p><?php echo __('Enter your Store ID generated in Cryptum Dashboard'); ?></p>
							</td>
						</tr>
						<tr valign="top">
							<th scope="row"><label for="apikey">API key</label></th>
							<td>
								<input id="apikey" type="text" name="cryptum_nft[apikey]" value="<?php echo $options['apikey']; ?>" style="width: 70%" />
								<p><?php echo __('Enter your Cryptum API Key (Generated in Cryptum Dashboard, API Keys Section)'); ?></p>
							</td>
						</tr>
						<tr valign="top">
							<th scope="row"><label for="order"><?php echo __('NFT Contract Blockchain'); ?></label></th>
							<td>
								<select name="cryptum_nft[blockchain]">
									<option value="CELO" <?php if ($options['blockchain'] == 'CELO') {
																	echo ' selected="selected"';
																} ?>>CELO</option>
								</select>
								<br>
								<p><?php echo __('Choose your blockchain that your token was issued'); ?></p>
							</td>
						</tr>
						<tr valign="top">
							<th scope="row"><label for="contractAddress">NFT Contract Address</label></th>
							<td>
								<input id="contractAddress" type="text" name="cryptum_nft[contractAddress]" value="<?php echo $options['contractAddress']; ?>" style="width: 70%" />
								<p><?php echo __('Enter the NFT contract address'); ?></p>
							</td>
						</tr>
					</table>

					<p class="submit">
						<input type="submit" class="button-primary" value="<?php _e('Save Changes') ?>" />
					</p>
				</form>
			</div>
		</div>

	<?php
}
	?>