<?php

namespace DerCooleVonDem\WorldNavigator\commands;

use DerCooleVonDem\WorldNavigator\commands\utils\CommandArgs;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\permission\DefaultPermissions;
use pocketmine\permission\Permission;
use pocketmine\permission\PermissionManager;
use pocketmine\utils\TextFormat;

/**
 * Class NavigatorCommand - Provided by VanillaX
 * @package DerCooleVonDem\WorldNavigator\commands
 */
abstract class NavigatorCommand extends Command{

	protected ?CommandArgs $commandArg = null;

	public function canRegister(): bool{
		return true;
	}

	public function setPermission(?string $permission): void{
		if($permission !== null){
			$permManager = PermissionManager::getInstance();
			$opRoot = $permManager->getPermission(DefaultPermissions::ROOT_OPERATOR);

			foreach(explode(";", $permission) as $perm){
				if(PermissionManager::getInstance()->getPermission($perm) === null){
					$permManager->addPermission($perm = new Permission($perm));
					$opRoot->addChild($perm->getName(), true);
				}
			}
		}
	}

	public function getCommandArg(): ?CommandArgs{
		return $this->commandArg;
	}

	public function sendSyntaxError(CommandSender $sender, string $name, string $at, string $extra = "", array $args = []): void{
		$argsList = count($args) >= 1 ? " " . implode(" ", $args) : "";
		$sender->sendMessage(TextFormat::RED . "Syntax error: Unexpected \"$name\": at \"$at>>$extra<<$argsList\"");
	}

}