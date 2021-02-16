<?php


namespace Papiyas\Notifications\EasySms;

use Overtrue\EasySms\EasySms as BaseEasySms;
use Overtrue\EasySms\Exceptions\InvalidArgumentException;
use Overtrue\EasySms\Exceptions\NoGatewayAvailableException;
use Overtrue\EasySms\Support\Config;
use Papiyas\Notifications\EasySms\Messages\EasySmsMessage;

class EasySms extends BaseEasySms
{
    /**
     * @param $to
     * @param $message
     * @param  array  $gateways
     * @throws InvalidArgumentException
     */
    public function safeSend($to, EasySmsMessage $message, array $gateways = [])
    {
        try {
            $this->send($to, $message, $gateways);
        } catch (NoGatewayAvailableException $e) {
            $config = $this->config->get('default.errors', []);

            if (!empty($config)) {
                $this->gateway($config['gateway'])->setExceptions($e)->send($to, $message, new Config($config));
            }
        }
    }
}
