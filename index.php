<?php
require_once('vendor/autoload.php');

use App\Controller\SenderController;
use App\Model\TelegramBot;
use App\Controller\HomeController;
use App\Controller\UserController;
use Dotenv\Dotenv;

$dotenv = Dotenv::createImmutable(dirname(__DIR__));
$dotenv->load();

$webhook_data = file_get_contents('php://input');
$data = json_decode($webhook_data, true);

$app = new TelegramBot($_ENV['API_KEY'], $data);

$userId = $data['message']['from']['id'] ?? $data['callback_query']['from']['id'];

$user = new UserController($userId);
if (!$user->checkUser()) {
    if (!$user->createUser()) {
        die();
    }
}
$channelIds = array('@tg' => '-1', '@tg2' => '-2');
foreach ($channelIds as $channelLink => $channel) {
    $response = $app->getChatMember($channel, $userId);
    $response = $response['result'];
    $isSubscribed = ($response['status'] == 'member'
        || $response['status'] == 'administrator'
        || $response['status'] == 'creator');
    if (!$isSubscribed) {
        $app->sendMessage("Вы не подписаны на канал {$channelLink}! 
        После подписки введите /start ещё раз!");
        die();
    }
}

if (isset($data['callback_query'])) {
    $namespace = 'App\Controller\\';

    $parts = explode('_', $data['callback_query']['data']);

    $className = ucfirst($parts[0]);
    $methodName = $parts[1];
    $fullClassName = $namespace . $className;

    $args = array_slice($parts, 2);

    if (class_exists($fullClassName)) {
        $object = new $fullClassName($app);
        if (empty($args)) {
            $object->$methodName();
        } else {
            call_user_func_array([$object, $methodName], $args);
        }
    } else {
        $home = new HomeController($app);
        $app->sendMessage('Такая команда отсутствует', null, $home->mainMenu);
    }
} else {
    $home = new HomeController($app);
    if ($userId == 1 || $userId == 2) {
        $userGet = $user->getUser();
        $sender = new SenderController($app);
        if ($userGet['peremen'] == 1 && $data['message']['chat']['id']) {
            $sender->forwardSend();
        } elseif ($userGet['peremen'] == 2) {
            $sender->customSend();
        }
    }
    $app->sendMessage('Добро пожаловать в главное меню!', null, $home->mainMenu);
}

die('ok');
