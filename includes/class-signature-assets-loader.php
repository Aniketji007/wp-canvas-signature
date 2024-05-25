<?php

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

if (!class_exists('SIGNATURE_ASSETS_LOADER')) {
    class SIGNATURE_ASSETS_LOADER
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
            add_action("init", array($this, 'load_global_assets'));
        }

        public function assets_loading()
        {
            wp_enqueue_script('adsc-script');
            wp_enqueue_style('adsc-style');

            $data = array(
                'ajax_url' => admin_url('admin-ajax.php'),
                'action' => 'adsc_save_signature',
                'nonce' => wp_create_nonce('adsc_signature_data'),
            );
            wp_localize_script('adsc-script', 'adcsExtraData', $data);
        }

        public function load_global_assets()
        {
            wp_register_script('adsc-script', ADSC_URL . 'includes/assets/js/adsc-index.min.js', array('jquery'), ADSC_VERSION, true);
            wp_register_style('adsc-style', ADSC_URL . 'includes/assets/css/adsc-index.min.css', ADSC_VERSION);
        }
    }
}
