<?php
/**
 * Default bootstrap file
 * 
 * @author Alexander Sergeychik
 * @return \Zend\Mvc\Application
 */

$loader = require_once __DIR__.'/autoload.php';

$application = \Zend\Mvc\Application::init(require 'config/application.config.php');

return $application;