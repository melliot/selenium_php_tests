<?php

namespace tests\ui\pages;

use Facebook\WebDriver\Remote\RemoteWebDriver;
use Facebook\WebDriver\WebDriverBy;
use tests\ui\util\BaseClass;
use tests\ui\util\SeleniumBase;

/**
 * PageObject for sitebuilder.
 */
class SiteBuilderPage extends BaseClass
{
    /**
     * @var WebDriverBy
     */
    private $loginBox;
    private $passwordTextBox;
    private $submitBtn;
    private $locales;
    private $russianLocale;
    private $submitLocaleBtn;
    private $serial;
    private $subjects;
    private $submitSubjectBtn;
    private $createTemplateBtn;
    private $sendToDeployBtn;
    private $nameOfArchive;
    private $templateList;
    private $template;

    /**
     * @var RemoteWebDriver
     */
    protected $driver;

    /**
     * {@inheritDoc}
     */
    public function __construct($driver)
    {
        parent::__construct($driver);
        $this->loginBox = WebDriverBy::name('s10authname');
        $this->passwordTextBox = WebDriverBy::name('s10authpass');
        $this->submitBtn = WebDriverBy::xpath("//*[@type='submit']");
        $this->locales = WebDriverBy::xpath("//*[@id='table']//button[@class='btn dropdown-toggle']");
        $this->russianLocale = WebDriverBy::xpath("//*[@value='ru']");
        $this->submitLocaleBtn = WebDriverBy::xpath("//*[@id='localeslist']/*/input");
        $this->serial = WebDriverBy::xpath("//*[@id='subjslist']/*/input[@sid=4107]");
        $this->subjects = WebDriverBy::id('subjname');
        $this->submitSubjectBtn = WebDriverBy::xpath("//*[@id='subjlist']/*/input");
        $this->createTemplateBtn = WebDriverBy::id('btnGenTemplate');
        $this->sendToDeployBtn = WebDriverBy::xpath("//*/a[@class='todeploy']");
        $this->nameOfArchive = WebDriverBy::xpath("//*[@id='copyPath']");
        $this->templateList = WebDriverBy::xpath("//*[@id='selTemplate']");
        $this->template = WebDriverBy::xpath("//*[@id='selTemplate']/*[@value='serialy-tut']");

        $this->driver = $driver;
    }

    /**
     * @param string $username
     *
     * @return $this
     */
    public function typeLogin($username)
    {
        $this->findElement($this->loginBox)->sendKeys($username);

        return $this;
    }

    /**
     * @param string $password
     *
     * @return $this
     */
    public function typePassword($password)
    {
        $this->findElement($this->passwordTextBox)->sendKeys($password);

        return $this;
    }

    /**
     * @param string $username
     * @param string $password
     */
    public function loginAs($username, $password)
    {
        $this->typeLogin($username);
        $this->typePassword($password);
        $this->findElement($this->submitBtn)->submit();
    }

    /**
     * Choose required locale.
     */
    public function chooseRussianLocale()
    {
        $this->findElement($this->locales)->click()
            ->findElement($this->russianLocale)->click();
        if (SeleniumBase::getCurrentBrowser() == 'chrome') {
            $this->findElement($this->submitLocaleBtn)->click();
        }
    }

    /**
     * Choose required subject.
     */
    public function chooseAppropriateSubject()
    {
        $this->driver->navigate()->refresh();
        $this->waitForAjaxFinished();
        $this->findElement($this->subjects)->click();
        $this->findElement($this->serial)->click();
        if (SeleniumBase::getCurrentBrowser() == 'chrome') {
            $this->findElement($this->submitSubjectBtn)->click();
        }
    }

    /**
     * Create template.
     */
    public function createTemplate()
    {
        $this->findElement($this->templateList)->click();
        $this->findElement($this->template)->click();
        $this->findElement($this->createTemplateBtn)->submit();
    }

    /**
     * @return DomainRegDeployerPage
     */
    public function sendToDeploy()
    {
        $this->waitForAjaxFinished();
        $archiveName = $this->waitForElementVisible($this->nameOfArchive)->getText();

        $this->waitForElementClickable($this->sendToDeployBtn)->click();

        if ($this->findElement($this->sendToDeployBtn)->isDisplayed()) {
            $this->findElement($this->sendToDeployBtn)->click();
        }

        return new DomainRegDeployerPage($this->driver, $archiveName);
    }
}
