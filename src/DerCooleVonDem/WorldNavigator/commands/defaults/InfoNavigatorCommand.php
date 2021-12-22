<?php

namespace DerCooleVonDem\WorldNavigator\commands\defaults;

use DerCooleVonDem\WorldNavigator\commands\info\AuthorInfo;
use DerCooleVonDem\WorldNavigator\commands\info\HelpInfo;
use DerCooleVonDem\WorldNavigator\commands\info\Info;
use DerCooleVonDem\WorldNavigator\commands\info\SpecialThanksInfo;
use DerCooleVonDem\WorldNavigator\commands\info\VersionInfo;
use DerCooleVonDem\WorldNavigator\commands\NavigatorCommand;
use DerCooleVonDem\WorldNavigator\commands\utils\CommandArgs;
use pocketmine\command\CommandSender;
use pocketmine\network\mcpe\protocol\AvailableCommandsPacket;
use pocketmine\network\mcpe\protocol\types\PlayerPermissions;
use pocketmine\player\Player;

class InfoNavigatorCommand extends NavigatorCommand{

	private array $types = ["author", "version", "special-thanks", "commands"];

	public function __construct()
	{
		parent::__construct("nav-info", "Displays the help for the world-navigator plugin.", "/info");
		$this->commandArg = new CommandArgs(CommandArgs::FLAG_NORMAL, PlayerPermissions::MEMBER);
		$this->commandArg->addParameter(0, "info", AvailableCommandsPacket::ARG_FLAG_ENUM, false, "info: type", $this->types);
	}

	/**
	 * @param CommandSender $sender
	 * @param string $commandLabel
	 * @param array $args
	 */
	public function execute(CommandSender $sender, string $commandLabel, array $args)
	{
		if (!$sender instanceof Player) {
			$sender->sendMessage("§cUse this command in-game to gather information about the world-navigator plugin.");
			return;
		}

		if (!isset($args[0])) {
			$this->sendSyntaxError($sender, "empty-type", "/nav-info ");
			return;
		}

		if (!in_array($args[0], $this->types)) {
			$this->sendSyntaxError($sender, "unknown-type", "/nav-info ", $args[0]);
			return;
		}

		$type = $args[0];

		switch ($type) {
			case "author":
				$info = AuthorInfo::getInfo($sender, Info::NULL_FLAG);
				$sender->sendMessage($info);
				break;
			case "version":
				$info = VersionInfo::getInfo($sender, Info::NULL_FLAG);
				$sender->sendMessage($info);
				break;
			case "special-thanks":
				$info = SpecialThanksInfo::getInfo($sender, Info::NULL_FLAG);
				$sender->sendMessage($info);
				break;
			case "commands":
				$page = $args[1] ?? 1;
				$info = HelpInfo::getInfo($sender, $page);
				$sender->sendMessage($info);
				break;
		}
	}
}