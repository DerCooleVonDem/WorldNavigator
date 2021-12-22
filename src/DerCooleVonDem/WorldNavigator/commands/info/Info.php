<?php

namespace DerCooleVonDem\WorldNavigator\commands\info;

use pocketmine\player\Player;

/**
 * Class Info - Used to design the info command
 * @package DerCooleVonDem\WorldNavigator\commands\info
 */
abstract class Info{

	public const NULL_FLAG = -1;

	protected const LINE_SEPARATOR = "§6>-------------§a[WorldNavigator]§6-------------<";

	public abstract static function getInfo(Player $player, int $flag): string;

}