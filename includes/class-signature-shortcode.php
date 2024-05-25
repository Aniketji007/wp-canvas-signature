<?php

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

if (!class_exists('SIGNATURE_SHORTCODE')) {
    class SIGNATURE_SHORTCODE
    {
        private static $instance = null;

        private static $assets_obj = null;
        
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
            add_shortcode('ad-signature', array($this, 'ad_signature_shortcode'));

            self::$assets_obj = SIGNATURE_ASSETS_LOADER::getInstance();
        }

        public function ad_signature_shortcode($atts)
        {
            self::$assets_obj->assets_loading();
            $attribute = shortcode_atts(
                array(
                    'color' => 'red',
                    'size' => 'medium',

                ),
                $atts
            );

            $random=random_int(0,999);

            $attribute['id']='adsc_wrapper_'.$random;

            $render_obj=SIGNATURE_SHORTCODE_RENDER::getInstance($attribute);
            return $render_obj->ad_signature_shortcode();
        }
    }
}
