<?php
namespace App\Model;

class TelegramBot
{
    private string $apiUrl;

    public $chatId;

    public $messageId;

    private $curlHandle;

    public function __construct($botToken, $data)
    {
        $this->apiUrl = "https://api.telegram.org/bot{$botToken}/";
        if (isset($data['message']['chat']['id'])) {
            $this->chatId = $data['message']['chat']['id'];
            $this->messageId = $data['message']['message_id'];
        } else {
            $this->chatId = $data['callback_query']['message']['chat']['id'];
            $this->messageId = $data['callback_query']['message']['message_id'];
        }

        $this->curlHandle = curl_init();
        curl_setopt($this->curlHandle, CURLOPT_RETURNTRANSFER, 1);
    }

    public function getChatMember($chatId, $userId)
    {
        $url = $this->apiUrl . "getChatMember";
        $params = [
            'chat_id' => $chatId,
            'user_id' => $userId
        ];

        return $this->sendRequest($url, $params);
    }

    public function sendMessage($messageText, $media = null, $buttons = null, $parse_mode = 'HTML', $entities = null)
    {
        $data = [
            'chat_id' => $this->chatId,
            'text' => $messageText,
            'parse_mode' => $parse_mode
        ];

        if ($media) {
            $data['photo'] = $media;
        }

        if ($buttons) {
            $data['reply_markup'] = json_encode([
                'inline_keyboard' => $buttons
            ]);
        }

        if ($entities) {
            $data['entities'] = json_encode($entities);
        }
        $url = $this->apiUrl . ($media ? 'sendPhoto' : 'sendMessage');
        return $this->sendRequest($url, $data);
    }

    public function editMessage($newText = null, $newMedia = null, $newButtons = null, $parse_mode = 'HTML')
    {
        $data = [
            'chat_id' => $this->chatId,
            'message_id' => $this->messageId
        ];

        if ($newText && !$newMedia) {
            $data['text'] = $newText;
            $data['parse_mode'] = $parse_mode;

            if ($newButtons) {
                $data['reply_markup'] = json_encode([
                    'inline_keyboard' => $newButtons
                ]);
            }

            $url = $this->apiUrl . 'editMessageText';
        } elseif ($newMedia) {
            $data['media'] = json_encode([
                'type' => 'photo',
                'media' => $newMedia,
                'caption' => $newText,
                'parse_mode' => 'HTML',
            ]);

            if ($newButtons) {
                $data['reply_markup'] = json_encode([
                    'inline_keyboard' => $newButtons
                ]);
            }

            $url = $this->apiUrl . 'editMessageMedia';
        } else {
            return false;
        }

        return $this->sendRequest($url, $data);
    }

    public function sendPhoto($photoUrl, $caption = null, $buttons = null, $parse_mode = 'HTML', $entities = null)
    {
        $data = [
            'chat_id' => $this->chatId,
            'photo' => $photoUrl,
            'caption' => $caption,
            'parse_mode' => $parse_mode
        ];

        if ($buttons) {
            $data['reply_markup'] = json_encode([
                'inline_keyboard' => $buttons
            ]);
        }
        if ($entities) {
            $data['caption_entities'] = json_encode($entities);
        }
        $url = $this->apiUrl . 'sendPhoto';
        $this->sendRequest($url, $data);
    }
    public function deleteMessage($chatId, $messageId)
    {
        global $start;
        $data = [
            'chat_id' => $chatId,
            'message_id' => $messageId
        ];

        $url = $this->apiUrl . 'deleteMessage';

        return $this->sendRequest($url, $data);
    }

    public function forwardMessage($fromChatId, $messageId)
    {
        $data = [
            'chat_id' => $this->chatId,
            'from_chat_id' => $fromChatId,
            'message_id' => $messageId
        ];

        $url = $this->apiUrl . 'forwardMessage';
        return $this->sendRequest($url, $data);
    }
    private function sendRequest($url, $data)
    {
        curl_setopt($this->curlHandle, CURLOPT_URL, $url);
        curl_setopt($this->curlHandle, CURLOPT_POSTFIELDS, $data);

        $response = curl_exec($this->curlHandle);

        return json_decode($response, true);
    }
}
