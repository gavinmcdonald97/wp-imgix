<?php

namespace WPImgix;

class Plugin_Settings_Page extends Singleton {

    protected $logoSVG;

    protected function __construct() {
        add_action( 'admin_menu', array( $this, 'createSettings' ) );
        add_action( 'admin_init', array( $this, 'setupSections' ) );
        add_action( 'admin_init', array( $this, 'setupFields' ) );
        $this->logoSVG = file_get_contents(WPIMGIX_PLUGIN_DIR . 'assets/img/imgix.svg');
    }

    public function createSettings() {
        $page_title = 'Imgix Settings';
        $menu_title = 'Imgix Settings';
        $capability = 'manage_options';
        $slug       = 'wp-imgix-settings';
        $callback   = array($this, 'settingsContent');
        $icon_url   = 'data:image/svg+xml;base64,' . base64_encode($this->logoSVG);
        $position   = 120;
        add_menu_page($page_title, $menu_title, $capability, $slug, $callback, $icon_url, $position);

    }

    public function settingsContent()
    {
        ?>
        <div id="wp-imgix-plugin-settings-page">

            <div class="section-wrapper">
                <div class="title-wrapper">
                    <div class="svg-wrapper">
                        <?php echo $this->logoSVG; ?>
                    </div>
                    <h2><?php echo esc_html( get_admin_page_title() ); ?></h2>
                </div>
            </div>

            <div class="section-wrapper">
                <form action="options.php" method="post">
                    <?php
                    settings_fields('wp-imgix-settings');
                    do_settings_sections('wp-imgix-settings');
                    submit_button('Save Settings');
                    ?>
                </form>
            </div>

        </div>
        <?php
    }

    public function setupSections() {
        add_settings_section( 'wp_imgix_general_settings', '', array(), 'wp-imgix-settings' );
    }

    public function setupFields() {

        $fields = array(
            array(
                'section' => 'wp_imgix_general_settings',
                'label' => 'Enable imgix',
                'id' => 'wp-imgix-enable-imgix',
                'type' => 'checkbox',
            ),
            array(
                'section' => 'wp_imgix_general_settings',
                'label' => 'Imgix domain',
                'id' => 'wp-imgix-domain',
                'type' => 'text',
            ),
            array(
                'section' => 'wp_imgix_general_settings',
                'label' => 'Sign key',
                'placeholder' => 'optional',
                'id' => 'wp-imgix-sign-key',
                'desc' => 'To produce signed URLs, you must enable secure URLs on your source and add your signature key here.',
                'type' => 'text',
            )
        );

        foreach ( $fields as $field ){
            add_settings_field( $field['id'], $field['label'], array( $this, 'fieldCallback' ), 'wp-imgix-settings', $field['section'], $field );
            register_setting( 'wp-imgix-settings', $field['id'] );
        }
    }

    public function fieldCallback( $field ) {

        $value = get_option( $field['id'] );
        $placeholder = '';

        if ( isset($field['placeholder']) ) {
            $placeholder = $field['placeholder'];
        }

        switch ( $field['type'] ) {

            case 'checkbox':
                printf('<input %s id="%s" name="%s" type="checkbox" value="1">',
                    $value === '1' ? 'checked' : '',
                    $field['id'],
                    $field['id']
                );
                break;

            case 'wysiwyg':
                wp_editor($value, $field['id']);
                break;

            default:
                printf( '<input name="%1$s" id="%1$s" type="%2$s" placeholder="%3$s" value="%4$s" />',
                    $field['id'],
                    $field['type'],
                    $placeholder,
                    esc_html($value)
                );

        }

        if ( isset($field['desc']) ) {
            printf( '<p class="description">%s </p>', $field['desc'] );
        }
    }

}