<?php

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

if (!class_exists('SIGNATURE_SHORTCODE_RENDER')) {
    class SIGNATURE_SHORTCODE_RENDER
    {
        private static $instance = null;
        private static $attr = null;

        public static function getInstance($attr)
        {
            if (!(self::$instance instanceof self)) {
                self::$instance = new self($attr);
            }
            return self::$instance;
        }

        private function __construct($attr)
        {
            self::$attr = $attr;
        }

        public function ad_signature_shortcode()
        {
            if (null === self::$attr) {
                return '';
            }

            $output = '<section class="adsc_signature_wrapper" id="' . esc_attr(self::$attr['id']) . '">';
            $output .= '<canvas width="500" height="200" style="border: 1px solid black;">Your browser doesn\'t support the HTML5 canvas tag</canvas>';
            $output .= '<button class="update">Update Data</button>';
            $output .= '<button class="clear">Clear</button>';
            $output .= '</section>';

            $output .= '<section class="adsc_img_wrapper">';
            $image_dir = ADSC_PATH . 'includes/images/';
            if (file_exists($image_dir) && is_dir($image_dir)) {
                $images = glob($image_dir . '*.{jpg,jpeg,png,gif}', GLOB_BRACE);
                foreach ($images as $image) {
                    $output .= '<img src="' . esc_url(ADSC_URL . 'includes/images/' . basename($image)) . '" alt="Signature Image">';
                }
            }
            $output .= '</section>';

            return $output;
        }
    }
}
