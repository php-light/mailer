<?php
/**
 * Created by PhpStorm.
 * User: iknsa
 * Date: 05/07/17
 * Time: 00:06
 */

namespace PhpLight\Mailer\Strategy;


class SmtpStrategy
{
    /**
     * @var array
     */
    private $config;

    /**
     * @var \Swift_SmtpTransport
     */
    private $transport;

    public function __construct($config)
    {
        $this->config = $config;
        $this->setTransport();
    }

    /**
     * @return \Swift_Mailer
     */
    public function getMailer()
    {
        return new \Swift_Mailer($this->transport);
    }

    /**
     * @return SmtpStrategy
     */
    private function setTransport()
    {
        $this->transport = (new \Swift_SmtpTransport($this->config["host"], $this->config["port"]))
            ->setUsername($this->config["username"])
            ->setPassword($this->config["password"])
        ;

        return $this;
    }
}
