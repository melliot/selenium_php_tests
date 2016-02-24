<?php

namespace tests\ui;

use Facebook\WebDriver\WebDriverBy;
use tests\ui\pages\MapcontSitesPage;
use tests\ui\util\SeleniumBase;

/**
 * Test that check actions with task.
 */
class TaskActionsTest extends SeleniumBase
{
    /**
     * @var MapcontSitesPage
     */
    private $mapcont;

    /**
     * @var int
     */
    private $taskId;

    /**
     * @var string
     */
    private $testSlot;

    /**
     * @var string
     */
    private $postingName;

    /**
     * {@inheritDoc}
     */
    public function setUp()
    {
        parent::setUp();
        $this->mapcont = new MapcontSitesPage($this->driver);
        $this->mapcont->login();
        $this->testSlot = $this->getValue('test_slot_url');
        $this->postingName = $this->mapcont->getRandName();
        $this->mapcont->createPresetWithMenu($this->postingName);
    }

    /**
     * {@inheritDoc}
     */
    public function tearDown()
    {
        if ($this->taskId) {
            $this->mapcont->sendRequest('DELETE', $this->mapcont->url . 'tasks/' . $this->taskId . '.json');
        }
        if ($this->mapcont->getPresetId()) {
            $this->mapcont->deletePresetRequest($this->mapcont->getPresetId());
        }
        parent::tearDown();
    }

    /**
     * Check create project.
     */
    public function testCreateProject()
    {
        $taskIdPath = WebDriverBy::xpath(
            '//td/a[contains(text(), \'' . $this->testSlot . '\')]/../../td[@data-title-text=\'ID\']'
        );
        $this->mapcont->addProject($this->testSlot, $this->postingName);
        $this->waitForAjaxRequestsFinished();
        $elements = $this->driver->findElements(WebDriverBy::xpath("//td[@sortable=\"'domain.name'\"]"));

        $this->taskId = $this->waitForElementVisible($taskIdPath)->getText();
        $domains = [];
        foreach ($elements as $element) {
            array_push($domains, $element->getText());
        }
        self::assertContains($this->testSlot, $domains, 'Task has not been created!');

        $startPostingBtn = WebDriverBy::xpath(
            '//td/a[contains(text(),' . $this->testSlot . ')]/../../td/../*//a[@title=\'Запустить постинг\']'
        );
        self::assertTrue($this->driver->findElement($startPostingBtn)->isDisplayed(),
            'Task has not been started posting!'
        );
    }
}
