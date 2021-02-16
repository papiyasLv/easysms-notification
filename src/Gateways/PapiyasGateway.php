<?php


namespace Papiyas\Notifications\EasySms\Gateways;


use Overtrue\EasySms\Gateways\Gateway;
use Overtrue\EasySms\Exceptions\NoGatewayAvailableException;


abstract class PapiyasGateway extends \Overtrue\EasySms\Gateways\Gateway
{
    protected NoGatewayAvailableException $noGatewayAvailableException;

    /**
     * @param  NoGatewayAvailableException  $noGatewayAvailableException
     * @return $this
     */
    public function setExceptions(NoGatewayAvailableException $noGatewayAvailableException): static
    {
        $this->noGatewayAvailableException = $noGatewayAvailableException;
        return $this;
    }

    /**
     * @return NoGatewayAvailableException
     */
    public function getExceptions(): NoGatewayAvailableException
    {
        return $this->noGatewayAvailableException;
    }
}
