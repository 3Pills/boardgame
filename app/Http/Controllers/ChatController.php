<?php

namespace App\Http\Controllers;

use App\Chat;
use App\ChatMessage;

use Input;
use Carbon\Carbon;

class ChatController extends BaseController {

    public function sendMessage() {
        $user_id = Input::get('user_id');
        $text = Input::get('text');

        $chatMessage = new ChatMessage();
        $chatMessage->user_id = $user_id;
        $chatMessage->message = $text;
        $chatMessage->save();
    }

    public function isTyping() {
        $user_id = Input::get('user_id');

        $chat = Chat::find(1);
        if (count($chat) > 0) {
            if ($chat->user_id1 == $user_id)
                $chat->user1_is_typing = true;
            else
                $chat->user2_is_typing = true;
            $chat->save();
        }
    }

    public function notTyping() {
        $user_id = Input::get('user_id');

        $chat = Chat::find(1);
        if (count($chat) > 0) {
            if ($chat->user_id1 == $user_id)
                $chat->user1_is_typing = false;
            else
                $chat->user2_is_typing = false;
            $chat->save();
        }
    }

    public function retrieveChatMessages() {
        $user_id = Input::get('user_id');
        
        $message = ChatMessage::where('user_id', '!=', $user_id)->where('read', '=', false)->first();
        
        if (count($message) > 0) {
            $message->read = true;
            $message->save();
            return $message->message;
        }
    }

    public function retrieveTypingStatus() {
        $user_id = Input::get('user_id');

        $chat = Chat::find(1);
        if (count($chat) > 0) {
            if ($chat->user1 == $user_id) {
                if ($chat->user2_is_typing)
                    return $chat->user2;
            }
            else {
                if ($chat->user1_is_typing)
                    return $chat->user1;
            }
        }
    }
}