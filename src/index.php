<?php
namespace Sender;

use Dotenv\Dotenv;
use Monolog\Logger;
use Monolog\Handler\StreamHandler;

function getSenderInstance()
{
    $config = include('config.php');
    $driver = $config['sender'];
    $className = $config['driver'][$driver]['className'];
    $url = $config['driver'][$driver]['url'];
    $id = $config['driver'][$driver]['id'];
    $key = $config['driver'][$driver]['key'];
    return new $className($url, $id, $key);
}


function run()
{
    $dotenv = Dotenv::create(__DIR__ . '/../');
    $dotenv->load();
    $log = new Logger('sender');
    $log->pushHandler(new StreamHandler(__DIR__ . '/../logs/response.log', Logger::INFO));
    $sender = getSenderInstance();
    
    try {
        // Отправить одно Sms
        $response = $sender->sendSms([
            'phone' => '79112223344',
            'message' => 'test sms',
            'sender' => 'zmtech.ru'
        ]);
        $log->info(json_encode($response));
        
        // Отправить несоколько Sms (до 100 штук)
        $response = $sender->sendSms([
            [
                'phone' => '79112223344',
                'message' => 'test sms',
                'sender' => 'zmtech.ru'
            ], [
                'phone' => '79112223345',
                'message' => 'test sms 2',
                'sender' => 'zmtech.ru'
            ]
        ]);
        $log->info(json_encode($response));
    } catch ( Exception $e ) {
        $log->error($e->getMessage());
    }
}
