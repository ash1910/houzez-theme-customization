<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class Houzez_Dev_Menu {

    public $slug = 'houzez-real-estate';
    public $capability = 'edit_posts';
    public static $instance;

    public function __construct() {

        add_filter( 'houzez_admin_realestate_menu', array( $this, 'add_developer_menu' ) );
    }

    public static function instance() {
        if ( is_null( self::$instance ) ) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    function add_developer_menu( $submenus ) {
        $submenus['houzez_developers'] = array(
            $this->slug,
            esc_html__( 'Developers', 'houzez-theme-developer' ),
            esc_html__( 'Developers', 'houzez-theme-developer' ),
            $this->capability,
            'edit.php?post_type=houzez_developer',
        );

        return $submenus;
    }

}