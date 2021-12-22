<?php /** @noinspection PhpRedundantMethodOverrideInspection */

declare(strict_types=1);

namespace DerCooleVonDem\WorldNavigator;

use DerCooleVonDem\WorldNavigator\commands\CommandUtil;
use DerCooleVonDem\WorldNavigator\listener\PacketListener;
use pocketmine\plugin\PluginBase;

class WorldNavigator extends PluginBase{

	private static WorldNavigator $instance;
	private static CommandUtil $commandUtil;

	public static function getInstance(): WorldNavigator
	{
		return self::$instance;
	}


	public static function getCommandUtil(): CommandUtil
	{
		return self::$commandUtil;
	}

	public function onLoad(): void
	{
		self::$instance = $this;
		self::$commandUtil = new CommandUtil();
	}

	public function onEnable(): void
	{
		$listeners = [
			new PacketListener()
		];
		foreach($listeners as $listener){
			$this->getServer()->getPluginManager()->registerEvents($listener, $this);
		}
		self::$commandUtil->registerAll($this);
	}

	// Method provided by VanillaX
	public function getFile(): string
	{
		return parent::getFile();
	}

}
