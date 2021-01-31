<?php


namespace bot\Types;


class Chat
{
    public $id;
    public $type;
    public $title;
    public $username;
    public $first_name;
    public $last_name;
    public $photo;
    public $bio;
    public $description;
    public $invite_link;
    private $pinned_message;
    private $permissions;
    public $slow_mode_delay;
    public $sticker_set_name;
    public $can_set_sticker_set;
    public $linked_chat_id;
    private $location;

    public function __construct($chat)
    {
        $this->id = $chat->id;
        $this->type = $chat->type;
        $this->title = $chat->title;
        $this->username = $chat->username;
        $this->first_name = $chat->first_name;
        $this->last_name = $chat->last_name;
        $this->photo = $chat->photo;
        $this->bio = $chat->bio;
        $this->description = $chat->description;
        $this->invite_link = $chat->invite_link;
        $this->pinned_message = $chat->pinned_message;
        $this->permissions = $chat->permissions;
        $this->slow_mode_delay = $chat->slow_mode_delay;
        $this->sticker_set_name = $chat->sticker_set_name;
        $this->can_set_sticker_set = $chat->can_set_sticker_set;
        $this->linked_chat_id = $chat->linked_chat_id;
        $this->location = $chat->location;
    }

    /**
     * @return mixed
     */
    public function getPinnedMessage()
    {
        return $this->pinned_message;
    }

    /**
     * @return mixed
     */
    public function getPermissions()
    {
        return $this->permissions;
    }

    /**
     * @return mixed
     */
    public function getLocation()
    {
        return $this->location;
    }
}