<?php

function cryptum_nft_init()
{
	register_setting('cryptum_nft_options', 'cryptum_nft', array(
		'environment' => 'production',
		'contractId' => '',
		'storeId' => '',
		'apikey' => '',
	));
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
							<th scope="row"><label for="contractId">NFT Contract Id</label></th>
							<td>
								<input id="contractId" type="text" name="cryptum_nft[contractId]" value="<?php echo $options['contractId']; ?>" style="width: 70%" />
								<p><?php echo __('Enter the NFT contract id (Created in Cryptum Dashboard)'); ?></p>
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