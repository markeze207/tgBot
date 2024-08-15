<?php

namespace App\Controller;

use App\Model\TelegramBot;

class DownloadController
{
    private TelegramBot $app;

    public function __construct(TelegramBot $app)
    {
        $this->app = $app;
    }

    public function index()
    {
        $startMenu = [
            [
                ['text' => '⬅ Вернуться назад', 'callback_data' => 'Home_index'],
            ]
        ];

        $this->app->editMessage(
            '🎮 Скачать игру Блек Раша можно здесь: [🔗 Ссылка на скачивание]
            (https://www.memuplay.com/ru/download-memu-on-pc.html)',
            null,
            $startMenu,
            'MarkdownV2'
        );
    }
}
