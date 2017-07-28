<?php
/**
 * Created by PhpStorm.
 * User: iknsa
 * Date: 05/07/17
 * Time: 00:17
 */

namespace PhpLight\Mailer;


use PhpLight\Framework\Components\Config;

class Mailer
{
    const ALLOWED_TRANSPORT = ["smtp"];

    private $configToUse;
    /**
     * @var array
     */
    private $config;

    /**
     * @var string
     */
    private $transport;

    /**
     * @var \Swift_Mailer
     */
    private $mailer;

    public function __construct($name="")
    {
        $this->configToUse = $name === "" ? "default" : $name;

        $this->setConfig();
        $this->checkConfig();
        $this->setTransport();
        $this->setMailer();
    }

    public function mailer()
    {
        return $this->mailer;
    }

    /**
     * @return Mailer
     */
    private function setMailer()
    {
        $strategy = 'PhpLight\Mailer\Strategy\\' . ucfirst($this->transport) . 'Strategy';

        $this->mailer = (new $strategy($this->config[$this->configToUse]))->getMailer();

        return $this;
    }

    /**
     * @throws \Exception
     *
     * @return Mailer
     */
    private function setConfig()
    {
        $this->config = (new Config())->getConfig();
        if (!isset($this->config["phplight_mailer"]))
            throw new \Exception("'phplight_mailer' config is missing");

        $this->config = $this->config["phplight_mailer"];

        return $this;
    }

    /**
     * @throws \Exception
     *
     * @return Mailer
     */
    private function checkConfig()
    {
        if (!isset($this->config[$this->configToUse]["transport"])) {
            /** @todo add sendmail and corresponding strategy */
            throw new \Exception("The transport mode is missing under phplight_mailer config");
        }

        if (!in_array(strtolower($this->config[$this->configToUse]["transport"]), self::ALLOWED_TRANSPORT)) {
            $validValues = "";
            foreach (self::ALLOWED_TRANSPORT as $item) {
                $validValues .= $item . ' ';
            }

            throw new \Exception("The value of transport mode is wrong under phplight_mailer config. Valid values are $validValues");
        }

        return $this;
    }

    /**
     * @return Mailer
     */
    private function setTransport()
    {
        $this->transport = $this->config[$this->configToUse]["transport"];

        return $this;
    }
}