<?php

require __DIR__ . '/../vendor/autoload.php';

$configurator = new Nette\Configurator;

//$configurator->setDebugMode(TRUE);  // debug mode MUST NOT be enabled on production server
$configurator->enableDebugger(__DIR__ . '/../log');

$configurator->setTempDirectory(__DIR__ . '/../temp');

$configurator->createRobotLoader()
	->addDirectory(__DIR__)
	->addDirectory(__DIR__ . '/../vendor/others')
	->register();

$configurator->addConfig(__DIR__ . '/config/config.neon');
$configurator->addConfig(__DIR__ . '/config/config.local.neon');

$container = $configurator->createContainer();
\Nette\Forms\Container::extensionMethod("addTagBox", 
		function(\Nette\Forms\Container $container, $name, $label = NULL, $items = array(), $availableTags = array()){
			$container[$name] = new \TagBox($label);
			$container[$name]->setValue($items);
			$container[$name]->setAvailableTags($availableTags);
			return $container[$name];
		});
\Nette\Forms\Container::extensionMethod("addColorSelect", 
		function(\Nette\Forms\Container $container, $name, $label = NULL, $items = array()){
			$container[$name] = new \ColorSelectBox($label, $items);
			return $container[$name];
		});
return $container;
