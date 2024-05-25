<?php

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

if (!class_exists('ADSC_AJAX_HANDLER')) {
    class ADSC_AJAX_HANDLER
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

            add_action('wp_ajax_signature_data_save', array($this, 'signature_data_save'));
            add_action('wp_ajax_nopriv_signature_data_save', array($this, 'signature_data_save'));
        }

        public function signature_data_save()
        {
            // Check if the nonce is valid
            if (!check_ajax_referer('adsc_signature_data', 'nonce', false)) {
                wp_send_json_error('Invalid nonce', 400);
            }

            // Process the AJAX request
            // Assuming the data is sent via POST as 'signature_image'
            $signature_image = isset($_POST['signature_image']) ? sanitize_text_field($_POST['signature_image']) : '';
            if (empty($signature_image)) {
                wp_send_json_error('No image data provided', 400);
            }
            $encoded_image = explode(",", $signature_image)[1];
            if (!preg_match('/^[a-zA-Z0-9\/\r\n+]*={0,2}$/', $encoded_image)) {
                wp_send_json_error('Invalid image data', 400);
            }
            $decoded_image = base64_decode($encoded_image);
            if ($decoded_image === false) {
                wp_send_json_error('Decoding failed', 400);
            }
            // Save the image in the includes/image folder
            $upload_dir = ADSC_PATH . 'includes/images/';
            if (!file_exists($upload_dir)) {
                wp_mkdir_p($upload_dir);
            }
            $file_path = $upload_dir . uniqid() . '.png';
            file_put_contents($file_path, $decoded_image);

            // Here you would typically handle the saving of the data, e.g., to a database
            // For demonstration, we'll just return the received image data
            wp_send_json_success(array('received_image' => $signature_image));
        }
    }
}
