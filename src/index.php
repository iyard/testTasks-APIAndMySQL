<?php
namespace Sender;

use Sender\Sender;
use Dotenv\Dotenv;
use Monolog\Logger;
use Monolog\Handler\StreamHandler;

function run()
{
    $dotenv = Dotenv::create(__DIR__ . '/../');
    $dotenv->load();
    $logger = new Logger('sender');
    $logger->pushHandler(new StreamHandler(__DIR__ . '/../logs/response.log', Logger::INFO));

    $sender = new Sender($logger);
    
    try {
        // Отправить одно Sms
        $response = $sender->sendSms([
            'phone' => '79112223344',
            'message' => 'test sms',
            'sender' => 'zmtech.ru'
        ]);
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
    } catch ( Exception $e ) {
        $logger->error($e->getMessage());
    }
}