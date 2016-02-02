<?php

namespace tests\ui\pages;

use Facebook\WebDriver\WebDriver;
use Facebook\WebDriver\WebDriverBy;
use Facebook\WebDriver\WebDriverKeys;
use tests\ui\util\BaseClass;
use tests\ui\util\SeleniumBase;

/**
 * PageObject for domainRegDeployer.
 */
class DomainRegDeployerPage extends BaseClass
{
    /**
     * @var WebDriverBy
     */
    private $login;
    private $password;
    private $submitBtn;
    private $searchField;
    private $slotBtn;
    private $editBtn;
    private $chooseBtn;
    private $treeSubjectsField;
    private $serialSubject;
    private $templateField;
    private $templateSearchField;
    private $saveBtn;
    private $copyFilesBtn;

    /**
     * @var string
     */
    public $archiveName;

    /**
     * @var WebDriver
     */
    protected $driver;

    /**
     * @var string
     */
    private $testSlot;

    /**
     * @param WebDriver $driver
     * @param string    $archiveName
     */
    public function __construct($driver, $archiveName)
    {
        parent::__construct($driver);
        $this->testSlot = $this->getValue('test_slot_url');
        $this->login = WebDriverBy::name('login');
        $this->password = WebDriverBy::name('password');
        $this->submitBtn = WebDriverBy::name('signin');
        $this->searchField = WebDriverBy::name('domain_id');
        $this->slotBtn = WebDriverBy::xpath('//*/li[@class=\'active\']/a[contains(.,' . $this->testSlot . ')]');
        $this->editBtn = WebDriverBy::xpath(
            '//*/a[@href=\'http://' . $this->testSlot . '\']/../..//a[@title=\'Редактировать\']'
        );
        $this->chooseBtn = WebDriverBy::xpath("//*[@role='button'][contains(text(), 'Выбрать')]");
        $this->treeSubjectsField = WebDriverBy::xpath(".//*[@id='jstree-search']");
        $this->serialSubject = WebDriverBy::xpath(".//*[@id='4111']/*/li[@id='4107']/a");
        $this->templateField = WebDriverBy::xpath(".//*[@id='select2-chosen-1']");
        $this->templateSearchField = WebDriverBy::xpath("//*[@id='s2id_autogen1_search']");
        $this->saveBtn = WebDriverBy::xpath("//*[@id='edit']");
        $this->copyFilesBtn = WebDriverBy::xpath(
            '//*/a[@href=\'http://' . $this->testSlot . ']/../../td/a[@data-original-title=\'Копировать файлы\']'
        );
        $this->archiveName = $archiveName;
    }

    /**
     * @param string $login
     *
     * @return $this
     */
    public function typeLogin($login)
    {
        $this->findElement($this->login)->sendKeys($login);

        return $this;
    }

    /**
     * @param string $password
     *
     * @return $this
     */
    public function typePassword($password)
    {
        $this->findElement($this->password)->sendKeys($password);

        return $this;
    }

    /**
     * @param string $login
     * @param string $password
     */
    public function login($login, $password)
    {
        $this->typeLogin($login);
        $this->typePassword($password);
        $this->findElement($this->submitBtn)->click();
    }

    /**
     * @param string $slot
     */
    public function clickSearch($slot)
    {
        $this->findElement($this->searchField)->sendKeys($slot)->sendKeys(WebDriverKeys::ENTER);
    }

    /**
     * Edit domain function.
     */
    public function editDomain()
    {
        $this->waitForAjaxFinished();
        $this->findElement($this->editBtn)->click();
    }

    /**
     * Find required subject (serials) and choose it.
     */
    public function chooseSubject()
    {
        $this->waitForAjaxFinished();
        $this->findElement($this->chooseBtn)->click();
        $this->waitForElementVisible($this->treeSubjectsField)->sendKeys('сериалы');
        $this->findElement($this->serialSubject)->click();
    }

    /**
     * Save template with required archive.
     */
    public function saveTemplate()
    {
        $this->driver->getKeyboard()
            ->sendKeys(WebDriverKeys::TAB)
            ->sendKeys(WebDriverKeys::ENTER);

        $this->driver->findElement($this->templateSearchField)
            ->click()
            ->sendKeys($this->archiveName)
            ->sendKeys(WebDriverKeys::ENTER);

        $this->findElement($this->saveBtn)->click();
    }

    /**
     * Copy template files.
     *
     * @throws \NoSuchElementException
     */
    public function copyFiles()
    {
        // Fix for accepting alert in phantomjs.
        // https://github.com/detro/ghostdriver/issues/20

        $this->driver->executeScript('window.confirm = function(){return true;}');
        $this->driver->executeScript(
            '$(\'#domain-list-page table a:contains(' . $this->testSlot . ')\')
            .parents(\'tr\').find("a[data-original-title$=\'Копировать файлы\']")
            .click();'
        );
    }
}
