<?php

namespace SMSSender;

class SMSSender
{

    public static function getInstance($config)
    {
        $config;
        $sender = $config['sender'];
        $className = $config['connections'][$sender]['className'];
        $url = $config['connections'][$sender]['url'];
        $id = $config['connections'][$sender]['id'];
        $key = $config['connections'][$sender]['key'];
        $obj = new $className($url, $id, $key);

        
    }
}

