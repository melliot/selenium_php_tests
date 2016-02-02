<?php

namespace tests\ui;

use Facebook\WebDriver\WebDriverBy;
use tests\ui\pages\SiteBuilderPage;
use tests\ui\util\SeleniumBase;

/**
 * Test for creating template and edit domain.
 */
class CreateEditAndCopyTemplateTest extends SeleniumBase
{
    /**
     * Check creating template and edit domain.
     */
    public function testCreateEditReplaceTemplate()
    {
        $domainLocation = WebDriverBy::xpath("//*[@id='domain-add-page']/h2/span");
        $subjectLocation = WebDriverBy::xpath("//*[@id='subject-text']");

        $siteBuilderUrl = $this->getValue('site_builder_url');
        $siteBuilderLogin = $this->getValue('site_builder_login');
        $siteBuilderPassword = $this->getValue('site_builder_password');
        $domainRegLogin = $this->getValue('domain_reg_deployer_login');
        $domainRegPassword = $this->getValue('domain_reg_deployer_password');
        $testSlot = $this->getValue('test_slot_url');
        $domainRegUrl = $this->getValue('domain_reg_deployer_url');

        $siteBuild = new SiteBuilderPage($this->driver->get($siteBuilderUrl));
        $siteBuild->loginAs($siteBuilderLogin, $siteBuilderPassword);
        self::assertEquals('Сателлиты', $this->driver->getTitle());

        $siteBuild->chooseRussianLocale();
        $siteBuild->chooseAppropriateSubject();
        $siteBuild->createTemplate();
        $domainRegDeployer = $siteBuild->sendToDeploy();
        self::assertContains('serialy-tut', $domainRegDeployer->archiveName);

        $this->driver->get($domainRegUrl);
        $domainRegDeployer->login($domainRegLogin, $domainRegPassword);
        self::assertEquals('Satellite', $this->driver->getTitle());

        $domainRegDeployer->clickSearch($testSlot);
        $domainRegDeployer->editDomain();
        $this->waitForAjaxFinished();
        $domainUrl = $this->waitForPresenceElementLocated($domainLocation)->getText();
        self::assertEquals($testSlot, $domainUrl);

        $url = $this->driver->getCurrentURL();
        preg_match('/\d+/', $url, $domainId);
        $domainId = $domainId[0];

        $domainRegDeployer->chooseSubject();
        $text = $this->waitForPresenceElementLocated($subjectLocation)->getText();
        self::assertEquals('сериалы', $text);

        $copyStatusLocation = WebDriverBy::xpath('//*[@id=\'domain_deploy_copy_status_' . $domainId . '\']');
        $domainRegDeployer->saveTemplate();
        $domainRegDeployer->copyFiles();
        $this->waitForAjaxFinished();
        self::assertTrue($this->driver->findElement($copyStatusLocation)->isDisplayed());
    }
}
