<?php

return [
    
    'sender' => 'zmtech',

    'driver' => [

        'zmtech' => [
            'className' => 'Sender\Senders\Zmtech',
            'url' => 'http://api.zmtech.ru:7777/v1',
            'id' => getenv('ID'),
            'key' => getenv('KEY'),
            // 'sender' => 'driver'
            'messageParams' => [
                'phone' => 'phone', 
                'message' => 'message',
                'sender' => 'sender',
                'link' => 'link',
                'expired_after' => 'expired_after'
            ]
        ],

    ]

];