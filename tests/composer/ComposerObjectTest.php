<?php

/**
 * Intended to ensure that the composer object has the appropriate fields
 * all repos.
 */
class ComposerObjectTest extends PHPUnit_Framework_TestCase
{
    const COMPOSER_FILE = 'composer.json';

    const ERR_NO_FILE       = 'There was no file found at "%s';
    const ERR_DECODING_JSON = 'Json error: "%s"';

    const NOTICE_NO_FILE_SET = 'There was no $_FILES[\'composer.json\'] found.';

    const TEST_AUTHOR_KEY           = 'authors';
    const TEST_DESCRIPTION_KEY      = 'description';
    const TEST_DEV_REQUIREMENTS_KEY = 'require-dev';
    const TEST_LICENSE_KEY          = 'license';
    const TEST_NAME_KEY             = 'name';
    const TEST_REQUIREMENTS_KEY     = 'require';
    const TEST_SUPPORT_KEY          = 'support';
    const TEST_SUPPORT_EMAIL_KEY    = 'email';
    const TEST_SUPPORT_WIKI_KEY     = 'wiki';
    const TEST_SUPPORT_ISSUES_KEY   = 'issues';
    const TEST_TIME_KEY             = 'time';

    protected $_composerObj = null;


    /**
     * Creates the composer object from the file supplied in the $_FILES[]
     * array.
     *
     * @return mixed|null
     */
    protected function makeComposerObj()
    {
        if (isset($_FILES[self::COMPOSER_FILE]) === false) {
            $this->markTestSkipped(self::NOTICE_NO_FILE_SET);
        }

        if (file_exists($_FILES[self::COMPOSER_FILE]) === true) {
            $contents    = file_get_contents($_FILES[self::COMPOSER_FILE]);
            $composerObj = json_decode($contents);

            if (json_last_error() !== JSON_ERROR_NONE) {
                $this->markTestIncomplete(
                    sprintf(self::ERR_DECODING_JSON, json_last_error_msg())
                );
            } else {
                return $composerObj;
            }
        } else {
            $this->markTestIncomplete(
                sprintf(self::ERR_NO_FILE, $_FILES[self::COMPOSER_FILE])
            );
        }

        return null;

    }

    /**
     * Fetches the composer object for manipulation by other methods.
     *
     * @return mixed
     */
    protected function getComposerObj()
    {
        if ($this->_composerObj === null) {
            $this->_composerObj = $this->makeComposerObj();
        }

        return $this->_composerObj;
    }

    public function testHasAuthors()
    {
        $this->assertObjectHasAttribute(
            self::TEST_AUTHOR_KEY, $this->getComposerObj()
        );
    }

    public function testHasDescription()
    {
        $this->assertObjectHasAttribute(
            self::TEST_DESCRIPTION_KEY, $this->getComposerObj()
        );
    }

    public function testHasLicense()
    {
        $this->assertObjectHasAttribute(
            self::TEST_LICENSE_KEY, $this->getComposerObj()
        );
    }

    public function testHasName()
    {
        $this->assertObjectHasAttribute(
            self::TEST_NAME_KEY, $this->getComposerObj()
        );
    }

    public function testHasRequirements()
    {
        $this->assertObjectHasAttribute(
            self::TEST_REQUIREMENTS_KEY, $this->getComposerObj()
        );
    }

    public function testHasSupport()
    {
        $this->assertObjectHasAttribute(
            self::TEST_SUPPORT_KEY, $this->getComposerObj()
        );

        $support = $this->getComposerObj()->support;
        $this->assertObjectHasAttribute(self::TEST_SUPPORT_EMAIL_KEY, $support);
        $this->assertObjectHasAttribute(self::TEST_SUPPORT_WIKI_KEY, $support);
        $this->assertObjectHasAttribute(self::TEST_SUPPORT_ISSUES_KEY, $support);
    }

    public function testHasTime()
    {
        $this->assertObjectHasAttribute(
            self::TEST_TIME_KEY, $this->getComposerObj()
        );
    }

    public function testHasDevRequirements()
    {
        $this->assertObjectHasAttribute(
            self::TEST_DEV_REQUIREMENTS_KEY, $this->getComposerObj()
        );
    }

}