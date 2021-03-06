<?php

namespace tests\ui\pages;

use Facebook\WebDriver\WebDriverBy;
use Facebook\WebDriver\WebDriverSelect;

/**
 * PageObject for posting's presets.
 */
class MapcontSitesPage extends MapcontBasePage
{
    /**
     * @var WebDriverBy
     */
    private $addProjectBtn;

    /**
     * @var WebDriverBy
     */
    private $chooseDomainBtn;

    /**
     * @var WebDriverBy
     */
    private $choosePresetBtn;

    /**
     * @var WebDriverBy
     */
    private $postingPreset;

    /**
     * @var WebDriverBy
     */
    private $submitBtn;

    /**
     * @var WebDriverBy
     */
    private $cmsTypeBtn;

    /**
     * @var WebDriverBy
     */
    private $startPostingBtn;

    /**
     * @var WebDriverBy
     */
    private $deleteProjectBtn;

    /**
     * @var WebDriverBy
     */
    private $okBtn;

    /**
     * {@inheritDoc}
     */
    public function __construct($driver)
    {
        parent::__construct($driver);
        $this->addProjectBtn = WebDriverBy::xpath("//a[@href='#task/create']");
        $this->chooseDomainBtn = WebDriverBy::xpath("//span[contains(text(),'Выбор домена')]");
        $this->choosePresetBtn = WebDriverBy::xpath("//span[contains(text(),'Выбор пресета')]");
        $this->submitBtn = WebDriverBy::xpath("//button[@type='submit']");
        $this->cmsTypeBtn = WebDriverBy::xpath("//select[@ng-model='siteDefaultQueryParams.cms_type']");
        $this->startPostingBtn = WebDriverBy::xpath(
            '//td/a[contains(text(),\''
            . $this->getValue('test_slot_url') .
            '\')]/../../td/../*//a[@ng-click=\'setPostingAllowed(task, true)\']');
        $this->deleteProjectBtn =
            WebDriverBy::xpath(
                '//td/a[contains(text(),\'' .
                $this->getValue('test_slot_url') .
                '\')]/../../td/../*//a[@title=\'Удалить проект\']'
        );
        $this->okBtn = WebDriverBy::xpath('//button[@ng-click="okBtnClick()"]');
    }

    /**
     * @param string $testSlot
     * @param string $postingName
     */
    public function addProject($testSlot, $postingName)
    {
        $this->postingPreset = WebDriverBy::xpath(
            '//div[contains(text(),\'' . $postingName . '\')]'
        );
        $this->waitForElementClickable($this->addProjectBtn)->click();
        $element = new WebDriverSelect($this->driver->findElement($this->cmsTypeBtn));
        $element->selectByVisibleText($this->getValue('cms_type'));
        $this->findElement($this->chooseDomainBtn)->click();
        $this->findElement(WebDriverBy::xpath('//div[contains(text(), \'' . $testSlot . '\')]'))->click();
        $this->findElement($this->choosePresetBtn)->click();
        $this->findElement($this->postingPreset)->click();
        $this->findElement($this->submitBtn)->submit();
    }

    /**
     * Click on btn start posting.
     */
    public function startPosting()
    {
        $btn = WebDriverBy::xpath(
            '//td/a[contains(text(),' . $this->getValue('test_slot_url')
            . ')]/../../td/../*//a[@title=\'Запустить постинг\']'
        );
        $this->waitForElementClickable($btn)->click();
    }

    /**
     * Delete project.
     */
    public function deleteProject()
    {
        $this->driver->get($this->url . '#/sites');
        if ($this->isElementPresent($this->deleteProjectBtn)) {
            $this->waitForElementClickable($this->deleteProjectBtn)->click();
            $this->waitForElementClickable($this->okBtn)->click();
        } else {
            return;
        }
    }
}
