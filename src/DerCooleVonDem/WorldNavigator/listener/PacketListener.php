<?php

namespace DerCooleVonDem\WorldNavigator\listener;

use DerCooleVonDem\WorldNavigator\commands\NavigatorCommand;
use DerCooleVonDem\WorldNavigator\WorldNavigator;
use pocketmine\event\Listener;
use pocketmine\event\server\DataPacketSendEvent;
use pocketmine\network\mcpe\protocol\AvailableCommandsPacket;

class PacketListener implements Listener{

	// Method provided by VanillaX
	public function onDataPacketSend(DataPacketSendEvent $event): void{
		if(!$event->isCancelled()){
			foreach($event->getPackets() as $packet){
				if($packet instanceof AvailableCommandsPacket) {
					$this->handleCommandEnum($packet);
					break;
				}
			}
		}
	}

	// Method provided by VanillaX
	private function handleCommandEnum(AvailableCommandsPacket $packet): void{
		foreach(WorldNavigator::getInstance()->getServer()->getCommandMap()->getCommands() as $key => $command){

			if(!($command instanceof NavigatorCommand)) continue;

			if(($arg = $command->getCommandArg()) !== null && ($command = $packet->commandData[strtolower($key)] ?? null) !== null){
				$command->flags = $arg->getFlags();
				$command->permission = $arg->getPermission();
				$command->overloads = $arg->getOverload();
			}
		}
	}

}