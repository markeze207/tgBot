<?php

namespace App\Controller;

use App\Model\PromocodeModel;
use App\Model\TelegramBot;

class PromocodeController extends BaseController
{
    public function __construct(TelegramBot $app)
    {
        parent::__construct($app, new PromocodeModel('promo'));
    }

    public function index()
    {
        $items = $this->model->getAll(false, true);
        $message = "#allen на сервере BELGOROD\n";
        foreach ($items as $item) {
            $message .= "\n{$item['promo']}";
        }

        $menu[] = [
            ['text' => '⬅ Вернуться назад', 'callback_data' => 'Home_index'],
        ];

        $this->app->editMessage($message, null, $menu);
    }
}
