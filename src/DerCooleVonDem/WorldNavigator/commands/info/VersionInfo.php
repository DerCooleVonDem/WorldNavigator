<?php

namespace DerCooleVonDem\WorldNavigator\commands\info;

use DerCooleVonDem\WorldNavigator\WorldNavigator;
use pocketmine\player\Player;

class VersionInfo extends Info{

	public static function getInfo(Player $player, int $flag): string{
		$result = self::LINE_SEPARATOR . "\n";
		$version = WorldNavigator::getInstance()->getDescription()->getVersion();
		$description = WorldNavigator::getInstance()->getDescription()->getDescription();
		$result .= "§7Version: §a$version\n";
		$result .= "§7Description: \n§a$description\n";
		$result .= self::LINE_SEPARATOR;
		return $result;
	}
}