<?php

namespace DerCooleVonDem\WorldNavigator\commands\info;

use DerCooleVonDem\WorldNavigator\commands\NavigatorCommand;
use DerCooleVonDem\WorldNavigator\WorldNavigator;
use pocketmine\command\Command;
use pocketmine\player\Player;

class HelpInfo extends Info{

	public static function getInfo(Player $player, int $flag): string
	{
		$flag--;
		$string = self::LINE_SEPARATOR . "\n";
		$commands = WorldNavigator::getInstance()->getServer()->getCommandMap()->getCommands();
		//filter all commands out that are not an instanceof NavigatorCommand
		$commands = array_filter($commands, function(Command $command){
			return $command instanceof NavigatorCommand;
		});
		//split the array into chunks of 5 sort alphabetically
		$commands = array_chunk($commands, 5, true);
		if(isset($commands[$flag])){
			foreach($commands[$flag] as $command){
				if($command instanceof NavigatorCommand){
					$string .= "§a/" . $command->getName() . " §r-> " . $command->getDescription() . "\n";
				}
			}
		}else{
			$string .= "§cNo commands found on this page.\n";
		}
		$string .= self::LINE_SEPARATOR;
		return $string;
	}

}