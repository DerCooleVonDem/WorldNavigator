<?php

namespace DerCooleVonDem\WorldNavigator\commands\defaults;

use DerCooleVonDem\WorldNavigator\commands\NavigatorCommand;
use DerCooleVonDem\WorldNavigator\commands\utils\CommandArgs;
use DerCooleVonDem\WorldNavigator\commands\utils\CommandTargetSelector;
use pocketmine\block\VanillaBlocks;
use pocketmine\command\CommandSender;
use pocketmine\network\mcpe\protocol\AvailableCommandsPacket;
use pocketmine\network\mcpe\protocol\types\PlayerPermissions;
use pocketmine\player\Player;
use pocketmine\Server;
use pocketmine\world\Position;
use pocketmine\world\World;

class UpNavigatorCommand extends NavigatorCommand{

	public function __construct() {
		parent::__construct("up", "Fast-Travel given blocks up", "/up <blocks> [player] [check-obstruction]");
		$this->commandArg = new CommandArgs(CommandArgs::FLAG_NORMAL, PlayerPermissions::OPERATOR);
		$this->commandArg->addParameter(0, "blocks", AvailableCommandsPacket::ARG_TYPE_INT, false);
		$this->commandArg->addParameter(0, "player", AvailableCommandsPacket::ARG_TYPE_TARGET, true);
		$this->commandArg->addParameter(0, "platform", AvailableCommandsPacket::ARG_FLAG_ENUM, true, "boolean: true", ["true", "false"]);
		$this->commandArg->addParameter(0, "check-obstruction", AvailableCommandsPacket::ARG_FLAG_ENUM, true, "boolean: true", ["true", "false"]);
	}

	/**
	 * @param CommandSender $sender
	 * @param string $commandLabel
	 * @param array $args
	 */
	public function execute(CommandSender $sender, string $commandLabel, array $args)
	{
		if (!$sender instanceof Player) {
			$sender->sendMessage("§cUse this command in-game to fast-travel given blocks up.");
			return;
		}

		if(!isset($args[0])){
			$this->sendSyntaxError($sender, "empty-amount", "/up ");
			return;
		}

		$blocks = (int)$args[0];

		$players = CommandTargetSelector::getFromString($sender, $args[1] ?? $sender->getName(), false, true, true);

		foreach ($players as $player) {

			//I hate this code
			if(isset($args[2])) {
				$platform = $args[2] == "true";
			}else{
				$platform = true;
			}

			if(isset($args[3])) {
				$checkObstruction = $args[3] == "true";
			}else{
				$checkObstruction = true;
			}
			// Until here

			$travelBlock = $player->getPosition()->y + $blocks;

			//check if out of bounds
			if($travelBlock > World::Y_MAX || $travelBlock < World::Y_MIN ){
				$this->sendSyntaxError($sender, "out-of-bounds", "/up ", $blocks, [$player->getName(), (string)$checkObstruction]);
				return;
			}

			$obstructionValue = false;

			//check if there is an obstruction
			if($checkObstruction){
				$world = $player->getWorld();
				$obstruction = $world->getBlockAt($player->getPosition()->x, $travelBlock, $player->getPosition()->z);
				$obstructionUp = $world->getBlockAt($player->getPosition()->x, $travelBlock + 1, $player->getPosition()->z);

				if($obstruction->getId() !== 0 || $obstructionUp->getId() !== 0){
					if($player === $sender){
						$sender->sendMessage("§cThere is an obstruction at your destination.");
					}else{
						$sender->sendMessage("§cThere is an obstruction at {$player->getName()}'s destination.");
					}
					$obstructionValue = true;
				}else{
					$player->teleport(new Position($player->getPosition()->floor()->x + 0.5, $travelBlock, $player->getPosition()->floor()->z + 0.5, $player->getWorld()));
					if($platform){
						$world->setBlockAt($player->getPosition()->floor()->x, $travelBlock - 1, $player->getPosition()->floor()->z, VanillaBlocks::GLASS());
					}
				}
			}else{
				$player->teleport(new Position($player->getPosition()->floor()->x + 0.5, $travelBlock, $player->getPosition()->floor()->z + 0.5, $player->getWorld()));
				if($platform){
					$player->getWorld()->setBlockAt($player->getPosition()->floor()->x, $travelBlock - 1, $player->getPosition()->floor()->z, VanillaBlocks::GLASS());
				}
			}

			if($player !== $sender){
				$sender->sendMessage("§aYou have fast-traveled {$player->getName()} up {$blocks} blocks.");
			}

			if(!$obstructionValue){
				$player->sendMessage("§aYou have fast-traveled up {$blocks} blocks.");
			}
		}
	}
}