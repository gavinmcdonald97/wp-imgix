<?php

namespace WPImgix;

class Plugin_Settings_Page extends Singleton
{
    protected $logoSVG;

    protected function __construct()
    {
        add_action('admin_menu', array($this, 'addToMenu'));
        add_action('admin_init', array($this, 'settings'));
        $this->logoSVG = file_get_contents(WPIMGIX_PLUGIN_DIR . 'assets/img/imgix.svg');
    }

    public function addToMenu()
    {
        add_menu_page(
            __('Imgix Settings', 'wp-imgix'),
            __('Imgix Settings', 'wp-imgix'),
            'manage_options',
            'wp-imgix-settings-page',
            array($this, 'settingsPage'),
            'data:image/svg+xml;base64,' . base64_encode($this->logoSVG),
            120
        );
    }

    public function settings()
    {
        add_settings_section(
            'wp_imgix_plugin_general_settings',
            'General Settings',
            '',
            'wp-imgix-settings-page'
        );

        new Setting([
            'id' => 'wp_imgix_domain',
            'page' => 'wp-imgix-settings-page',
            'section' => 'wp_imgix_plugin_general_settings',
            'label' => 'Imgix domain',
            'placeholder' => 'demo.imgix.net'
        ]);

        new Setting([
            'id' => 'wp_imgix_sign_key',
            'page' => 'wp-imgix-settings-page',
            'section' => 'wp_imgix_plugin_general_settings',
            'label' => 'Sign key',
            'placeholder' => 'optional',
            'description' => 'To produce signed URLs, you must enable secure URLs on your source and add your signature key here.'
        ]);
    }

    public function settingsPage()
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
                        settings_fields('wp-imgix-settings-page');
                        do_settings_sections('wp-imgix-settings-page');
                        submit_button('Save Settings');
                        ?>
                    </form>
                </div>

            </div>
        <?php
    }
}