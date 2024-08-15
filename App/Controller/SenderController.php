<?php

namespace App\Controller;

use App\Model\TelegramBot;

class SenderController
{
    private TelegramBot $app;
    public function __construct(TelegramBot $app)
    {
        $this->app = $app;
    }
    public function index()
    {
        $menu = [
            [
                ['text' => 'Написать свой текст', 'callback_data' => 'Sender_custom'],
            ],
            [
                ['text' => 'Переслать сообщение', 'callback_data' => 'Sender_forward'],
            ],
            [
                ['text' => '⬅ Вернуться назад', 'callback_data' => 'Home_index'],
            ]
        ];
        global $user;
        $count = $user->getCount();
        return $this->app->editMessage(
            "Кол-во пользователей: {$count}\n\nВыберите вариант",
            null,
            $menu
        );
    }

    public function custom()
    {
        global $user;
        $user->updateUser(['peremen' => 2]);
        $menu = [
            [
                ['text' => '⬅ Вернуться назад', 'callback_data' => 'Home_index_clear'],
            ]
        ];
        return $this->app->editMessage('Напишите сообщение для рассылки (фото тоже в это сообщение)', null, $menu);
    }

    public function customSend()
    {
        global $data, $user;

        $this->app->sendMessage('Вы отправили рассылку');
        $user->updateUser(['peremen' => 0]);

        $users = $user->getAll();
        foreach ($users as $us) {
            $this->app->chatId = $us['user_id'];

            if (!empty($data['message']['photo'])) {
                $this->app->sendPhoto(
                    $data['message']['photo'][0]['file_id'],
                    $data['message']['caption'],
                    null,
                    '',
                    $data['message']['caption_entities']
                );
            } else {
                if ($data['message']['entities']) {
                    $this->app->sendMessage($data['message']['text'], null, null, '', $data['message']['entities']);
                } else {
                    $this->app->sendMessage($data['message']['text']);
                }
            }
        }
    }

    public function forward()
    {
        global $user;
        $menu = [
            [
                ['text' => '⬅ Вернуться назад', 'callback_data' => 'Home_index_clear'],
            ]
        ];
        $user->updateUser(['peremen' => 1]);
        return $this->app->editMessage('Перешлите сообщение', null, $menu);
    }

    public function forwardSend()
    {
        global $data, $user;
        $this->app->sendMessage('Вы отправили рассылку');
        $user->updateUser(['peremen' => 0]);

        $users = $user->getAll();
        foreach ($users as $us) {
            $this->app->chatId = $us['user_id'];
            $this->app->forwardMessage($data['message']['chat']['id'], $data['message']['message_id']);
        }
    }
}
