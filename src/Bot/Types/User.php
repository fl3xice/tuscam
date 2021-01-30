<?php


namespace bot\types;


class User
{
    private $id;
    private $is_bot;
    private $first_name;
    private $last_name;
    private $username;
    private $language_code;
    private $can_join_groups;
    private $can_read_all_group_messages;
    private $supports_inline_queries;

    public function __construct($user)
    {
        $this->id = $user->id;
        $this->is_bot = $user->is_bot;
        $this->first_name = $user->first_name;
        $this->last_name = $user->last_name;
        $this->username = $user->username;
        $this->language_code = $user->language_code;
        $this->can_join_groups = $user->can_join_groups;
        $this->can_read_all_group_messages = $user->can_read_all_group_messages;
        $this->supports_inline_queries = $user->supports_inline_queries;
    }

    /**
     * @return mixed
     */
    public function getCanJoinGroups()
    {
        return $this->can_join_groups;
    }

    /**
     * @return mixed
     */
    public function getCanReadAllGroupMessages()
    {
        return $this->can_read_all_group_messages;
    }

    /**
     * @return mixed
     */
    public function getFirstName()
    {
        return $this->first_name;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return bool
     */
    public function getIsBot() : bool
    {
        return $this->is_bot;
    }

    /**
     * @return mixed
     */
    public function getLanguageCode()
    {
        return $this->language_code;
    }

    /**
     * @return mixed
     */
    public function getLastName()
    {
        return $this->last_name;
    }

    /**
     * @return mixed
     */
    public function getSupportsInlineQueries()
    {
        return $this->supports_inline_queries;
    }

    /**
     * @return mixed
     */
    public function getUsername()
    {
        return $this->username;
    }
}