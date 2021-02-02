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
            $state = "start";
            if ($userId == $this->config['bot']['owner_bot_id']) {
                $rank = "owner";
                $state = '';
            }

            if (!$user->getUsername()) {
                $insertCreateUser = "INSERT INTO `tscm_users` (`telegram_id`, `username`, `input`, `state`, `rank`) VALUES ('$userId', NULL, '', '$state', '$rank')";
            } else {
                $insertCreateUser = "INSERT INTO `tscm_users` (`telegram_id`, `username`, `input`, `state`, `rank`) VALUES ('$userId', '".$user->getUsername()."', '', '$state', '$rank')";
            }
            $this->mysql->query($insertCreateUser);
            $users = $this->mysql->query("SELECT * FROM `tscm_users` WHERE `telegram_id`=".$userId)->fetch_all();
        }
        return $users[0];
    }

    public function getUserByDb($id) {
        $users = $this->mysql->query("SELECT * FROM `tscm_users` WHERE `telegram_id`=".$id)->fetch_all();
        return $users[0];
    }

    public function changeUserRank($id, $newRank) {
        return $this->mysql->query("UPDATE `tscm_users` SET `rank`='$newRank' WHERE `telegram_id`=".$id);
    }

    public function getInput(User $user) {
        return $this->mysql->query("SELECT input FROM `tscm_users` WHERE `telegram_id`=".$user->getId())->fetch_all()[0];
    }

    public function setInput(User $user, $data) {
        return $this->mysql->query("UPDATE `tscm_users` SET `input`='$data' WHERE `telegram_id`=".$user->getId());
    }

    public function changeUserState(User $user, $newState = ''): string
    {
        $this->mysql->query("UPDATE `tscm_users` SET `state` = '$newState',`dataForState` = NULL WHERE `telegram_id`=".$user->getId());
        return $newState;
    }

    public function changeUserStateWithID($id, $newState) {
        $this->mysql->query("UPDATE `tscm_users` SET `state` = '$newState',`dataForState` = NULL WHERE `telegram_id`=".$id);
        return $newState;
    }

    public function getUserState(User $user) {
        return $this->mysql->query("SELECT state FROM `tscm_users` WHERE `telegram_id`=".$user->getId())->fetch_all()[0];
    }

    public function getDataForState(User $user) {
        return $this->mysql->query("SELECT dataForState FROM `tscm_users` WHERE `telegram_id`=".$user->getId())->fetch_all()[0][0];
    }

    public function setDataForState(User $user, $data) {
        return $this->mysql->query("UPDATE `tscm_users` SET dataForState='$data' WHERE `telegram_id`=".$user->getId());
    }

    /**
     * @return mysqli
     */
    public function getMysql(): mysqli
    {
        return $this->mysql;
    }
}