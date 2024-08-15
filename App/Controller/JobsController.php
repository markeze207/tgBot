<?php

namespace App\Controller;

use App\Model\JobsModel;
use App\Model\TelegramBot;

class JobsController extends BaseController
{
    public function __construct(TelegramBot $app)
    {
        parent::__construct($app, new JobsModel('jobs_pay'));
    }

    public function index($page)
    {
        $offset = ($page - 1) * 6;
        $uniqueArray = $this->model->getAll($offset);

        $menu = [];
        $row = [];

        foreach ($uniqueArray as $item) {
            $row[] = ['text' => $item['name'],
                'callback_data' => 'Jobs_item_'.$item['id']];
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

        $prefixPrev = 'Jobs_index_' . ($page - 1);
        $prefixNext = 'Jobs_index_' . ($page + 1);

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
            ['text' => '⬅ Вернуться назад', 'callback_data' => 'Jobs_index_1'],
        ];
        $this->app->editMessage("Работа: *{$item['name']}*\n\n".
            "Необходимый уровень: *{$item['lvl']}*\n\n".
            "Зарплата в час: *{$item['money']}*\n\n".
            "Дополнительно описание: *{$item['description']}*", null, $menu, 'Markdown');
    }
}
