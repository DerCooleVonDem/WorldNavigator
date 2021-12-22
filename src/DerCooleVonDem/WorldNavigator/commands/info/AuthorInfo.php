<?php

namespace DerCooleVonDem\WorldNavigator\commands\info;

use pocketmine\player\Player;

class AuthorInfo extends Info{

	public static function getInfo(Player $player, int $flag): string{
		$result = self::LINE_SEPARATOR . "\n";
		$result .= "§7Author: §aDerCooleVonDem\n";
		$result .= "§7Contribution: §aCLADevs\n";
		$result .= self::LINE_SEPARATOR;
		return $result;
	}
}