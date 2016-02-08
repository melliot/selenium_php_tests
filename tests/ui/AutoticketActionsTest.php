<?php

namespace tests\ui;

use tests\ui\pages\AutoticketPage;
use tests\ui\util\SeleniumBase;

/**
 * Check actions with autoticket's preset.
 */
class AutoticketActionsTest extends SeleniumBase
{
    /**
     * @var AutoticketPage
     */
    private $autoticket;
    
    public function setUp()
    {
        parent::setUp();
        $this->autoticket = new AutoticketPage($this->driver);
        $this->autoticket->login();

        $this->driver->get($this->getValue('mapcont_url') . '#/config/journalist/topics/791');
    }

    /**
     * Check name's validation.
     */
    public function testCheckValidationOnName()
    {
        $this->findElement($this->autoticket->addPresetBtn)->click();
        $this->waitForAjaxRequestsFinished();
        $this->findElement($this->autoticket->descriptionInput)->sendKeys('automated');
        $this->findElement($this->autoticket->h1)->click();
        $this->findElement($this->autoticket->headerInput)->sendKeys('automated');
        $this->findElement($this->autoticket->newsCheckBox)->click();
        $this->findElement($this->autoticket->nestingLevelCheckBox)->click();
        $this->findElement($this->autoticket->saveBtn)->click();

        $text = $this->waitForElementVisible($this->autoticket->alertLocator)->getText();
        self::assertEquals('Необходимо заполнить название пресета', $text,
            'Name validation failed!'
        );
    }

    /**
     * Check description's validation.
     */
    public function testCheckValidationOnDescription()
    {
        $this->findElement($this->autoticket->addPresetBtn)->click();
        $this->findElement($this->autoticket->nameInput)->sendKeys('automated');
        $this->waitForAjaxRequestsFinished();
        $this->findElement($this->autoticket->h1)->click();
        $this->findElement($this->autoticket->headerInput)->sendKeys('automated');
        $this->findElement($this->autoticket->newsCheckBox)->click();
        $this->findElement($this->autoticket->nestingLevelCheckBox)->click();
        $this->findElement($this->autoticket->saveBtn)->click();

        $text = $this->waitForElementVisible($this->autoticket->alertLocator)->getText();
        self::assertEquals('Необходимо заполнить описание пресета', $text,
            'Description validation failed!'
        );
    }

    /**
     * Check header's validation.
     */
    public function testCheckValidationOnH1()
    {
        $this->findElement($this->autoticket->addPresetBtn)->click();
        $this->findElement($this->autoticket->nameInput)->sendKeys('automated');
        $this->waitForAjaxRequestsFinished();
        $this->findElement($this->autoticket->descriptionInput)->sendKeys('automated');
        $this->findElement($this->autoticket->newsCheckBox)->click();
        $this->findElement($this->autoticket->nestingLevelCheckBox)->click();
        $this->findElement($this->autoticket->saveBtn)->click();

        $text = $this->waitForElementVisible($this->autoticket->alertLocator)->getText();
        self::assertEquals('Необходимо заполнить Заголовок H1 блока первого уровня', $text,
            'Header h1 validation failed!'
        );
    }

    /**
     * Check create preset.
     */
    public function testCheckCreatePreset()
    {
        $this->findElement($this->autoticket->addPresetBtn)->click();
        $this->findElement($this->autoticket->nameInput)->sendKeys('automated');
        $this->waitForAjaxRequestsFinished();
        $this->findElement($this->autoticket->descriptionInput)->sendKeys('automated');
        $this->findElement($this->autoticket->h1)->click();
        $this->findElement($this->autoticket->headerInput)->sendKeys('automated');
        $this->findElement($this->autoticket->newsCheckBox)->click();
        $this->findElement($this->autoticket->nestingLevelCheckBox)->click();
        $this->findElement($this->autoticket->saveBtn)->click();

        $this->findElement($this->autoticket->firstLevelPresetsBtn)->click();
        self::assertTrue($this->findElement($this->autoticket->editPreset)->isDisplayed());
    }
}