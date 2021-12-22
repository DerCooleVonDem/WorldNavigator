<?php

namespace DerCooleVonDem\WorldNavigator\commands;

use DerCooleVonDem\WorldNavigator\WorldNavigator;

class CommandUtil {

	/** @var NavigatorCommand[] */
	private array $commands = [];

	public function registerAll(WorldNavigator $navigator) {
		self::callDirectory($navigator, "commands" . DIRECTORY_SEPARATOR . "defaults", function (string $namespace) use ($navigator): void{
			$class = new $namespace();
			$this->register($navigator, $class);
		});
	}

	// Method provided by VanillaX
	public function register(WorldNavigator $navigator, NavigatorCommand $command): void{
		$map = $navigator->getServer()->getCommandMap();
		if(($cmd = $map->getCommand($command->getName())) !== null){
			$map->unregister($cmd);
		}
		$map->register("VanillaX", $command);
		$this->commands[strtolower($command->getName())] = $command;
	}

	// Method provided by VanillaX
	/**
	 * @return NavigatorCommand[]
	 */
	public function getCommands(): array{
		return $this->commands;
	}

	// Method provided by VanillaX
	public function getCommand(string $command): ?NavigatorCommand{
		return $this->commands[strtolower($command)] ?? null;
	}

	// Method provided by VanillaX
	public static function callDirectory(WorldNavigator $navigator, string $directory, callable $callable): void{
		$main = explode("\\", $navigator->getDescription()->getMain());
		unset($main[array_key_last($main)]);
		$main = implode("/", $main);
		$directory = rtrim(str_replace(DIRECTORY_SEPARATOR, "/", $directory), "/");
		$dir = $navigator->getFile() . "src/$main/" . $directory;

		foreach(array_diff(scandir($dir), [".", ".."]) as $file){
			$path = $dir . "/$file";
			$extension = pathinfo($path)["extension"] ?? null;

			if($extension === null){
				self::callDirectory($navigator, $directory . "/" . $file, $callable);
			}elseif($extension === "php"){
				$namespaceDirectory = str_replace("/", "\\", $directory);
				$namespaceMain = str_replace("/", "\\", $main);
				$namespace = $namespaceMain . "\\$namespaceDirectory\\" . basename($file, ".php");
				$callable($namespace);
			}
		}
	}

}