<?php

namespace tests\ui\pages;

use Facebook\WebDriver\Remote\RemoteWebDriver;
use Facebook\WebDriver\WebDriverBy;
use tests\ui\util\BaseClass;

/**
 * Base pageObject for mapcont.
 */
class MapcontBasePage extends BaseClass
{
    /**
     * @var WebDriverBy
     */
    private $loginField;

    /**
     * @var WebDriverBy
     */
    private $passwordField;

    /**
     * @var WebDriverBy
     */
    private $loginBtn;

    /**
     * @var RemoteWebDriver
     */
    protected $driver;

    /**
     * @var string
     */
    public $url;

    /**
     * {@inheritDoc}
     */
    public function __construct($driver)
    {
        parent::__construct($driver);
        $this->loginField = WebDriverBy::xpath("//*[@id='login-email']");
        $this->passwordField = WebDriverBy::xpath("//*[@id='login-password']");
        $this->loginBtn = WebDriverBy::xpath("//*[@id='form-login']/*/*/button[@type='submit']");
        $this->url = $this->getValue('mapcont_url');
    }

    /**
     * @param string $username
     *
     * @return $this
     */
    public function typeLogin($username)
    {
        $this->driver->findElement($this->loginField)->sendKeys($username);

        return $this;
    }

    /**
     * @param string $password
     *
     * @return $this
     */
    public function typePassword($password)
    {
        $this->driver->findElement($this->passwordField)->sendKeys($password);

        return $this;
    }

    /**
     * @param string $username
     * @param string $password
     */
    public function loginAs($username, $password)
    {
        $this->typeLogin($username);
        $this->typePassword($password);
        $this->driver->findElement($this->loginBtn)->submit();
    }

    /**
     * Create preset through api.
     *
     * @return int
     *
     * @throws \Exception
     */
    public function createPresetRequest()
    {
        $data = ['name' => $this->getRandName()];

        return $this->sendRequest('POST', $this->url . 'presets.json', $data);
    }

    /**
     * Add origin to preset through api.
     *
     * @param int    $presetId
     * @param string $originName
     *
     * @return int
     */
    private function addOriginToPresetRequest($presetId, $originName)
    {
        $this->sendRequest('GET',
            $this->url . 'api/1/presets/' . $presetId .
            '/origins/add_tags.json?type=' . $originName
        );
    }

    /**
     * Create preset with origin through api.
     *
     * @param string $originName
     *
     * @return int
     */
    public function createPresetWithOriginRequest($originName)
    {
        $presetId = $this->createPresetRequest();
        $this->addOriginToPresetRequest($presetId, $originName);

        return $presetId;
    }

    /**
     * Delete preset via api.
     *
     * @param int $presetId
     */
    public function deletePresetRequest($presetId)
    {
        $this->sendRequest('DELETE', $this->url . 'presets/' . $presetId . '.json?full=1');
    }

    /**
     * Login into content-mapper.
     */
    public function login()
    {
        $this->driver->get($this->url);
        $user = $this->getValue('mapcont_login');
        $pass = $this->getValue('mapcont_password');
        $this->loginAs($user, $pass);
    }

    /**
     * @return int domainId
     */
    public function getDomainId()
    {
        $url = $this->getValue('mapcont_url')
            . 'dac/user_domain.json?&filters[0][field]=domains.name&filters[0][value]=' .
            $this->getValue('test_slot_url');
        $response = $this->sendRequest('GET', $url);
        $response = json_decode($response, true);

        return $response['items'][0]['id'];
    }

    /**
     * Generate random name for preset.
     *
     * @return String
     */
    public function getRandName()
    {
        $name = $this->getValue('preset_name') . uniqid();

        return $name;
    }
}
