<?php

namespace App\Controller;

use App\Model\FinanceModel;
use App\Model\TelegramBot;

class FinanceController extends BaseController
{
    public function __construct(TelegramBot $app)
    {
        parent::__construct($app, new FinanceModel('all_biz'));
    }

    public function index()
    {
        $menu = [
            [
                ['text' => 'Уникальный', 'callback_data' => 'Finance_unique_1'],
            ],
            [
                ['text' => 'Номерной', 'callback_data' => 'Finance_number_1'],
            ],
            [
                ['text' => '⬅ Вернуться назад', 'callback_data' => 'Home_index'],
            ]
        ];

        $this->app->editMessage(
            'Выберите тип бизнеса',
            null,
            $menu
        );
    }

    public function number($page)
    {
        $offset = ($page - 1) * 6;
        $uniqueArray = $this->model->getAllNumberType($offset);

        $menu = [];
        $row = [];

        foreach ($uniqueArray as $item) {
            $row[] = ['text' => $item['name'], 'callback_data' => 'Finance_numberNum_'.$item['name'].'_1'];

            if (count($row) == 2) {
                $menu[] = $row;
                $row = [];
            }
        }

        if (count($row) > 0) {
            $menu[] = $row;
        }

        $nextOffset = $offset + 6;
        $nextPageArray = $this->model->getAllNumberType($nextOffset);

        $prefixPrev = 'Finance_number_' . ($page - 1);
        $prefixNext = 'Finance_number_' . ($page + 1);

        $generateMenu = self::generateMenu($page, $nextPageArray, $prefixPrev, $prefixNext);

        $menu = array_merge($menu, $generateMenu);

        $menu[] = [
            ['text' => '⬅ Вернуться назад', 'callback_data' => 'Finance_index'],
        ];
        $this->app->editMessage('Выберите тип бизнеса', null, $menu);
    }

    public function numberNum($name, $page, $isItem = false)
    {
        $offset = ($page - 1) * 6;
        $uniqueArray = $this->model->getAllNumber($name, $offset);

        $menu = [];
        $row = [];

        foreach ($uniqueArray as $item) {
            $row[] = ['text' => $item['number'], 'callback_data' => 'Finance_numberItem_'.$item['id']];

            if (count($row) == 2) {
                $menu[] = $row;
                $row = [];
            }
        }

        if (count($row) > 0) {
            $menu[] = $row;
        }

        $nextOffset = $offset + 6;

        $nextPageArray = $this->model->getAllNumber($name, $nextOffset);

        $prefixPrev = 'Finance_numberNum_'.$name.'_' . ($page - 1);
        $prefixNext = 'Finance_numberNum_'.$name.'_' . ($page + 1);

        $generateMenu = self::generateMenu($page, $nextPageArray, $prefixPrev, $prefixNext);
        $menu = array_merge($menu, $generateMenu);

        $menu[] = [
            ['text' => '⬅ Вернуться назад', 'callback_data' => 'Finance_number_1'],
        ];

        self::handleMenuDisplay($menu, 'Выберите айди бизнеса', $isItem);
    }

    public function itemUnique($id)
    {
        $item = $this->model->getItem($id);

        $menu = [
            [
                ['text' => '⬅ Вернуться назад', 'callback_data' => 'Finance_unique_1_true'],
            ]
        ];

        $idMessage = $this->app->messageId;
        $chatId = $this->app->chatId;

        $this->app->sendPhoto($item['url_image'], $item['name'], $menu);

        $this->app->deleteMessage($chatId, $idMessage);
    }

    public function numberItem($id)
    {
        $item = $this->model->getItem($id);

        $menu = [
            [
                ['text' => '⬅ Вернуться назад', 'callback_data' => 'Finance_numberNum_'.$item['name'].'_1_true'],
            ]
        ];

        $idMessage = $this->app->messageId;
        $chatId = $this->app->chatId;

        $this->app->sendPhoto($item['url_image'], $item['name'], $menu);

        $this->app->deleteMessage($chatId, $idMessage);
    }

    public function unique($page, $isItem = false)
    {
        $offset = ($page - 1) * 6;
        $uniqueArray = $this->model->getAllUnique($offset);
        $menu = [];
        $row = [];

        foreach ($uniqueArray as $key => $item) {
            $row[] = ['text' => $item['name'], 'callback_data' => 'Finance_itemUnique_'.$item['id']];

            if (count($row) == 2) {
                $menu[] = $row;
                $row = [];
            }
        }

        if (count($row) > 0) {
            $menu[] = $row;
        }

        $nextOffset = $offset + 6;
        $nextPageArray = $this->model->getAllUnique($nextOffset);

        $prefixPrev = 'Finance_unique_' . ($page - 1);
        $prefixNext = 'Finance_unique_' . ($page + 1);

        $generateMenu = self::generateMenu($page, $nextPageArray, $prefixPrev, $prefixNext);
        $menu = array_merge($menu, $generateMenu);

        $menu[] = [
            ['text' => '⬅ Вернуться назад', 'callback_data' => 'Finance_index'],
        ];

        self::handleMenuDisplay($menu, 'Выберите бизнес', $isItem);
    }
}
