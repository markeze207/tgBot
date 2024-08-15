<?php

namespace App\Controller;

use App\Model\CraftModel;
use App\Model\TelegramBot;

class CraftController extends BaseController
{
    public function __construct(TelegramBot $app)
    {
        parent::__construct($app, new CraftModel('craft'));
    }

    public function index($page, $isItem = false)
    {
        $offset = ($page - 1) * 6;
        $uniqueArray = $this->model->getAll($offset);

        $menu = [];
        $row = [];

        foreach ($uniqueArray as $item) {
            $row[] = ['text' => $item['name'],
                'callback_data' => 'Craft_item_'.$item['id']];
            if (count($row) == 2) {
                $menu[] = $row;
                $row = [];
            }
        }

        if (count($row) > 0) {
            $menu[] = $row;
        }

        $nextOffset = $offset + 6;
        $nextPageArray = $this->model->getAll($nextOffset);

        $prefixPrev = 'Craft_index_' . ($page - 1);
        $prefixNext = 'Craft_index_' . ($page + 1);

        $generateMenu = self::generateMenu($page, $nextPageArray, $prefixPrev, $prefixNext);

        $menu = array_merge($menu, $generateMenu);

        $menu[] = [
            ['text' => '⬅ Вернуться назад', 'callback_data' => 'Home_index'],
        ];
        $this->app->editMessage('Выберите предмет', null, $menu);
        
        self::handleMenuDisplay($menu, 'Выберите предмет', $isItem);
    }

    public function item($id)
    {
        $item = $this->model->getItem($id);

        $menu[] = [
            ['text' => '⬅ Вернуться назад', 'callback_data' => 'Craft_index_1_true'],
        ];

        $idMessage = $this->app->messageId;
        $chatId = $this->app->chatId;

        $this->app->sendPhoto($item['url_image'], $item['description'], $menu);

        $this->app->deleteMessage($chatId, $idMessage);
    }
}
