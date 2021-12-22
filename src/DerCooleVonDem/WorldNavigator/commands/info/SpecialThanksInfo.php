<?php

namespace DerCooleVonDem\WorldNavigator\commands\info;

use pocketmine\player\Player;

class SpecialThanksInfo extends Info{

	public static function getInfo(Player $player, int $flag): string{
		$result = self::LINE_SEPARATOR . "\n";
		$result .= "§7CLADevs: §aThanks for the great command argument api!\n";
		$result .= self::LINE_SEPARATOR;
		return $result;
	}
}