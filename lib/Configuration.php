<?php

/**
 * Generic Configuration Class
 * 
 * @author Timo Witte <timo.witte@googlemail.com>
 * @copyright 2011 Timo Witte
 * @licence apache
 * 
 * @version 1.0 
 */
class Configuration {
	private $app;
	private $configs;

	public static function get(Application $app) {
		$conf = new Configuration();
		$conf->setApp($app);
		return $conf;
	}

	public function setApp(Application $app) {
		$this->app = $app;
	}

	public function getValue($path) {
		$path = explode("/",$path, 2);
		$config = $this->load($path[0]);
		$path = explode("/", $path[1]);
		foreach($path as $part) {
			if(empty($config[$part]))
				return false;
			$config = $config[$part];
		}
		return $config;
	}

	public function load($name) {
		// if in cache just return the data
		if(isset($this->configs[$name]))
			return $this->configs[$name];

		$file = dirname(dirname(__FILE__))."/configs/".$name.".php";
		if(file_exists($file)) {
			include $file;
			if(!isset($cfg[$name]))
				throw new Exception(sprintf(_('Arraykey $cfg[%s] is missing!'), $name));
			else
				return $this->configs[$name] = $cfg[$name];
		}
		else
			throw new Exception(sprintf(_("Configuration File %s.php not found in configs folder!"), $name));
	}
}