<?php

declare(strict_types=1);

namespace ySimmer\Size;

use pocketmine\plugin\PluginBase;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;
use pocketmine\utils\TextFormat;
use pocketmine\Server;
use pocketmine\network\mcpe\protocol\PlaySoundPacket;
use pocketmine\math\Vector3;
use pocketmine\utils\Config;

class Main extends PluginBase {

    private Config $config;

    public function onEnable(): void {
        $this->saveDefaultConfig();
        $this->config = $this->getConfig();
        $this->getLogger()->info($this->getMessage("plugin_enabled"));
    }

    public function onDisable(): void {
        $this->getLogger()->info($this->getMessage("plugin_disabled"));
    }

    public function onCommand(CommandSender $sender, Command $command, string $label, array $args): bool {
        if ($command->getName() === "size") {
            return $this->handleSizeCommand($sender, $args);
        }
        return false;
    }

    private function handleSizeCommand(CommandSender $sender, array $args): bool {
        if (empty($args)) {
            $this->sendHelp($sender);
            return true;
        }

        $subCommand = strtolower($args[0]);

        switch ($subCommand) {
            case "set":
                return $this->handleSetCommand($sender, $args);
            case "reset":
                return $this->handleResetCommand($sender, $args);
            case "credits":
                return $this->handleCreditsCommand($sender);
            case "help":
                $this->sendHelp($sender);
                return true;
            default:
                $sender->sendMessage($this->getMessage("error_invalid_subcommand"));
                return true;
        }
    }

    private function handleSetCommand(CommandSender $sender, array $args): bool {
        if (!$sender instanceof Player) {
            $sender->sendMessage($this->getMessage("error_players_only"));
            return true;
        }

        if (count($args) < 2) {
            $sender->sendMessage($this->getMessage("error_usage_set"));
            return true;
        }

        $size = (float) $args[1];
        
        if ($size < 0.1 || $size > 10.0) {
            $sender->sendMessage($this->getMessage("error_invalid_size"));
            return true;
        }

        if (isset($args[2])) {
            if (!$sender->hasPermission("size.others")) {
                $sender->sendMessage($this->getMessage("error_no_permission_others"));
                return true;
            }

            $targetName = $args[2];
            $target = Server::getInstance()->getPlayerByPrefix($targetName);
            
            if ($target === null) {
                $sender->sendMessage(str_replace("{player}", $targetName, $this->getMessage("error_player_not_found")));
                return true;
            }

            $this->setPlayerSize($target, $size);
            
            $senderMessage = str_replace(["{player}", "{size}"], [$target->getName(), (string)$size], 
                $this->getMessage("size_changed_other_sender"));
            $sender->sendActionBarMessage($senderMessage);
            $this->playSound($sender);
            
            $targetMessage = str_replace("{size}", (string)$size, $this->getMessage("size_changed_other_target"));
            $target->sendActionBarMessage($targetMessage);
            $this->playSound($target);
        } else {
            $this->setPlayerSize($sender, $size);
            $message = str_replace("{size}", (string)$size, $this->getMessage("size_changed_self"));
            $sender->sendActionBarMessage($message);
            $this->playSound($sender);
        }

        return true;
    }

    private function handleResetCommand(CommandSender $sender, array $args): bool {
        if (!$sender instanceof Player) {
            $sender->sendMessage($this->getMessage("error_players_only"));
            return true;
        }

        if (isset($args[1])) {
            if (!$sender->hasPermission("size.others")) {
                $sender->sendMessage($this->getMessage("error_no_permission_reset_others"));
                return true;
            }

            $targetName = $args[1];
            $target = Server::getInstance()->getPlayerByPrefix($targetName);
            
            if ($target === null) {
                $sender->sendMessage(str_replace("{player}", $targetName, $this->getMessage("error_player_not_found")));
                return true;
            }

            $this->setPlayerSize($target, 1.0);
            
            $senderMessage = str_replace("{player}", $target->getName(), $this->getMessage("size_reset_other_sender"));
            $sender->sendActionBarMessage($senderMessage);
            $this->playSound($sender);
            
            $target->sendActionBarMessage($this->getMessage("size_reset_other_target"));
            $this->playSound($target);
        } else {
            $this->setPlayerSize($sender, 1.0);
            $sender->sendActionBarMessage($this->getMessage("size_reset_self"));
            $this->playSound($sender);
        }

        return true;
    }

    private function handleCreditsCommand(CommandSender $sender): bool {
        $sender->sendMessage(TextFormat::GOLD . "=== Size Plugin ===");
        $sender->sendMessage(TextFormat::YELLOW . "Desarrollado por: " . TextFormat::AQUA . "ySimmer");
        $sender->sendMessage(TextFormat::YELLOW . "Versión: " . TextFormat::WHITE . $this->getDescription()->getVersion());
        $sender->sendMessage(TextFormat::GRAY . "Plugin para cambiar el tamaño de los jugadores");
        return true;
    }

    private function sendHelp(CommandSender $sender): void {
        $sender->sendMessage(TextFormat::GOLD . "=== Comandos de Size ===");
        $sender->sendMessage(TextFormat::YELLOW . "/size set <0.1-10>" . TextFormat::WHITE . " - Cambia tu tamaño");
        $sender->sendMessage(TextFormat::YELLOW . "/size set <0.1-10> <jugador>" . TextFormat::WHITE . " - Cambia el tamaño de otro jugador");
        $sender->sendMessage(TextFormat::YELLOW . "/size reset" . TextFormat::WHITE . " - Resetea tu tamaño");
        $sender->sendMessage(TextFormat::YELLOW . "/size reset <jugador>" . TextFormat::WHITE . " - Resetea el tamaño de otro jugador");
        $sender->sendMessage(TextFormat::YELLOW . "/size credits" . TextFormat::WHITE . " - Muestra los créditos");
        $sender->sendMessage(TextFormat::YELLOW . "/size help" . TextFormat::WHITE . " - Muestra esta ayuda");
    }

    private function setPlayerSize(Player $player, float $size): void {
        $player->setScale($size);
    }

    private function playSound(Player $player): void {
        if (!$this->config->get("sounds")["enabled"]) {
            return;
        }
        
        $packet = new PlaySoundPacket();
        $packet->soundName = $this->config->get("sounds")["sound_name"];
        $packet->x = $player->getPosition()->getX();
        $packet->y = $player->getPosition()->getY();
        $packet->z = $player->getPosition()->getZ();
        $packet->volume = (float) $this->config->get("sounds")["volume"];
        $packet->pitch = (float) $this->config->get("sounds")["pitch"];
        
        $player->getNetworkSession()->sendDataPacket($packet);
    }

    private function getMessage(string $key): string {
        $messages = $this->config->get("messages");
        return $messages[$key] ?? "§cMensaje no encontrado: " . $key;
    }

}
