<?php


namespace Papiyas\Notifications\EasySms\Gateways;


use GuzzleHttp\Exception\ClientException;
use Illuminate\Support\Facades\Log;
use Overtrue\EasySms\Contracts\MessageInterface;
use Overtrue\EasySms\Contracts\PhoneNumberInterface;
use Overtrue\EasySms\Exceptions\GatewayErrorException;
use Overtrue\EasySms\Exceptions\NoGatewayAvailableException;
use Overtrue\EasySms\Support\Config;
use Papiyas\Notifications\EasySms\EasySms;
use Papiyas\Notifications\EasySms\Messages\EasySmsMessage;

class ErrorLogGateway extends PapiyasGateway
{
    /**
     * @inheritDoc
     */
    public function send(PhoneNumberInterface $to, MessageInterface $message, Config $config): array
    {
        // TODO: Implement send() method.
        if (is_array($to)) {
            $to = implode(',', $to);
        }

        $easySms = app()->make('easysms');

        foreach ($this->getExceptions()->getExceptions() as $gateway => $exception) {

            if ($exception instanceof ClientException) {
                $errorMessage = $exception->getResponse()->getBody()->getContents();
            } elseif ($exception instanceof GatewayErrorException) {
                $errorMessage = json_encode($exception->raw, JSON_UNESCAPED_UNICODE);
            } else {
                $errorMessage = $exception->getMessage();
            }

            $gatewayClass = $easySms->gateway($gateway);

            $errorMessage = sprintf(
                'gateway: %s | to: %s | content: "%s"  | template: "%s" | data: %s | response: %s',
                $gateway,
                $to,
                $message->getContent($gatewayClass),
                $message->getTemplate($gatewayClass),
                json_encode($message->getData($gatewayClass)),
                $errorMessage,
            );


            Log::channel($config->get('channel'))->error($errorMessage);
        }


        return [];
    }
}
