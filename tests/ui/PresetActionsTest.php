<?php

namespace tests\ui;

use Facebook\WebDriver\WebDriverBy;
use tests\ui\pages\MapcontPresetsPage;
use tests\ui\util\SeleniumBase;

/**
 * Test that check actions with preset.
 */
class PresetActionsTest extends SeleniumBase
{
    /**
     * @var MapcontPresetsPage
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
        $this->mapcont = new MapcontPresetsPage($this->driver);
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
     * Check creating preset with menu.
     *
     * @throws \Exception
     */
    public function testCreateAndDeletePreset()
    {
        $h3Path = WebDriverBy::xpath('//h3[@class="ng-binding"]');
        $name = $this->mapcont->getRandName();
        $this->mapcont->createPreset($name);
        self::assertEquals('Пресет контента', $this->waitForElementVisible($h3Path)->getText(),
            'Preset has not been created!'
        );
        $this->mapcont->deletePreset($name);
    }

    /**
     * Check import origin into preset and set configurations.
     */
    public function testImportOriginIntoPreset()
    {
        $tagsPath = WebDriverBy::xpath("//*[@id='tags-list']");
        $this->presetId = $this->mapcont->createPresetRequest();
        $this->mapcont->goToPresets($this->presetId);

        $this->mapcont->importOrigin($this->getValue('origin'));

        $this->mapcont->setConfigurationForTags();
        $this->mapcont->setConfigurationForElements();
        $this->waitForAjaxRequestsFinished();
        $tags = $this->driver->findElement($tagsPath);
        self::assertNotEmpty($tags->getText(), 'Origin has not been imported into preset!');
    }

    /**
     * Check create menu, add elements to menu.
     *
     * @throws \Exception
     */
    public function testCreateMenuAndAddOriginInMenu()
    {
        $menuNamePath = WebDriverBy::xpath("//td[contains(text(), 'Название меню:')]");
        $menuLocationPath = WebDriverBy::xpath("//td[contains(text(), 'Расположение:')]");
        $menuElementsPath = WebDriverBy::xpath("//*[@id='menu-items-root']");
        $this->presetId = $this->mapcont->createPresetWithOriginRequest($this->getValue('origin'));
        // Creating menu.
        $this->mapcont->goToPresets($this->presetId);
        $this->mapcont->createMenu($this->getValue('menu_name'));
        self::assertTrue($this->driver->findElement($menuNamePath)->isDisplayed() &&
            $this->driver->findElement($menuLocationPath)->isDisplayed(),
            'Menu has not been created!'
        );

        $this->mapcont->addTagsToMenu();
        $menuTags = $this->waitForElementVisible($menuElementsPath)->getText();
        self::assertContains("мультфильмы\nприключенческие\nсемейные", $menuTags, 'Tags has not been added to menu!');
    }
}
