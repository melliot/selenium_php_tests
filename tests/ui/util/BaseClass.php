<?php

namespace tests\ui\util;

use Facebook\WebDriver\Remote\RemoteWebDriver;
use Facebook\WebDriver\WebDriverBy;
use Facebook\WebDriver\WebDriverExpectedCondition;
use PHPUnit_Framework_TestCase;
use Symfony\Component\Yaml\Yaml;

/**
 * Helpers functions for tests.
 */
class BaseClass extends PHPUnit_Framework_TestCase
{
    /**
     * @var RemoteWebDriver
     */
    protected $driver;

    /**
     * @var array
     */
    protected $parameters;

    /**
     * Load config from parameters_test.yml.
     *
     * @param RemoteWebDriver $driver
     *
     * @throws \Exception
     */
    public function __construct($driver = null)
    {
        parent::__construct();
        $this->parameters = $this->getParameters();
        $this->driver = $driver;
    }

    /**
     * Return value from parameters.yml.
     *
     * @param string $key
     *
     * @return mixed
     *
     * @throws \Exception
     */
    protected function getValue($key)
    {
        if (!array_key_exists($key, $this->parameters)) {
            throw new \Exception('Parameter for \'' . $key . '\' does not exist!');
        }

        return $this->parameters[$key];
    }
    /**
     * Parse config file and return parameters.
     *
     * @return array
     *
     * @throws \Exception
     */
    private function getParameters()
    {
        $config = Yaml::parse(__DIR__ . '/../../../config/parameters.yml');

        if (empty($config)) {
            throw new \Exception('You must specify parameters!');
        }

        return $config['parameters'];
    }

    /**
     * @param WebDriverBy $element
     *
     * @return RemoteWebDriver
     */
    public function findElement($element)
    {
        return $this->driver->findElement($element);
    }

    /**
     * @return mixed
     *
     * @throws NoSuchElementException
     */
    public function waitForAjaxFinished()
    {
        $this->driver->wait()->until(function(){
            return $this->driver->executeScript('return window.jQuery != undefined && jQuery.active === 0');
        }, 'Ajax not finished before the timeout');
    }

    /**
     * @param WebDriverBy $by
     *
     * @return WebDriverElement
     *
     * @throws NoSuchElementException
     */
    protected function waitForPresenceElementLocated($by)
    {
        $this->waitForAjaxRequestsFinished();
        $this->driver->wait()->until(WebDriverExpectedCondition::presenceOfElementLocated($by));

        return $this->driver->findElement($by);
    }

    /**
     * @param WebDriverBy $by
     *
     * @return \RemoteWebElement
     *
     * @throws \NoSuchElementException
     */
    protected function waitForElementClickable($by)
    {
        $this->waitForAjaxRequestsFinished();
        $this->driver->wait()->until(WebDriverExpectedCondition::elementToBeClickable($by));

        return $this->driver->findElement($by);
    }

    /**
     * @param WebDriverBy $by
     *
     * @return \WebDriverElement
     *
     * @throws \NoSuchElementException
     */
    protected function waitForElementVisible($by)
    {
        $this->waitForAjaxRequestsFinished();
        $this->driver->wait()->until(WebDriverExpectedCondition::visibilityOf($this->driver->findElement($by)));

        return $this->driver->findElement($by);
    }

    /**
     * Wait for finished all ajax requests.
     *
     * @return mixed
     */
    protected function waitForAjaxRequestsFinished()
    {
        $this->driver->wait()->until(function(){
            return $this->driver->executeScript(
                'if (window.angular === undefined) {

                    return true
                } else {
                    return angular.element(\'[ng-app="mapcont"]\')
                            .injector().get(\'$http\').pendingRequests.length === 0}'
            );
        }, 'Ajax requests not finished before the timeout');
    }

    /**
     * @param string $type
     * @param string $url
     * @param array  $data
     *
     * @return mixed
     */
    protected function sendRequest($type, $url, $data = null)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $type);
        curl_setopt($ch, CURLOPT_COOKIE, 'PHPSESSID=' . $this->getCookie());
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        if ($type == 'POST' || $type == 'PATCH') {
            $headers = array('Content-Type: application/json');
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

            $response = curl_exec($ch);
            self::assertJson($response, 'Response is not valid json!');
            $entityId = json_decode($response, true)['id'];

            return $entityId;
        }
        return curl_exec($ch);
    }

    /**
     * Get cookie for current user.
     *
     * @return string
     */
    private function getCookie()
    {
        $cookies = $this->driver->manage()->getCookies();

        return $cookies[0]['value'];
    }
}
