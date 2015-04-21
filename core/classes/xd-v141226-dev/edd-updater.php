<?php
/**
 * Project: core-test
 * File: edd-updater.php
 * User: Panagiotis Vagenas <pan.vagenas@gmail.com>
 * Date: 13/12/2014
 * Time: 8:18 πμ
 * Since: TODO ${VERSION}
 * Copyright: 2014 Panagiotis Vagenas
 */

namespace xd_v141226_dev;

if(!defined('WPINC'))
	exit('Do NOT access this file directly: '.basename(__FILE__));

class edd_updater extends framework{
	private $api_url  = '';
	private $api_data = array();
	private $slug     = '';
	private $do_check = false;

	/**
	 * @param array|framework $instance
	 *
	 * @throws \exception
	 */
	function __construct( $instance ) {
		parent::__construct($instance);
	}

	function init(){
		if($this->©option->get('edd.update', true)){
			$this->add_filter('pre_set_site_transient_update_plugins', '©edd_updater.pre_set_site_transient_update_plugins_filter');
			$this->add_filter('plugins_api', '©edd_updater.plugins_api_filter', 10, 3);
			$this->add_filter('http_request_args', '©edd_updater.http_request_args', 10, 2);
		}

		$this->api_url  = trailingslashit($this->©option->get('edd.store_url', true));
		$this->api_data = urlencode_deep(array(
				'version'   => $this->instance->plugin_version, // current version number
				'license'   => $this->getLicense(), // license key (used get_option above to retrieve from DB)
				'item_name' => $this->instance->plugin_name, // name of this plugin
				'author'    => 'Panagiotis Vagenas', // author of this plugin
				'url'       => home_url()
			)
		);
		$this->slug     = $this->instance->plugin_dir_basename;
	}

	public function isEDD(){
		$storeUrl = $this->©option->get('edd.store_url', true);
		return $this->©option->get('edd.update', true) && $this->©string->is_not_empty($storeUrl);
	}

	public function getLicense(){
		return $this->©option->get('edd_license');
	}

	public function getLicenseStatus(){
		return $this->©option->get('edd.license.status');
	}

	public function setLicense($license){
		$this->©option->®update(array('edd_license' => $license));
	}

	public function setLicenseStatus($status){
		$status = (bool)$status ? 1 : 0;
		$this->©option->®update(array('edd.license.status' => $status));
	}

	public function chkLicense($lic = '')
	{
		$license_data = $this->getLicenseDataFromServer();

		if(!is_object($license_data) || !isset($license_data->license)){
			return 2;
		}

		if ($license_data->license == 'valid') {
			return 1;
		} else {
			return 0;
		}
	}

	public function ®ajaxActivateLicense($license) {
		$this->check_arg_types('string', func_get_args());

		$licenseData = $this->activateLicense($license);

		$this->©action->set_call_data_for($this->dynamic_call(__FUNCTION__), get_defined_vars());
		$this->©ajax->sendJSONResult($licenseData);
	}

	public function activateLicense($license) {
		$this->check_arg_types('string', func_get_args());

		$license_data = $this->getLicenseDataFromServer('activate_license', $license);
		if(is_object($license_data)){
			if($license_data->license === 'valid'){
				$this->setLicense($license);
				$this->setLicenseStatus(1);
				$this->©notice->enqueue(array(
					'notice' => $this->__('License activated!'),
					'allow_dismissals' => false
				));
			}elseif($license_data->success == false && $license_data->error == 'expired'){
				$this->setLicense($license);
				$this->setLicenseStatus(0);
				$this->©notice->error_enqueue(array(
					'notice' => $this->__('Your license has expired'),
					'allow_dismissals' => false
				));
			} else{
				$this->setLicense($license);
				$this->setLicenseStatus(0);
				$this->©notice->error_enqueue(array(
					'notice' => $this->__('License couldn\'t be activated. Please check your input.'),
					'allow_dismissals' => false
				));
			}
			return $license_data;
		} else {
			$this->©notice->error_enqueue(array(
				'notice' => $this->__('There was an error contacting the license server. Please try again later.'),
				'allow_dismissals' => false
			));
			$this->setLicenseStatus(0);
			return false;
		}
	}

	public function ®ajaxDeactivateLicense($license){
		$licenseData = $this->deactivateLicense($license);
		$this->©action->set_call_data_for($this->dynamic_call(__FUNCTION__), get_defined_vars());
		$this->©ajax->sendJSONResult($licenseData);
	}

	public function deactivateLicense($license){
		$license_data = $this->getLicenseDataFromServer('deactivate_license', $license);
		if(is_object($license_data)){
			if( $license_data->license == 'deactivated' ){
				$this->setLicenseStatus(0);
				$this->©notice->enqueue(array(
					'notice' => $this->__('License deactivated!'),
					'allow_dismissals' => false
				));
			}
			return $license_data;
		}
		return false;
	}

	public function getLicenseDataFromServer($licenseAction = 'check_license', $license = ''){
		global $wp_version;

		$license = trim(empty($license) ? $this->getLicense() : $license);

		$api_params = array(
			'edd_action' => $licenseAction,
			'license' => $license,
			'item_name' => urlencode($this->instance->plugin_name),
			'url' => home_url()
		);

		// Call the custom API.
		$response = wp_remote_get(add_query_arg($api_params, $this->©option->get('edd.store_url', true)), array('timeout' => 15, 'sslverify' => false));

		if (is_wp_error($response)) {
			return 2;
		}

		return json_decode(wp_remote_retrieve_body($response));
	}

	/**
	 * Check for Updates at the defined API endpoint and modify the update array.
	 *
	 * This function dives into the update API just when WordPress creates its update array,
	 * then adds a custom API call and injects the custom plugin data retrieved from the API.
	 * It is reassembled from parts of the native WordPress plugin update code.
	 * See wp-includes/update.php line 121 for the original wp_update_plugins() function.
	 *
	 * @uses api_request()
	 *
	 * @param array $_transient_data Update array build by WordPress.
	 * @return array Modified update array with custom plugin data.
	 */
	function pre_set_site_transient_update_plugins_filter( $_transient_data ) {

		if( empty( $_transient_data ) || ! $this->do_check ) {

			// This ensures that the custom API request only runs on the second time that WP fires the update check
			$this->do_check = true;

			return $_transient_data;
		}

		$to_send = array( 'slug' => $this->slug );

		$api_response = $this->api_request( 'plugin_latest_version', $to_send );
		if( false !== $api_response && is_object( $api_response ) && isset( $api_response->new_version ) ) {

			if( version_compare( $this->instance->plugin_version, $api_response->new_version, '<' ) ) {
				$_transient_data->response[$this->instance->plugin_dir_file_basename] = $api_response;
			}
		}

		return $_transient_data;
	}


	/**
	 * Updates information on the "View version x.x details" page with custom data.
	 *
	 * @uses api_request()
	 *
	 * @param mixed $_data
	 * @param string $_action
	 * @param object $_args
	 * @return object $_data
	 */
	function plugins_api_filter( $_data, $_action = '', $_args = null ) {
		if ( ( $_action != 'plugin_information' ) || !isset( $_args->slug ) || ( $_args->slug != $this->slug ) ) return $_data;

		$to_send = array( 'slug' => $this->slug );

		$api_response = $this->api_request( 'plugin_information', $to_send );
		if ( false !== $api_response ) $_data = $api_response;
		return $_data;
	}


	/**
	 * Disable SSL verification in order to prevent download update failures
	 *
	 * @param array $args
	 * @param string $url
	 * @return object $array
	 */
	function http_request_args( $args, $url ) {
		// If it is an https request and we are performing a package download, disable ssl verification
		if( strpos( $url, 'https://' ) !== false && strpos( $url, 'edd_action=package_download' ) ) {
			$args['sslverify'] = false;
		}

		return $args;
	}

	/**
	 * Calls the API and, if successfull, returns the object delivered by the API.
	 *
	 * @uses get_bloginfo()
	 * @uses wp_remote_post()
	 * @uses is_wp_error()
	 *
	 * @param string $_action The requested action.
	 * @param array $_data Parameters for the API action.
	 * @return false||object
	 */
	private function api_request( $_action, $_data ) {

		global $wp_version;

		$data = array_merge( $this->api_data, $_data );

		if( $data['slug'] != $this->slug )
			return;

		if( empty( $data['license'] ) )
			return;

		$api_params = array(
			'edd_action' => 'get_version',
			'license'    => $data['license'],
			'name'       => $data['item_name'],
			'slug'       => $this->slug,
			'author'     => $data['author'],
			'url'        => home_url()
		);
		$request = wp_remote_post( $this->api_url, array( 'timeout' => 15, 'sslverify' => false, 'body' => $api_params ) );

		if ( ! is_wp_error( $request ) ):
			$request = json_decode( wp_remote_retrieve_body( $request ) );
			if( $request && isset( $request->sections ) )
				$request->sections = maybe_unserialize( $request->sections );

			return $request;
		else:
			return false;
		endif;
	}
}
