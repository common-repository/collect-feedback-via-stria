<?php
/*
* Plugin Name: Collect Feedback via Stria
* Description: A lightweight, Artificial Intelligence powered user feedback collection and analysis tool for websites by <a href="https://stria.co">Stria.co</a>.
* Author:       Stria Team
* Author URI:  https://stria.co
* License:      GPL2
* License URI:  https://www.gnu.org/licenses/gpl-2.0.html
* Version: 1.0.0
* License: GPL2
*/
class InsertFeedbackTool {
    /**
     * Constructor
     */
    public function __construct() {
        // Plugin Details
        $this->plugin               = new stdClass;
        $this->plugin->name         = 'collect-feedback-via-stria'; // Plugin Folder
        $this->plugin->displayName  = 'Collect Feedback via Stria'; // Plugin Name
        $this->plugin->version      = '1.0.0';
        $this->plugin->basename     = plugin_basename(__FILE__);
        // Hooks
        add_action( 'admin_init', array( &$this, 'registerSettings' ) );
        add_action( 'admin_menu', array( &$this, 'registerAdminPanels' ) );
        // Frontend Hooks
        add_action( 'wp_footer', array( &$this, 'addFooter' ));


        add_filter("plugin_action_links_".$this->plugin->basename,array(&$this,'your_plugin_settings_link'));
    }

    // Add settings link on plugin page
    function your_plugin_settings_link($links) {
        $settings_link = '<a href="options-general.php?page='.$this->plugin->name.'">Settings</a>';
        array_unshift($links, $settings_link);
        return $links;
    }






    /**
     * Register Settings
     */
    function registerSettings() {
        register_setting( $this->plugin->name, 'stria-token', 'trim' );
    }

    /**
     * Output the Administration Panel
     * Save POSTed data from the Administration Panel into a WordPress option
     */
    function adminPanel() {
        // only admin user can access this page
        if (!current_user_can('administrator')) {
            echo '<p>' . __('Sorry, you are not allowed to access this page.', $this->plugin->name) . '</p>';
            return;
        }
        // Save Settings
        if ( isset( $_REQUEST['submit'] ) ) {
            // Check nonce
            if ( !isset( $_REQUEST[$this->plugin->name.'_nonce'] ) ) {
                // Missing nonce
                $this->errorMessage = __( 'nonce field is missing. Settings NOT saved.', $this->plugin->name );
            } elseif ( !wp_verify_nonce( $_REQUEST[$this->plugin->name.'_nonce'], $this->plugin->name ) ) {
                // Invalid nonce
                $this->errorMessage = __( 'Invalid nonce specified. Settings NOT saved.', $this->plugin->name );
            } else {
                // Save
                // $_REQUEST has already been slashed by wp_magic_quotes in wp-settings
                // so do nothing before saving
                update_option( 'stria-token', $_REQUEST['stria-token'] );
                $this->message = __( ' Congrats! You can start to collect feedback now. If you didn\'t create a feedback form before, please visit <a href="https://dashboard.stria.co">Stria Dashboard</a>. For any help <a href="https://stria.co/contact">contact</a> us.', $this->plugin->name );
            }
        }

        // Get latest settings
        $this->settings = array(
            'stria-token' => esc_html( wp_unslash( get_option( 'stria-token' ) ) )
        );

        // Load Settings Form
        include_once( WP_PLUGIN_DIR . '/' . $this->plugin->name . '/views/settings.php' );
    }

    /**
     * Register the plugin settings panel
     */
    function registerAdminPanels() {
        add_submenu_page( 'options-general.php', $this->plugin->displayName, $this->plugin->displayName, 'manage_options', $this->plugin->name, array( &$this, 'adminPanel' ) );
    }

    /**
     * Outputs script / CSS to the frontend header
     */
    function addFooter() {
            ?>
            <?php
            $this->output( 'stria-token' );
            ?>
            <?php
    }

    function output( $setting ) {
        // Ignore admin, feed, robots or trackbacks
        if ( is_admin() || is_feed() || is_robots() || is_trackback() ) {
            return;
        }

        // provide the opportunity to Ignore Stria Plugin
        if ( apply_filters( 'disable_stria', false ) ) {
            return;
        }

        // Get meta
        $meta = get_option( $setting );
        if ( empty( $meta ) ) {
            return;
        }
        if ( trim( $meta ) == '' ) {
            return;
        }

        // Output
        echo wp_unslash( $meta );
    }
}

$striaFeedbackTool = new InsertFeedbackTool();
?>