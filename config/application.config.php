<?php
return array(
	'modules' => array(
		//'ZfcBase',
		//'ZfcUser',
		//'Application',
		//'Admin',
	),
	'module_listener_options' => array(
		'config_glob_paths'	=> array(
			'config/autoload/{,*.}{global,local}.php',
		),
		'module_paths' => array(
			'./modules',
			//'./vendor/zf-commons',
			//'./vendor/doctrine',
		),
	),
);
