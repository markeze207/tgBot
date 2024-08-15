<?php

namespace App\Controller;

abstract class BaseController
{
    protected $app;
    protected $model;

    public function __construct($app, $model)
    {
        $this->app = $app;
        $this->model = $model;
    }
    public function generateMenu($page, $nextPageArray, $prefixPrev, $prefixNext): array
    {
        $menu = [];
        if ($page > 1) {
            if (count($nextPageArray) > 0) {
                $menu[] = [
                    ['text' => '<<', 'callback_data' => $prefixPrev],
                    ['text' => '>>', 'callback_data' => $prefixNext],
                ];
            } else {
                $menu[] = [
                    ['text' => '<<', 'callback_data' => $prefixPrev],
                ];
            }
        } else {
            if (count($nextPageArray) > 0) {
                $menu[] = [
                    ['text' => '>>', 'callback_data' => $prefixNext],
                ];
            }
        }

        return $menu;
    }

    protected function handleMenuDisplay($menu, $message, $isItem, $media = null)
    {
        if ($isItem) {
            $idMessage = $this->app->messageId;
            $chatId = $this->app->chatId;

            $this->app->sendMessage($message, null, $menu);
            $this->app->deleteMessage($chatId, $idMessage);
        } else {
            $this->app->editMessage($message, null, $menu);
        }
    }
}
