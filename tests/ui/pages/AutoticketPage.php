<?php

namespace tests\ui\pages;

use Facebook\WebDriver\WebDriverBy;

/**
 * PageObject for autotickets.
 */
class AutoticketPage extends MapcontBasePage
{
    /**
     * @var WebDriverBy
     */
    public $addPresetBtn;

    /**
     * @var WebDriverBy
     */
    public $nameInput;

    /**
     * @var WebDriverBy
     */
    public $descriptionInput;

    /**
     * @var WebDriverBy
     */
    public $h1;

    /**
     * @var WebDriverBy
     */
    public $headerInput;

    /**
     * @var WebDriverBy
     */
    public $newsCheckBox;

    /**
     * @var WebDriverBy
     */
    public $nestingLevelCheckBox;

    /**
     * @var WebDriverBy
     */
    public $saveBtn;

    /**
     * @var WebDriverBy
     */
    public $firstLevelPresetsBtn;

    /**
     * @var WebDriverBy
     */
    public $editPreset;

    /**
     * @var WebDriverBy
     */
    public $alertLocator;

    public function __construct($driver)
    {
        parent::__construct($driver);

        $this->addPresetBtn = WebDriverBy::xpath('//a[@href=\'#/config/journalist/topics/791/preset/create\']');
        $this->nameInput = WebDriverBy::xpath('//input[@ng-model=\'object.name\']');
        $this->descriptionInput = WebDriverBy::xpath('//*[@id=\'config-description\']');
        $this->h1 = WebDriverBy::xpath('//a[text()=\'Заголовок H1\']');
        $this->headerInput = WebDriverBy::xpath('//input[@placeholder=\'Заголовок\']');
        $this->newsCheckBox = WebDriverBy::xpath('//label[contains(.,\'news_1\')]/input[@type=\'checkbox\']');
        $this->nestingLevelCheckBox = WebDriverBy::xpath(
            '//label[contains(.,\'Все\')]/input[@ng-checked=\'allCheckboxesMet(object.nesting_levels)\']'
        );
        $this->saveBtn = WebDriverBy::xpath('//button[@type=\'submit\']');
        $this->firstLevelPresetsBtn = WebDriverBy::xpath(
            '//a[@class=\'btn btn-xs\' and @href=\'#config/journalist/topics/791/level/1\']'
        );
        $this->editPreset = WebDriverBy::xpath(
            '//a[text()=\'automated\']/../..//span[@class=\'glyphicon glyphicon-edit\']'
        );
        $this->alertLocator = WebDriverBy::xpath('//div[@role=\'alert\']/*//li');
    }
}