<?php


namespace bot\types;


class Message
{
    private $message_id;
    private $from;
    private $sender_chat;
    private $date;
    private $chat;
    private $forward_from;
    private $forward_from_chat;
    private $forward_signature;
    private $forward_sender_name;
    private $forward_date;
    private $reply_to_message;
    private $via_bot;
    private $edit_date;
    private $media_group_id;
    private $author_signature;
    private $text;
    private $entities;
    private $animation;
    private $audio;
    private $document;
    private $photo;
    private $sticker;
    private $video;
    private $video_note;
    private $voice;
    private $caption;
    private $caption_entities;
    private $contact;
    private $dice;
    private $game;
    private $poll;
    private $venue;
    private $location;
    private $new_chat_members;
    private $left_chat_members;
    private $new_chat_title;
    private $new_chat_photo;
    private $delete_chat_photo;
    private $group_chat_created;
    private $supergroup_chat_created;
    private $channel_chat_created;
    private $migrate_to_chat_id;
    private $migrate_from_chat_id;
    private $pinned_message;
    private $invoice;
    private $successful_payment;
    private $connected_website;
    private $passport_data;
    private $proximity_alert_triggered;
    private $reply_markup;

    public function __construct($message)
    {
        $this->message_id = $message->message_id;
        $this->from = $message->from;
        $this->animation = $message->animation;
        $this->audio = $message->audio;
        $this->author_signature = $message->author_signature;
        $this->caption = $message->caption;
        $this->text = $message->text;
        $this->sender_chat = $message->sender_chat;
        $this->date = $message->date;
        $this->chat = $message->chat;
        $this->forward_from = $message->forward_from;
        $this->forward_from_chat = $message->forward_from_chat;
        $this->forward_signature = $message->forward_signature;
        $this->forward_sender_name = $message->forward_sender_name;
        $this->forward_date = $message->forward_date;
        $this->reply_to_message = $message->reply_to_message;
        $this->via_bot = $message->via_bot;
        $this->edit_date = $message->edit_date;
        $this->media_group_id = $message->media_group_id;
        $this->entities = $message->entities;
        $this->document = $message->document;
        $this->photo = $message->photo;
        $this->sticker = $message->sticker;
        $this->video = $message->video;
        $this->video_note = $message->video_note;
        $this->voice = $message->voice;
        $this->caption_entities = $message->caption_entities;
        $this->contact = $message->contact;
        $this->dice = $message->dice;
        $this->game = $message->game;
        $this->poll = $message->poll;
        $this->venue = $message->venue;
        $this->location = $message->location;
        $this->new_chat_members = $message->new_chat_members;
        $this->left_chat_members = $message->left_chat_members;
        $this->new_chat_title = $message->new_chat_title;
        $this->new_chat_photo = $message->new_chat_photo;
        $this->delete_chat_photo = $message->delete_chat_photo;
        $this->group_chat_created = $message->group_chat_created;
        $this->supergroup_chat_created = $message->supergroup_chat_created;
        $this->channel_chat_created = $message->channel_chat_created;
        $this->migrate_to_chat_id = $message->migrate_to_chat_id;
        $this->migrate_from_chat_id = $message->migrate_from_chat_id;
        $this->pinned_message = $message->pinned_message;
        $this->invoice = $message->invoice;
        $this->successful_payment = $message->successful_payment;
        $this->connected_website = $message->connected_website;
        $this->passport_data = $message->passport_data;
        $this->proximity_alert_triggered = $message->proximity_alert_triggered;
        $this->reply_markup = $message->reply_markup;
    }

    /**
     * @return mixed
     */
    public function getText()
    {
        return $this->text;
    }

    /**
     * @return mixed
     */
    public function getAnimation()
    {
        return $this->animation;
    }

    /**
     * @return mixed
     */
    public function getAudio()
    {
        return $this->audio;
    }

    /**
     * @return mixed
     */
    public function getAuthorSignature()
    {
        return $this->author_signature;
    }

    /**
     * @return mixed
     */
    public function getCaption()
    {
        return $this->caption;
    }

    /**
     * @return mixed
     */
    public function getCaptionEntities()
    {
        return $this->caption_entities;
    }

    /**
     * @return mixed
     */
    public function getChannelChatCreated()
    {
        return $this->channel_chat_created;
    }

    /**
     * @return Chat
     */
    public function getChat(): Chat
    {
        return new Chat($this->chat);
    }

    /**
     * @return mixed
     */
    public function getConnectedWebsite()
    {
        return $this->connected_website;
    }

    /**
     * @return mixed
     */
    public function getContact()
    {
        return $this->contact;
    }

    /**
     * @return mixed
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * @return mixed
     */
    public function getDeleteChatPhoto()
    {
        return $this->delete_chat_photo;
    }

    /**
     * @return mixed
     */
    public function getDice()
    {
        return $this->dice;
    }
    /**
     * @return mixed
     */
    public function getDocument()
    {
        return $this->document;
    }

    /**
     * @return mixed
     */
    public function getEditDate()
    {
        return $this->edit_date;
    }

    /**
     * @return mixed
     */
    public function getEntities()
    {
        return $this->entities;
    }

    /**
     * @return mixed
     */
    public function getForwardDate()
    {
        return $this->forward_date;
    }

    /**
     * @return User
     */
    public function getForwardFrom() : User
    {
        return new User($this->forward_from);
    }

    /**
     * @return mixed
     */
    public function getForwardFromChat()
    {
        return $this->forward_from_chat;
    }

    /**
     * @return mixed
     */
    public function getForwardSenderName()
    {
        return $this->forward_sender_name;
    }

    /**
     * @return mixed
     */
    public function getForwardSignature()
    {
        return $this->forward_signature;
    }

    /**
     * @return User
     */
    public function getFrom() : User
    {
        return new User($this->from);
    }

    /**
     * @return mixed
     */
    public function getGame()
    {
        return $this->game;
    }

    /**
     * @return mixed
     */
    public function getGroupChatCreated()
    {
        return $this->group_chat_created;
    }

    /**
     * @return mixed
     */
    public function getInvoice()
    {
        return $this->invoice;
    }

    /**
     * @return User
     */
    public function getLeftChatMembers() : User
    {
        return new User($this->left_chat_members);
    }

    /**
     * @return mixed
     */
    public function getLocation()
    {
        return $this->location;
    }

    /**
     * @return mixed
     */
    public function getMediaGroupId()
    {
        return $this->media_group_id;
    }

    /**
     * @return mixed
     */
    public function getMessageId()
    {
        return $this->message_id;
    }

    /**
     * @return mixed
     */
    public function getMigrateFromChatId()
    {
        return $this->migrate_from_chat_id;
    }

    /**
     * @return mixed
     */
    public function getMigrateToChatId()
    {
        return $this->migrate_to_chat_id;
    }

    /**
     * @return mixed
     */
    public function getNewChatMembers()
    {
        return $this->new_chat_members;
    }

    /**
     * @return mixed
     */
    public function getNewChatPhoto()
    {
        return $this->new_chat_photo;
    }

    /**
     * @return mixed
     */
    public function getNewChatTitle()
    {
        return $this->new_chat_title;
    }

    /**
     * @return mixed
     */
    public function getPassportData()
    {
        return $this->passport_data;
    }

    /**
     * @return mixed
     */
    public function getPhoto()
    {
        return $this->photo;
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
    public function getPoll()
    {
        return $this->poll;
    }

    /**
     * @return mixed
     */
    public function getProximityAlertTriggered()
    {
        return $this->proximity_alert_triggered;
    }

    /**
     * @return mixed
     */
    public function getReplyMarkup()
    {
        return $this->reply_markup;
    }

    /**
     * @return mixed
     */
    public function getReplyToMessage()
    {
        return $this->reply_to_message;
    }

    /**
     * @return Chat
     */
    public function getSenderChat() : Chat
    {
        return new Chat($this->sender_chat);
    }

    /**
     * @return mixed
     */
    public function getSticker()
    {
        return $this->sticker;
    }

    /**
     * @return mixed
     */
    public function getSuccessfulPayment()
    {
        return $this->successful_payment;
    }

    /**
     * @return mixed
     */
    public function getSupergroupChatCreated()
    {
        return $this->supergroup_chat_created;
    }

    /**
     * @return mixed
     */
    public function getVenue()
    {
        return $this->venue;
    }

    /**
     * @return User
     */
    public function getViaBot() : User
    {
        return new User($this->via_bot);
    }

    /**
     * @return mixed
     */
    public function getVideo()
    {
        return $this->video;
    }

    /**
     * @return mixed
     */
    public function getVideoNote()
    {
        return $this->video_note;
    }

    /**
     * @return mixed
     */
    public function getVoice()
    {
        return $this->voice;
    }

}