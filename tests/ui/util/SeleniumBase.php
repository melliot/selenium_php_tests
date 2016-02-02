<?php

namespace tests\ui\util;

use Facebook\WebDriver\Remote\RemoteWebDriver;
use Facebook\WebDriver\Remote\WebDriverCapabilityType;
use Facebook\WebDriver\WebDriverDimension;

/**
 * Base class for selenium tests.
 */
class SeleniumBase extends BaseClass
{
    /**
     * @var RemoteWebDriver
     */
    protected $driver;

    /**
     * @var WebDriverCapabilityType array
     */
    public static $capabilities;

    /**
     * Initialize logger and driver.
     */
    public function setUp()
    {
        $url = 'http://localhost:4444/wd/hub';
        SeleniumBase::$capabilities = array(
            WebDriverCapabilityType::BROWSER_NAME => 'phantomjs',
        );
        $this->driver = RemoteWebDriver::create($url, SeleniumBase::$capabilities);
        $this->driver->manage()->window()->setSize(new WebDriverDimension(1920, 1080));
        $this->driver->manage()->timeouts()->implicitlyWait(15);
    }

    /**
     * Close driver after tests.
     */
    public function tearDown()
    {
        $this->driver->quit();
    }

    /**
     * @return string browserName
     */
    public static function getCurrentBrowser()
    {
        return SeleniumBase::$capabilities[WebDriverCapabilityType::BROWSER_NAME];
    }

    /**
     * @param string $name
     */
    public function takeScreenShot($name = 'test')
    {
        $path = __DIR__ . $this->getValue('path_for_screenshots') . $name . '.png';
        $this->driver->takeScreenshot($path);
    }
}
