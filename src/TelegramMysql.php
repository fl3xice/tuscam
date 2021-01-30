<?php

use bot\types\User;

class TelegramMysql
{
    private $mysql;
    private $config;

    public function __construct($config)
    {
        $this->config = $config;
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

            $rank = "guest";
            if ($userId == $this->config['bot']['owner_bot_id']) {
                $rank = "owner";
            }

            if (!$user->getUsername()) {
                $insertCreateUser = "INSERT INTO `tscm_users` (`telegram_id`, `username`, `input`, `state`, `rank`) VALUES ('$userId', NULL, '', 'start', '$rank')";
            } else {
                $insertCreateUser = "INSERT INTO `tscm_users` (`telegram_id`, `username`, `input`, `state`, `rank`) VALUES ('$userId', '".$user->getUsername()."', '', 'start', '$rank')";
            }
            $this->mysql->query($insertCreateUser);
            $users = $this->mysql->query("SELECT * FROM `tscm_users` WHERE `telegram_id`=".$userId)->fetch_all();
        }
        return $users[0];
    }

    public function changeUserState(User $user, $newState = '') {
        $this->mysql->query("UPDATE `tscm_users` SET `state` = '$newState',`dataForState` = NULL WHERE `tscm_users`.`telegram_id`=".$user->getId());
        return $newState;
    }

    public function getUserState(User $user) {
        return $this->mysql->query("SELECT state FROM `tscm_users` WHERE `tscm_users`.`telegram_id`=".$user->getId())->fetch_all()[0];
    }

    public function getDataForState(User $user) {
        return $this->mysql->query("SELECT dataForState FROM `tscm_users` WHERE `tscm_users`.`telegram_id`=".$user->getId())->fetch_all()[0];
    }

    public function setDataForState(User $user, $data) {
        return $this->mysql->query("UPDATE `tscm_users` SET dataForState='$data' WHERE telegram_id=".$user->getId());
    }

    /**
     * @return mysqli
     */
    public function getMysql(): mysqli
    {
        return $this->mysql;
    }
}