<?php
/*
Plugin Name: Counters Integration
Plugin URI: https://shra.ru/hobbies/plugins/wordpress-counters-integration/
Description: You can add both are <a href="https://analytics.google.com/">Google Analytics</a> and <a href="https://metrika.yandex.ru/list?">Yandex Metrika</a> counter's codes on all pages.
Version: 1.0.1
Author: Shra
Author URI: https://shra.ru/
*/

class CountersIntClass
{

	public function __construct()
	{
		if (is_admin()) {
			//Actions, add link to admin menu
			add_action('admin_menu', array($this, '_add_menu'));
		} else {
			//insert counters to page head
			add_action('wp_head', array($this, '_add_counters'));
		}
	}

	public function _add_counters()
	{
		$acs = get_option('counters_integration_settings');
		//echo GA & YM codes into head section of pattern
		if (!empty($acs['GA'])) print $acs['GA'];
		if (!empty($acs['YM'])) print $acs['YM'];
	}

	/* admin_menu hook */
	public function _add_menu()
	{
		add_options_page('Counters Integration', 'Counters Integration', 8, __FILE__, array($this, '_options_page'));
	}

	/* Options admin page */
	public function _options_page()
	{

		switch ($_POST['action']) {
			case 'save_settings':
				//check & store new values
				$GA = stripslashes($_POST['GA']);
				$YM = stripslashes($_POST['YM']);

				update_option('counters_integration_settings', array('GA' => $GA, 'YM' => $YM));
				echo '<div class="updated"><p>' . __("Setting are updated.") . '</p></div>';
				break;
		}
		$acs = get_option('counters_integration_settings');

?>
		<div class="wrap">
			<h2><?= __('Counters Integration settings'); ?></h2>
			<form method="post">
				<input type="hidden" name="action" value="save_settings" />
				<fieldset class="options">
					<legend></legend>
					<table border=0 cellspacing=0 cellpadding=0 width=700>
						<tr>
							<td>
								<label for="connections"><?= __('Please, place here the <b>Google Analytics</b> JS code') ?></label><br />
								<small><?= __('If you haven\'t counter code, leave this field blank.') ?></small><br />
								<textarea name="GA" rows="5" cols="60" wrap="off"><?= htmlspecialchars($acs['GA']) ?></textarea><br /><br />
							</td>
						</tr>
						<tr>
							<td>
								<label for="connections"><?= __('Place here the <b>Yandex Metrika</b> JS code') ?></label><br />
								<small><?= __('Without informer, counter only.') . ' ' . __('If you haven\'t counter code, leave this field blank.') ?></small><br />
								<textarea name="YM" rows="5" cols="60" wrap="off"><?= htmlspecialchars($acs['YM']) ?></textarea><br /><br />
							</td>
						</tr>
						<tr>
							<td>
								<input type="submit" class="button-primary" name="sbm" value="<?= __('Save changes') ?>" />
							</td>
						</tr>
					</table>
				</fieldset>
			</form>
		</div>
<?php
	}


	/* install actions (when activate first time) */
	static function install()
	{
		//set defaults
		add_option('counters_integration_settings', CountersIntClass::default_settings());
	}

	static function default_settings()
	{
		return array('GA' => '', 'YM' => '');
	}

	/* uninstall hook */
	static function uninstall()
	{
		global $wpdb;
		delete_option('counters_integration_settings');
	}
}

register_uninstall_hook(__FILE__, array('CountersIntClass', 'uninstall'));
register_activation_hook(__FILE__, array('CountersIntClass', 'install'));

if (class_exists("CountersIntClass")) {
	$shra_counter_int_obj = new CountersIntClass();
}

if (isset($shra_counter_int_obj)) {
		//to do:
	;
}
