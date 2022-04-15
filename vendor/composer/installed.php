<?php return array(
    'root' => array(
        'pretty_version' => '1.0.0+no-version-set',
        'version' => '1.0.0.0',
        'type' => 'wordpress-plugin',
        'install_path' => __DIR__ . '/../../',
        'aliases' => array(),
        'reference' => NULL,
        'name' => 'gavinmcdonald/wp-imgix',
        'dev' => true,
    ),
    'versions' => array(
        'gavinmcdonald/wp-imgix' => array(
            'pretty_version' => '1.0.0+no-version-set',
            'version' => '1.0.0.0',
            'type' => 'wordpress-plugin',
            'install_path' => __DIR__ . '/../../',
            'aliases' => array(),
            'reference' => NULL,
            'dev_requirement' => false,
        ),
        'imgix/imgix-php' => array(
            'pretty_version' => '3.3.1',
            'version' => '3.3.1.0',
            'type' => 'library',
            'install_path' => __DIR__ . '/../imgix/imgix-php',
            'aliases' => array(),
            'reference' => 'ddb7e427b601bc3534f108180c81292d5b29bc39',
            'dev_requirement' => false,
        ),
    ),
);
