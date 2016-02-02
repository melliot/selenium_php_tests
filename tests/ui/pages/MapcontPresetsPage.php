<?php

namespace tests\ui\pages;

use Facebook\WebDriver\Remote\RemoteWebDriver;
use Facebook\WebDriver\WebDriverBy;

/**
 * PageObject for mapcont's presets.
 */
class MapcontPresetsPage extends MapcontBasePage
{
    /**
     * @var WebDriverBy
     */
    private $createPresetBtn;
    private $presetNameField;
    private $submitCreatePresetBtn;
    private $addOriginBtnToPresetBtn;
    private $findOriginField;
    private $addOriginWithTagsBtn;
    private $tagMetagenList;
    private $tagMetagen;
    private $tagConstructorList;
    private $tagConstructor;
    private $tagPostingTypeList;
    private $tagPosting;
    private $tagPlaceholdersBtn;
    private $tagPlaceholder;
    private $tagContentTypeList;
    private $tagContentType;
    private $elementsPreferencesBtn;
    private $elementsMetagenList;
    private $elementsMetagen;
    private $elementsConstructorList;
    private $elementConstructor;
    private $elementPostingTypeList;
    private $elementPosingType;
    private $elementPlaceholdersBtn;
    private $elementPlaceholder;
    private $elementContentTypeList;
    private $elementContentType;
    private $saveBtn;
    private $createMenuBtn;
    private $menuNameField;
    private $okBtn;
    private $tagAnime;
    private $copyTagsToMenuBtn;
    private $cartoon;
    private $horror;

    /**
     * @var RemoteWebDriver
     */
    protected $driver;

    /**
     * {@inheritDoc}
     */
    public function __construct(RemoteWebDriver $driver)
    {
        parent::__construct($driver);

        $presetName = $this->getValue('preset_name');
        $this->createPresetBtn = WebDriverBy::xpath("//button[contains(.,'Создать пресет')]");
        $this->presetNameField = WebDriverBy::xpath("//*/input[@ng-model='preset.name']");
        $this->submitCreatePresetBtn = WebDriverBy::xpath("//*/button[@ng-click='exec(btn)']");

        $this->addOriginBtnToPresetBtn = WebDriverBy::xpath("//*//button[@title='Добавить теги и элементы']");
        $this->findOriginField = WebDriverBy::xpath("//th[@data-title-text='Название']/*//input[@name='type.name']");

        $this->addOriginWithTagsBtn = WebDriverBy::xpath(
            "//td[contains(., 'qa_origin')]/..//button[@title='Добавить ориджины вместе c тегами']"
        );
        $this->tagMetagenList = WebDriverBy::xpath(
            "//div[@ng-show=\"tab == 'tag'\"]/*//select[@ng-model=\"model.metagenPresetId\"]"
        );
        $this->tagMetagen = WebDriverBy::xpath(
            "//div[@ng-show=\"tab == 'tag'\"]/*//option[@label='TAGS']"
        );
        $this->tagConstructorList = WebDriverBy::xpath(
            "//div[@ng-show=\"tab == 'tag'\"]/*//select[@ng-model=\"model.pageBuilder\"]"
        );
        $this->tagConstructor = WebDriverBy::xpath(
            "//div[@ng-show=\"tab == 'tag'\"]//option[contains(., 'preset1')]"
        );
        $this->tagPostingTypeList = WebDriverBy::xpath(
            "//div[@ng-show=\"tab == 'tag'\"]/*//select[@ng-model=\"model.postingType\"]"
        );
        $this->tagPosting = WebDriverBy::xpath(
            "//div[@ng-show=\"tab == 'tag'\"]//option[.='Постить без текста']"
        );
        $this->tagPlaceholdersBtn = WebDriverBy::xpath(
            "//div[@ng-show=\"tab == 'tag'\"]//span[contains(text(), 'Плейсхолдеры')]"
        );
        $this->tagPlaceholder = WebDriverBy::xpath("//div[@ng-show=\"tab == 'tag'\"]/*//a[.='news_4']");
        $this->tagContentTypeList = WebDriverBy::xpath(
            "//div[@ng-show=\"tab == 'tag'\"]//span[contains(text(), 'Типы контента')]"
        );
        $this->tagContentType = WebDriverBy::xpath("//div[@ng-show=\"tab == 'tag'\"]//a[contains(., 'от 3 лица')]");
        $this->elementsPreferencesBtn = WebDriverBy::xpath("//button[contains(text(), 'Настройки для элементов')]");
        $this->elementsMetagenList = WebDriverBy::xpath(
            "//div[@ng-show=\"tab == 'element'\"]/*//select[@ng-model=\"model.metagenPresetId\"]"
        );
        $this->elementsMetagen= WebDriverBy::xpath(
            "//div[@ng-show=\"tab == 'element'\"]/*//option[@label='SERIAL Сериалы']"
        );
        $this->elementsConstructorList = WebDriverBy::xpath(
            "//div[@ng-show=\"tab == 'element'\"]//select[@ng-model=\"model.pageBuilder\"]"
        );
        $this->elementConstructor = WebDriverBy::xpath(
            "//div[@ng-show=\"tab == 'element'\"]//option[contains(., 'preset1')]"
        );
        $this->elementPostingTypeList = WebDriverBy::xpath(
            "//div[@ng-show=\"tab == 'element'\"]//select[@ng-model=\"model.postingType\"]"
        );
        $this->elementPosingType = WebDriverBy::xpath(
            "//div[@ng-show=\"tab == 'element'\"]//option[.='Постить без текста']"
        );
        $this->elementPlaceholdersBtn = WebDriverBy::xpath(
            "//div[@ng-show=\"tab == 'element'\"]//span[contains(text(), 'Плейсхолдеры')]"
        );
        $this->elementPlaceholder = WebDriverBy::xpath("//div[@ng-show=\"tab == 'element'\"]/*//a[.='news_4']");
        $this->elementContentTypeList = WebDriverBy::xpath(
            "//div[@ng-show=\"tab == 'element'\"]//span[contains(text(), 'Типы контента')]"
        );
        $this->elementContentType = WebDriverBy::xpath(
            "//div[@ng-show=\"tab == 'element'\"]//a[contains(., 'от 3 лица')]"
        );
        $this->saveBtn = WebDriverBy::xpath("//button[.='Сохранить']");
        $this->createMenuBtn = WebDriverBy::xpath("//button[@ng-click='createMenu()']");
        $this->menuNameField = WebDriverBy::xpath("//*[@id='menuName']");
        $this->tagAnime = WebDriverBy::xpath("//span[contains(text(), 'аниме')]");
        $this->copyTagsToMenuBtn = WebDriverBy::xpath("//div[@data-ng-click='multiCopy()']");
        $this->okBtn = WebDriverBy::xpath("//button[@ng-click='exec(btn)']");
        $this->cartoon = WebDriverBy::xpath("//span[contains(text(), 'мультфильмы')]");
        $this->horror = WebDriverBy::xpath("//span[contains(text(), 'ужасы')]");
        $this->deleteBtn = WebDriverBy::xpath(
            '//a[contains(text(),\'' . $presetName . '\')]/..//button[@title=\'Переместить в корзину\']'
        );
        $this->trash = WebDriverBy::xpath("//button[@cb='showTrash']");
        $this->deleteFromTrashBtn = WebDriverBy::xpath(
            '//a[contains(text(), \'' . $presetName . '\')]/..//button[@title=\'Удалить из корзины\']'
        );
        $this->confirmDeletePreset = WebDriverBy::xpath("//button[@ng-click='okBtnClick()']");
    }

    /**
     * Create preset with require name.
     *
     * @param string $name
     */
    public function createPreset($name)
    {
        $this->findElement($this->createPresetBtn)->click();
        $this->findElement($this->presetNameField)->sendKeys($name);
        $this->findElement($this->submitCreatePresetBtn)->click();
    }

    /**
     * Import origin into preset.
     *
     * @param string $originName
     */
    public function importOrigin($originName)
    {
        $this->waitForElementClickable($this->addOriginBtnToPresetBtn)->click();
        $this->findElement($this->findOriginField)->sendKeys($originName);
        $this->findElement($this->addOriginWithTagsBtn)->click();
    }

    /**
     * Set settings for tags.
     */
    public function setConfigurationForTags()
    {
        $this->findElement($this->tagMetagenList)->click();
        $this->findElement($this->tagMetagen)->click();

        $this->findElement($this->tagConstructorList)->click();
        $this->waitForElementClickable($this->tagConstructor)->click();

        $this->findElement($this->tagPostingTypeList)->click();
        $this->findElement($this->tagPosting)->click();

        $this->findElement($this->tagPlaceholdersBtn)->click();
        $this->waitForElementClickable($this->tagPlaceholder)->click();

        $this->findElement($this->tagContentTypeList)->click();
        $this->findElement($this->tagContentType)->click();
    }

    /**
     * Set setting for elements.
     */
    public function setConfigurationForElements()
    {
        $this->findElement($this->elementsPreferencesBtn)->click();

        $this->findElement($this->elementsMetagenList)->click();
        $this->findElement($this->elementsMetagen)->click();

        $this->findElement($this->elementsConstructorList)->click();
        $this->findElement($this->elementConstructor)->click();

        $this->findElement($this->elementPostingTypeList)->click();
        $this->findElement($this->elementPosingType)->click();

        $this->findElement($this->elementPlaceholdersBtn)->click();
        $this->findElement($this->elementPlaceholder)->click();

        $this->findElement($this->elementContentTypeList)->click();
        $this->findElement($this->elementContentType)->click();

        $this->findElement($this->saveBtn)->click();
    }

    /**
     * Create menu in preset.
     *
     * @param string $name
     */
    public function createMenu($name)
    {
        $this->waitForElementClickable($this->createMenuBtn)->click();
        $this->findElement($this->menuNameField)->click()->sendKeys($name);
        $this->findElement($this->okBtn)->click();
    }

    /**
     * Add one tag to menu.
     */
    public function addTagToMenu()
    {
        $this->findElement($this->tagAnime)->click();
        $this->findElement($this->copyTagsToMenuBtn)->click();
        $this->findElement($this->okBtn)->click();
    }

    /**
     * Add two tags to menu.
     */
    public function addCoupleTagsToMenu()
    {
        // Unselect anime tag.
        $this->findElement($this->tagAnime)->click();
        $this->findElement($this->cartoon)->click();
        $this->findElement($this->horror)->click();
        $this->findElement($this->copyTagsToMenuBtn)->click();
        $this->findElement($this->okBtn)->click();
    }

    /**
     * Remove preset through ui.
     */
    public function deletePreset()
    {
        $this->goToPresets();
        $this->waitForElementClickable($this->deleteBtn)->click();
        $this->waitForElementClickable($this->confirmDeletePreset)->click();
        $this->findElement($this->trash)->click();
        $this->waitForElementClickable($this->deleteFromTrashBtn)->click();
        $this->findElement($this->confirmDeletePreset)->click();
    }

    /**
     * @param int $presetId
     */
    public function goToPresets($presetId = null)
    {
        $this->driver->get($this->url . '#/presets/' . $presetId);
    }
}
