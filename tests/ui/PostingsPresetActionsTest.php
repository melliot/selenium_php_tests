<?php

namespace tests\ui;

use Facebook\WebDriver\WebDriverBy;
use tests\ui\pages\MapcontMapsPage;
use tests\ui\util\SeleniumBase;

/**
 * Test that check actions with posting's preset.
 */
class PostingsPresetActionsTest extends SeleniumBase
{
    /**
     * @var MapcontMapsPage
     */
    private $mapcont;

    /**
     * @var int
     */
    private $presetId;

    /**
     * {@inheritDoc}
     */
    public function setUp()
    {
        parent::setUp();
        $this->mapcont = new MapcontMapsPage($this->driver);
        $this->mapcont->login();
    }

    /**
     * {@inheritDoc}
     */
    public function tearDown()
    {
        if ($this->presetId) {
            $this->mapcont->deletePresetRequest($this->presetId);
        }
        parent::tearDown();
    }

    /**
     * Check create posing's preset.
     */
    public function testCreatePostingsPreset()
    {
        $h3Path = WebDriverBy::xpath("//h3[@class='ng-binding']");
        $this->presetId = $this->mapcont->createPresetWithOriginRequest($this->getValue('origin'));
        $this->mapcont->createPostingsPreset($this->getValue('posting_name'));

        self::assertEquals('Пресет постинга', $this->waitForElementVisible($h3Path)->getText(),
            'Posting\'s preset has not been created!'
        );
    }

    /**
     * Check set configuration for posting's preset.
     */
    public function testSetConfiguration()
    {
        $alertPath = WebDriverBy::xpath("//div[@role='alert']");
        $this->presetId = $this->mapcont->createPresetWithOriginRequest($this->getValue('origin'));
        $data = [
            'name' => $this->getValue('posting_name'),
            'preset' => $this->presetId,
        ];
        $postingPresetId = $this->sendRequest('POST', $this->mapcont->url . 'presets/maps.json', $data);
        $this->driver->get($this->mapcont->url . '#/maps/' . $postingPresetId);
        $this->mapcont->setConfigurationAndSavePostingPreset();

        self::assertTrue($this->driver->findElement($alertPath)->isDisplayed(),
            'Posting\'s preset has not been added.'
        );
    }
}
