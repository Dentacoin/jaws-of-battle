<?php

require 'plugin-updates/plugin-update-checker.php';

class SFWD_Update_Checker extends PluginUpdateChecker {
	var $plugin_name;
	var $plugin_basename;
	var $license_key;
	var $options_page;
	var $renewal_page;
	
	function __construct($metadataUrl, $pluginFile, $slug = '', $checkPeriod = 12, $optionName = ''){
		parent::__construct( $metadataUrl, $pluginFile, $slug, $checkPeriod, $optionName );
	}
	
	function has_update() {
		$updates = (object)Array( 'response' => Array() );
		$updates = $this->injectUpdate( $updates );
		return ( !empty( $updates->response ) && !empty( $updates->response[$this->plugin_basename] ) );
	}

	/**
	 * Check license key format.
	 */
	function check_key_format( $license_key ) {
		return preg_match( "/^(?:MT-)?[A-Z\d]{14}$|^[A-Z\d]{17}$/", $license_key );	
	}

	/**
	 * Get the license key and sanitize it.
	 */
	function get_license_key( $license_key = null ) {
		if ( $license_key == null )
			$license_key = $this->license_key;
		$license_key = strtoupper( trim( $license_key ) );
		return $license_key;
	}
	
	/**
	 * Get the verification code.
	 */	
	function get_verification_code( $license_key = null ) {
		$license_key = $this->get_license_key( $license_key );
		return strtoupper( str_replace( "=", '', base64_encode( sha1( $license_key, true ) ) ) );
	}
	
	/**
	 * Check the license key.
	 */
	function check_license_key( $license_key = null ) {
		$license_key = $this->get_license_key( $license_key );
		return $this->check_key_format( $license_key );
	}
	
	/**
	 * Alert the user to enter the license key, if needed.
	 */

	function key_warning() {
		$msg = '';
		$license_key = null;
		if ( !empty( $_POST ) && ( !empty( $_POST['license_key'] ) ) ) $license_key = $_POST['license_key'];
		if ( !$this->check_license_key( $license_key ) )
			$msg = "<p><strong>" . sprintf( __('%s is almost ready.'), $this->plugin_name ) . "</strong> " 
				. sprintf(__('You must <a href="%s">enter a valid License Key</a> for it to work.'), "admin.php?page={$this->options_page}" )
				. __( ' Need a license key?', 'all_in_one_seo_pack' ) 
				. ' <a href="' . $this->renewal_page . '" target="_blank">' . __( 'Purchase one now', 'all_in_one_seo_pack' ) . '</a>';
		else
			if ( $this->has_update() )
				$msg = sprintf( __( "There is a new version of %s available. Go to <a href='%s'>the plugins page</a> for details.", 'all_in_one_seo_pack' ), AIOSEOP_PLUGIN_NAME, network_admin_url( 'plugins.php' ) );
		if ( !empty( $msg ) ) {
			aioseop_output_dismissable_notice( $msg, 'aioseop-warning' );			
		}
	}

	/**
	 * Add row to Plugins page with licensing information, if license key is invalid or not found.
	 */
	function add_plugin_row() {
		add_action( 'in_plugin_update_message-' . $this->plugin_basename, Array( $this, 'update_message' ), 10, 2 );
		if ( !$this->check_license_key() )
			echo '<tr class="plugin-update-tr"><td colspan="3" class="plugin-update"><div class="update-message"><span style="border-right: 1px solid #DFDFDF; margin-right: 5px;"><a href="admin.php?page=' . $this->options_page . '">'
				 . __( 'Manage Licenses', 'all_in_one_seo_pack' ) . '</a> ' . __( 'License Key is not set yet or invalid. ', 'all_in_one_seo_pack' ) . __( ' Need a license key?', 'all_in_one_seo_pack' ) 
				 . ' <a href="' . $this->renewal_page . '" target="_blank">' . __( 'Purchase one now', 'all_in_one_seo_pack' ) . '</a></span></div></td></tr>';
	}

	/**
	 * Get update information back from the server, display to the user on the plugins page.
	 */
	function update_message( $plugin_data, $r ) {
		echo " " . __("Notice: ", 'all_in_one_seo_pack' ) . $r->upgrade_notice;
	}
	
	function license_change_check( $options, $location ) {
		if ( ( $location == null ) && isset( $options['aiosp_license_key'] ) ) {
			if ( ( $options['aiosp_license_key'] != $this->license_key ) && $this->check_license_key( $options['aiosp_license_key'] ) )
				delete_transient( $this->slug . '_updates_checked' );
			$this->license_key = $options['aiosp_license_key'];
		}
		return $options;
	}
	
	function update_check( $options, $location ) {
		if ( $location == null ) {
			if ( $this->check_license_key() ) {
				if ( get_transient( $this->slug . '_updates_checked' ) )
					$this->maybeCheckForUpdates();
				else
					$this->checkForUpdates();
				set_transient( $this->slug . '_updates_checked', true, 300 );
			}
		}
	}
	
	function add_secret_key($query) {
		$query['plugin'] = $this->slug;
		$query['secret'] = $this->license_key;
		return $query;
	}
}

