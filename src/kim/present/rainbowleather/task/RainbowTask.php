<?php

/*
 *
 *  ____                           _   _  ___
 * |  _ \ _ __ ___  ___  ___ _ __ | |_| |/ (_)_ __ ___
 * | |_) | '__/ _ \/ __|/ _ \ '_ \| __| ' /| | '_ ` _ \
 * |  __/| | |  __/\__ \  __/ | | | |_| . \| | | | | | |
 * |_|   |_|  \___||___/\___|_| |_|\__|_|\_\_|_| |_| |_|
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * @author  PresentKim (debe3721@gmail.com)
 * @link    https://github.com/PresentKim
 * @license https://www.gnu.org/licenses/agpl-3.0.html AGPL-3.0.0
 *
 *   (\ /)
 *  ( . .) ♥
 *  c(")(")
 */

declare(strict_types=1);

namespace kim\present\rainbowleather\task;

use pocketmine\item\{
	Armor, Item
};
use pocketmine\scheduler\Task;
use pocketmine\Server;
use pocketmine\utils\Color;

class RainbowTask extends Task{
	/** @var float hue */
	private $h = 0.0;

	/**
	 * Actions to execute when run
	 *
	 * @param int $currentTick
	 *
	 * @return void
	 */
	public function onRun(int $currentTick){
		$this->hsl2rgb($this->h, 100.0, 50.0, $r, $g, $b);
		$this->h = ($this->h + 1) % 360;
		foreach(Server::getInstance()->getOnlinePlayers() as $key => $player){
			foreach($player->getArmorInventory()->getContents(true) as $slot => $item){
				if(in_array($item->getId(), [Item::LEATHER_CAP, Item::LEATHER_CHESTPLATE, Item::LEATHER_LEGGINGS, Item::LEATHER_BOOTS])){
					/** @var $item Armor */
					$item->setCustomColor(new Color($r, $g, $b));
				}
			}
		}
	}

	/**
	 * @param float    $h
	 * @param float    $s
	 * @param float    $l
	 * @param int|null $r
	 * @param int|null $g
	 * @param int|null $b
	 *
	 * @deprecated The test is not complete. I will fix it later.
	 */
	public function hsl2rgb(float $h, float $s, float $l, ?int &$r, ?int &$g, ?int &$b) : void{
		if($s == 0){
			$r = $g = $b = $l * 255;
		}else{
			$temp2 = $l < 0.5 ? $l * (1 + $s) : $l + $s - $l * $s;
			$temp1 = 2 * $l - $temp2;

			$h /= 360;
			$rgb = [($h + 1 / 3) % 1, $h, ($h + 2 / 3) % 1];
			for($i = 0; $i < 3; ++$i){
				$rgb[$i] = $rgb[$i] < 1 / 6 ? $temp1 + ($temp2 - $temp1) * 6 * $rgb[$i] : $rgb[$i] < 1 / 2 ? $temp2 : $rgb[$i] < 2 / 3 ? $temp1 + ($temp2 - $temp1) * 6 * (2 / 3 - $rgb[$i]) : $temp1;
			}

			$r = (int) $rgb[0];
			$g = (int) $rgb[1];
			$b = (int) $rgb[2];
		}
	}
}