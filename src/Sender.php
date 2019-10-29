<?php

namespace Sender;

class Sender 
{
    private $driver;
    private $logger;
    private $messageParams;

    public function __construct ($logger)
    {
        $config = include('config.php');
        $driverName = $config['sender'];
        $className = $config['driver'][$driverName]['className'];
        $url = $config['driver'][$driverName]['url'];
        $id = $config['driver'][$driverName]['id'];
        $key = $config['driver'][$driverName]['key'];
        $this->messageParams = $config['driver'][$driverName]['messageParams'];
        $this->driver = new $className($url, $id, $key);
        $this->logger = $logger;
    }

    public function getInfo()
    {
        return $this->driver->getInfo();
    }

    public function sendSms($pack)
    {
        $packWithDriverMessageParams = $this->getPackWithDriverMessageParams($pack);
        $response = $this->driver->sendSms($packWithDriverMessageParams);
        $this->logger->info(json_encode($response));
        return $response;
    }

    public function sendViber($pack)
    {
        $packWithDriverMessageParams = $this->getPackWithDriverMessageParams($pack);
        $response = $this->driver->sendViber($packWithDriverMessageParams);
        $this->logger->info(json_encode($response));
        return $response;
    }

    public function getStatuses()
    {
        return $this->driver->getStatuses();
    }

    public function getLastResponse()
    {
        return $this->driver->getLastResponse();
    }

    private function getPackWithDriverMessageParams($pack)
    {
        if (!isset($pack[0]) || !is_array($pack[0])) {
            $pack = [$pack];
        }
        $packWithDriverMessageParams = [];
        foreach ($pack as $message) {
            $packWithDriverMessageParams[] = $this->changeMessageKeys($message);
        }
        return $packWithDriverMessageParams;
    }

    private function changeMessageKeys($message)
    {
        $messageParams = $this->messageParams;

        return array_reduce(array_keys($message), function ($acc, $key) use ($message, $messageParams) {
            $newKey = $messageParams[$key];
            $acc[$newKey] = $message[$key];
            return $acc;
        }, []);
    }
}