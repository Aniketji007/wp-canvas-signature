<?php

/**
 * Plugin Name: AD Canvas Signature
 * Description: Creating signature plugin testing 
 * Author: Aniket Dogra
 * Author Uri: mfsstore.ezyro.com
 * Version: 1.0.0
 * Docs: plugin docs
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

define('ADSC__FILE', __FILE__);
define('ADSC_VERSION', '1.0.0');
define('ADSC_PLUGIN_BASE', plugin_basename(ADSC__FILE));
define('ADSC_PATH', plugin_dir_path(ADSC__FILE));
define('ADSC_URL', plugin_dir_url(ADSC__FILE));


if (!class_exists('AD_SIGNATURE')) {
    class AD_SIGNATURE
    {
        private static $instance = null;

        public static function getInstance()
        {
            if (!(self::$instance instanceof self)) {
                self::$instance = new self();
            }
            return self::$instance;
        }

        private function __construct()
        {
            // Initialize any required hooks or actions here

            register_activation_hook(ADSC__FILE, array($this, 'plugin_activate'));
            register_deactivation_hook(ADSC__FILE, array($this, 'plugin_deactivate'));
            add_action('plugins_loaded', array($this, 'include_files'));
            add_action('plugins_loaded', array($this, 'init_ajax_handler'));
        }

        public function include_files()
        {
            $includes_path = ADSC_PATH . 'includes/';
            $php_files = glob($includes_path . '*.php');
            foreach ($php_files as $file) {
                require_once $file;
            }

            if (class_exists('SIGNATURE_SHORTCODE')) {
                SIGNATURE_SHORTCODE::getInstance();
            }
        }

        public function init_ajax_handler()
        {
            require_once ADSC_PATH . 'helper/class-adsc-ajax-handler.php';
            if (class_exists('ADSC_AJAX_HANDLER')) {
                ADSC_AJAX_HANDLER::getInstance();
            }
        }

        public function plugin_activate()
        {
            update_option('ADSC_PLUGIN_STATUS', 'activate');
        }

        public function plugin_deactivate()
        {
            update_option('ADSC_PLUGIN_STATUS', 'deactivate');
        }
    }

    AD_SIGNATURE::getInstance();
}
