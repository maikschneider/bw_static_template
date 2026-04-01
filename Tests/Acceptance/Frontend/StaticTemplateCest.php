<?php

declare(strict_types=1);

namespace Blueways\BwStaticTemplateTest\Acceptance\Frontend;

use Blueways\BwStaticTemplateTest\Acceptance\Support\AcceptanceTester;

class StaticTemplateCest
{
    /**
     * Content element with no template path configured renders no template output.
     */
    public function canSeeNothingOnInvalidContentElement(AcceptanceTester $I): void
    {
        $I->amOnPage('/invalid-template/');
        $I->seeResponseCodeIs(500);
        $I->dontSee('Static template rendered successfully');
        $I->see('InvalidTemplateResourceException');
    }

    /**
     * Content element with a full EXT: path renders the referenced template.
     */
    public function canSeeTemplateWithFullExtPath(AcceptanceTester $I): void
    {
        $I->amOnPage('/ext-path-template/');
        $I->seeResponseCodeIs(200);
        $I->see('Static template rendered successfully');
    }

    /**
     * Content element with only a template name renders correctly when
     * templateRootPath is configured via TypoScript constants.
     */
    public function canSeeTemplateWithNameOnly(AcceptanceTester $I): void
    {
        $I->amOnPage('/template-name-only/');
        $I->seeResponseCodeIs(200);
        $I->see('Static template rendered successfully');
    }

    /**
     * JSON from the inline input field is injected as Fluid variables.
     */
    public function canSeeJsonVariablesInjectedIntoTemplate(AcceptanceTester $I): void
    {
        $I->amOnPage('/json-variables/');
        $I->seeResponseCodeIs(200);
        $I->see('Hello from JSON', '.bwst-json-headline');
        $I->see('blue', '.bwst-json-color');
    }

    /**
     * FAL asset attached to the content element is injected as {files}.
     */
    public function canSeeFileInjectedIntoTemplate(AcceptanceTester $I): void
    {
        $I->amOnPage('/file-injection/');
        $I->seeResponseCodeIs(200);
        $I->see('fixture.png', '.bwst-file-name');
    }
}
