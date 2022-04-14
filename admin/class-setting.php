<?php

namespace WPImgix;

class Setting
{
    public $value = '';

    public $args = [
        'id' => '',
        'page' => '',
        'type' => 'string',
        'sanitize_callback' => 'sanitize_text_field',
        'default' => '',
        'label' => '',
        'section' => '',
        'placeholder' => ''
    ];

    public function __construct($args)
    {
        $this->args = array_merge($this->args, $args);
        $this->value = get_option($this->args['id']);

        register_setting(
            $this->args['page'],
            $this->args['id'],
            array(
                'type' => $this->args['type'],
                'sanitize_callback' => $this->args['sanitize_callback'],
                'default' => $this->args['default'],
                'description' => __($this->args['description'], 'wp-imgix')
            )
        );

        add_settings_field(
            $this->args['id'],
            __($this->args['label'], 'wp-imgix'),
            array($this, 'callback'),
            $this->args['page'],
            $this->args['section']
        );

    }

    public function callback()
    {
        switch ($this->args['type'])
        {
            case 'string':
                ?>
                <input
                    type="text"
                    class="regular-text"
                    name="<?php echo $this->args['id']; ?>"
                    value="<?php echo isset($this->value) ? esc_html($this->value) : ''; ?>"
                    placeholder="<?php echo $this->args['placeholder']; ?>"
                />
                <p class="regular-text"><small><?php echo __($this->args['description'], 'wp-imgix'); ?></small></p>
                <?php
                break;
            default:
                break;
        }
    }
}