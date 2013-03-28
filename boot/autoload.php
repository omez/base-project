<?php
/**
 * Initialization
 * 
 * @author Alexander Sergeychik
 * @return \Composer\Autoload\ClassLoader
 */

chdir(dirname(__DIR__));

// Composer autoloading
if (file_exists('vendor/autoload.php')) {
	$loader = include 'vendor/autoload.php';
}

$zf2Path = false;

if (getenv('ZF2_PATH')) {		   // Support for ZF2_PATH environment variable or git submodule
	$zf2Path = getenv('ZF2_PATH');
} elseif (get_cfg_var('zf2_path')) { // Support for zf2_path directive value
	$zf2Path = get_cfg_var('zf2_path');
} elseif (is_dir('vendor/ZF2/library')) {
	$zf2Path = 'vendor/ZF2/library';
}

if ($zf2Path) {
	if (isset($loader)) {
		$loader->add('Zend', $zf2Path);
	} else {
		include $zf2Path . '/Zend/Loader/AutoloaderFactory.php';
		Zend\Loader\AutoloaderFactory::factory(array(
			'Zend\Loader\StandardAutoloader' => array(
				'autoregister_zf' => true
			)
		));
	}
}

if (!class_exists('Zend\Loader\AutoloaderFactory')) {
	throw new RuntimeException('Unable to load ZF2. Run `php composer.phar install` or define a ZF2_PATH environment variable.');
}


// Register patches autoloading
$patchNamespaces = file_exists('patches/namespaces.php') ? require('patches/namespaces.php') : array();
$patchClassmap = file_exists('patches/classmap.php') ? require('patches/classmap.php') : array();

if (class_exists('Composer\Autoload\ClassLoader')) {
	$patchLoader = new \Composer\Autoload\ClassLoader();
	if (!empty($patchNamespaces)) {
		foreach ($patchNamespaces as $namespace=>$path) {
			$patchLoader->add($namespace, $path);
		}
	}		
	if (!empty($patchClassmap)) {
		$patchLoader->addClassMap($patchClassmap);
	}
	$patchLoader->register(true);
} elseif (class_exists('Zend\Loader\StandardAutoloader') && class_exists('Zend\Loader\ClassMapAutoloader')) {
	// FIXME - should be prepended, find how to do this with standard autoloader
    $patchLoaderPSR0 = new \Zend\Loader\StandardAutoloader();
    $patchLoaderPSR0->registerNamespaces($patchNamespaces);
    $patchLoaderClassmap = new \Zend\Loader\ClassMapAutoloader();
    $patchLoaderClassmap->registerAutoloadMap($patchClassmap);
    $patchLoaderPSR0->register();
    $patchLoaderClassmap->register();
}



return $loader;