<?php

namespace App\Controller;

use App\Model\TelegramBot;

class HomeController
{
    private TelegramBot $app;

    public array $mainMenu =  [
        [
            ['text' => '🔍 Узнать финку бизнеса', 'callback_data' => 'Finance_index'],
        ],
        [
            ['text' => '🖥️ BLACK RUSSIA на ПК', 'callback_data' => 'Download_index'],
            ['text' => '🌏 Расположение бизнесов', 'callback_data' => 'Business_index'],
        ],
        [
            ['text' => '💸 ЗП Фракций', 'callback_data' => 'Fraction_index_1'],
            ['text' => '💰 ЗП Работ', 'callback_data' => 'Jobs_index_1'],
        ],
        [
            ['text' => '⛏️ Крафты', 'callback_data' => 'Craft_index_1'],
            ['text' => '❓ Отдел кадров ответы', 'callback_data' => 'Question_index_1'],
        ],
        [
            ['text' => '💎 Промокоды', 'callback_data' => 'Promocode_index'],
        ],
    ];

    public function __construct(TelegramBot $app)
    {
        $this->app = $app;
    }
    public function index($type = null)
    {
        global $userId;
        if ($userId == 1 || $userId == 2) {
            if ($type == 'clear') {
                $user = new UserController($userId);
                $user->updateUser(['peremen' => 0]);
            }
            $this->mainMenu[] = [['text' => '🖥️ Рассылка', 'callback_data' => 'Sender_index']];
        }
        return $this->app->editMessage(
            'Добро пожаловать в главное меню!',
            null,
            $this->mainMenu
        );
    }
}
