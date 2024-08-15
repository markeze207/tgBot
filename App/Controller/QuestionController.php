<?php

namespace App\Controller;

use App\Model\QuestionModel;
use App\Model\TelegramBot;

class QuestionController extends BaseController
{
    public function __construct(TelegramBot $app)
    {
        parent::__construct($app, new QuestionModel('questions'));
    }

    public function index($page)
    {
        $offset = ($page - 1) * 6;
        $uniqueArray = $this->model->getAll($offset);

        $menu = [];
        $row = [];

        foreach ($uniqueArray as $item) {
            $row[] = ['text' => $item['quest'],
                'callback_data' => 'Question_item_'.$item['id']];
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

        $prefixPrev = 'Question_index_' . ($page - 1);
        $prefixNext = 'Question_index_' . ($page + 1);

        $generateMenu = self::generateMenu($page, $nextPageArray, $prefixPrev, $prefixNext);

        $menu = array_merge($menu, $generateMenu);

        $menu[] = [
            ['text' => '⬅ Вернуться назад', 'callback_data' => 'Home_index'],
        ];
        $this->app->editMessage('Выберите работу', null, $menu);
    }

    public function item($id)
    {
        $item = $this->model->getItem($id);
        $menu[] = [
            ['text' => '⬅ Вернуться назад', 'callback_data' => 'Question_index_1'],
        ];
        $this->app->editMessage($item['ans'], null, $menu, 'Markdown');
    }
}
