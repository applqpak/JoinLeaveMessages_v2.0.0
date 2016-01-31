<?php

  namespace JoinLeaveMessages;
  
  use pocketmine\plugin\PluginBase;
  use pocketmine\event\Listener;
  use pocketmine\utils\TextFormat as TF;
  use pocketmine\event\player\PlayerJoinEvent;
  use pocketmine\event\player\PlayerQuitEvent;
  use pocketmine\command\Command;
  use pocketmine\command\CommandSender;
  
  class Main extends PluginBase implements Listener {
  
    public function onEnable() {
    
      $this->getServer()->getPluginManager()->registerEvents($this, $this);
      
      if(!(file_exists($this->getDataFolder()))) {
      
        @mkdir($this->getDataFolder());
        
        chdir($this->getDataFolder());
        
        touch("config.yml");
        
        $array = [
        
          "join-message:" => "{player} joined the server!",
          "quit-message:" => "{player} left the server!"
          
        ];
        
        file_put_contents("config.yml", json_encode($array));
        
      }
      
    }
    
    public function onJoin(PlayerJoinEvent $event) {
    
      chdir($this->getDataFolder());
      
      $data = file_get_contents("config.yml");
      
      $file_array = json_decode($data, true);
      
      $join_message = $file_array["join-message:"];
      
      $event->setJoinMessage(str_replace("{player}", $event->getPlayer()->getDisplayName(), $join_message));
      
    }
    
    public function onQuit(PlayerQuitEvent $event) {
    
      chdir($this->getDataFolder());
      
      $data = file_get_contents("config.yml");
      
      $file_array = json_decode($data, true);
      
      $quit_message = $file_array["quit-message:"];
      
      $event->setQuitMessage(str_replace("{player}", $event->getPlayer()->getDisplayName(), $quit_message));
      
    }

    public function onCommand(CommandSender $sender, Command $cmd, $label, array $args) {

      if(strtolower($cmd->getName()) === "setjoinmessage") {

        if(!(isset($args[0]))) {

          $sender->sendMessage(TF::RED . "Error: not enough args. Usage: /setjoinmessage < message >");

          return true;

        } else {

          chdir($this->getDataFolder());

          $data = file_get_contents("config.yml");

          $file_array = json_decode($data, true);

          $old_join_message = $file_array["join-message:"];

          $new_join_message = implode(" ", $args);

          $update_message = str_replace($old_join_message, $new_join_message, file_get_contents("config.yml"));

          file_put_contents("config.yml", $update_message);

          $sender->sendMessage(TF::GREEN . "Join Message Was Successfully Updated!");

          return true;

        }

      }

      if(strtolower($cmd->getName()) === "setleavemessage") {

        if(!(isset($args[0]))) {

          $sender->sendMessage(TF::RED . "Error: not enough args. Usage: /setleavemessage < message >");

          return true;

        } else {

          chdir($this->getDataFolder());

          $data = file_get_contents("config.yml");

          $file_array = json_decode($data, true);

          $old_leave_message = $file_array["quit-message:"];

          $new_leave_message = implode(" ", $args);

          $update_message = str_replace($old_leave_message, $new_leave_message, file_get_contents("config.yml"));

          file_put_contents("config.yml", $update_message);

          $sender->sendMessage(TF::GREEN . "Leave Message Was Successfully Updated!");

          return true;

        }

      }

    }
    
  }
  
?>
