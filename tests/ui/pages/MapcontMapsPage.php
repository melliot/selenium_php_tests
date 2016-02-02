<?php

namespace tests\ui\pages;

use Facebook\WebDriver\Remote\RemoteWebDriver;
use Facebook\WebDriver\WebDriverBy;

/**
 * PageObject for posting's preset.
 */
class MapcontMapsPage extends MapcontBasePage
{
    /**
     * @var WebDriverBy
     */
    private $addPostingPresetBtn;

    /**
     * @var WebDriverBy
     */
    private $nameField;

    /**
     * @var WebDriverBy
     */
    private $choosePreset;

    /**
     * @var WebDriverBy
     */
    private $preset;

    /**
     * @var WebDriverBy
     */
    private $okBtn;

    /**
     * @var WebDriverBy
     */
    private $addBlockBtn;

    /**
     * @var WebDriverBy
     */
    private $stopAfter;

    /**
     * @var WebDriverBy
     */
    private $initPosts;

    /**
     * @var WebDriverBy
     */
    private $saveBtn;

    /**
     * {@inheritDoc}
     */
    public function __construct(RemoteWebDriver $driver)
    {
        parent::__construct($driver);

        $this->addPostingPresetBtn = WebDriverBy::xpath("//button[@ng-click='add()']");
        $this->nameField = WebDriverBy::xpath("//input[@ng-model='map.name']");
        $this->choosePreset = WebDriverBy::xpath("//*[@id='select2-chosen-2']");
        $this->preset = WebDriverBy::xpath('//div[contains(text(),\'' . $this->getValue('preset_name') . '\')]');
        $this->okBtn = WebDriverBy::xpath("//button[@ng-click='exec(btn)']");
        $this->addBlockBtn = WebDriverBy::xpath("//button[@ng-click='map.addBlock()']");
        $this->stopAfter = WebDriverBy::xpath("//input[@ng-model='block.stop_after_posted']");
        $this->initPosts = WebDriverBy::xpath("//input[@ng-model='block.init_posts']");
        $this->saveBtn = WebDriverBy::xpath("//button[@ng-click='submit()']");
    }

    /**
     * @param string $postingName
     */
    public function createPostingsPreset($postingName)
    {
        $this->driver->get($this->url . '#/maps');
        $this->waitForElementClickable($this->addPostingPresetBtn)->click();
        $this->findElement($this->nameField)->sendKeys($postingName);
        $this->findElement($this->choosePreset)->click();
        $this->findElement($this->preset)->click();
        $this->findElement($this->okBtn)->click();
    }

    /**
     * Set configuration for created project.
     */
    public function setConfigurationAndSavePostingPreset()
    {
        $this->waitForElementClickable($this->addBlockBtn)->click();
        $this->findElement($this->stopAfter)->clear()->sendKeys(1000);
        $this->findElement($this->initPosts)->clear()->sendKeys(1000);
        $this->findElement($this->saveBtn)->click();
    }
}
