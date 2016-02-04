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
    private $presetId;

    /**
     * @var int
     */
    private $taskId;

    /**
     * @var int
     */
    private $postingPresetId;

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
        $this->createPresetWithMenu($this->postingName);
    }

    /**
     * {@inheritDoc}
     */
    public function tearDown()
    {
        if ($this->taskId) {
            $this->mapcont->sendRequest('DELETE', $this->mapcont->url . 'tasks/' . $this->taskId . '.json');
        }
        if ($this->presetId) {
            $this->mapcont->deletePresetRequest($this->presetId);
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

    /**
     * Create preset.
     *
     * @param String $postingName
     *
     * @return int
     *
     * @throws \Exception
     */
    private function createPresetWithMenu($postingName)
    {
        $this->presetId = $this->mapcont->createPresetWithOriginRequest($this->getValue('origin'));
        $originTags = $this->sendRequest(
            'GET', $this->mapcont->url . 'api/1/menus/presets/' . $this->presetId . '/tags.json'
        );
        $originTags = json_decode($originTags, true);

        $tagNames = ['ужасы'];
        $tagIds = [];

        foreach ($tagNames as $name) {
            foreach ($originTags['tags'] as $tag) {
                if ($tag['names'][0]['name'] == $name) {
                    array_push($tagIds, $tag['id']);
                }
            }
        }
        $menu = ['preset' => $this->presetId, 'region' => 1, 'name' => $this->getValue('menu_name')];

        $this->sendRequest('POST', $this->mapcont->url . 'api/1/menus.json', $menu);

        $data = [
            'name' => $postingName,
            'preset' => $this->presetId,
        ];
        $this->postingPresetId = $this->sendRequest('POST', $this->mapcont->url . 'presets/maps.json', $data);

        $data = [
            'name' => $postingName,
            'is_removed' => false,
            'max_tickets' => rand(1, 50),
            'tickets_per_day_limit' => null,
            'preset' => $this->presetId,
            'is_manual' => 1,
            'blocks' => [[
                'from_posts_day' => 1,
                'to_posts_day' => 1,
                'post_days' => 1,
                'stop_after_posted' => 1000,
                'init_posts' => 1000,
            ]]
        ];
        $this->sendRequest('PATCH', $this->mapcont->url . 'presets/maps/' . $this->postingPresetId . '.json', $data);
    }
}
