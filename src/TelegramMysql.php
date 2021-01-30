<?php

use bot\types\User;

class TelegramMysql
{
    private $mysql;

    public function __construct($config)
    {
        $this->mysql = new mysqli(
            $config['mysql']['hostname'],
            $config['mysql']['username'],
            $config['mysql']['password'],
            $config['mysql']['database_name']
        );
    }

    public function createUserIfDontExists(User $user) {
        $userId = $user->getId();
        $users = $this->mysql->query("SELECT * FROM `tscm_users` WHERE `telegram_id`=".$userId)->fetch_all();
        if (count($users) == 0) {
            if (!$user->getUsername()) {
                $insertCreateUser = "INSERT INTO `tscm_users` (`telegram_id`, `username`, `input`, `state`, `rank`) VALUES ('$userId', NULL, '', 'start', 'guest')";
            } else {
                $insertCreateUser = "INSERT INTO `tscm_users` (`telegram_id`, `username`, `input`, `state`, `rank`) VALUES ('$userId', '".$user->getUsername()."', '', 'start', 'guest')";
            }
            $this->mysql->query($insertCreateUser);
            $users = $this->mysql->query("SELECT * FROM `tscm_users` WHERE `telegram_id`=".$userId)->fetch_all();
        }
        return $users[0];
    }

    public function changeUserState(User $user, $newState = '') {
        $this->mysql->query("UPDATE `tscm_users` SET `state` = '$newState' WHERE `tscm_users`.`telegram_id`=".$user->getId());
    }

    /**
     * @return mysqli
     */
    public function getMysql(): mysqli
    {
        return $this->mysql;
    }
}