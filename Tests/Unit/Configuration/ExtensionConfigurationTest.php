<?php
namespace Aoe\Restler\Tests\Unit\Configuration;

/***************************************************************
 *  Copyright notice
 *
 *  (c) 2015 AOE GmbH <dev@aoe.com>
 *
 *  All rights reserved
 *
 *  This script is part of the TYPO3 project. The TYPO3 project is
 *  free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 3 of the License, or
 *  (at your option) any later version.
 *
 *  The GNU General Public License can be found at
 *  http://www.gnu.org/copyleft/gpl.html.
 *
 *  This script is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  This copyright notice MUST APPEAR in all copies of the script!
 ***************************************************************/

use Aoe\Restler\Configuration\ExtensionConfiguration;
use Aoe\Restler\Tests\Unit\BaseTest;

/**
 * @package Restler
 * @subpackage Tests
 *
 * @covers \Aoe\Restler\Configuration\ExtensionConfiguration
 */
class ExtensionConfigurationTest extends BaseTest
{
    /**
     * @var ExtensionConfiguration
     */
    protected $configuration;
    /**
     * original config of the restler-Extension
     */
    protected $originalExtConfig;

    /**
     * setup
     */
    protected function setUp()
    {
        parent::setUp();

        $this->originalExtConfig = [];
        $modifiedExtConfig = [];
        if (isset($GLOBALS['TYPO3_CONF_VARS']['EXT']['extConf']['restler'])) {
            $this->originalExtConfig = $GLOBALS['TYPO3_CONF_VARS']['EXT']['extConf']['restler'];
            $modifiedExtConfig = unserialize($this->originalExtConfig);
        }
        $modifiedExtConfig['refreshCache'] = '0';
        $modifiedExtConfig['productionContext'] = '1';
        $modifiedExtConfig['enableOnlineDocumentation'] = '1';
        $modifiedExtConfig['pathToOnlineDocumentation'] = 'api_explorer';
        $modifiedExtConfig['extensionsWithRequiredExtLocalConfFiles'] = ' extension1, extension2 ';
        $GLOBALS['TYPO3_CONF_VARS']['EXT']['extConf']['restler'] = serialize($modifiedExtConfig);

        $this->configuration = new ExtensionConfiguration();
    }

    /**
     * Cleans up the environment after running a test.
     */
    protected function tearDown()
    {
        $GLOBALS['TYPO3_CONF_VARS']['EXT']['extConf']['rester'] = $this->originalExtConfig;
        parent::tearDown();
    }

    /**
     * @test
     */
    public function canCheckThatCacheRefreshingIsNotEnabled()
    {
        $this->assertFalse($this->configuration->isCacheRefreshingEnabled());
    }

    /**
     * @test
     */
    public function canCheckThatProductionContextIsSet()
    {
        $this->assertTrue($this->configuration->isProductionContextSet());
    }

    /**
     * @test
     */
    public function canCheckThatOnlineDocumentationIsEnabled()
    {
        $this->assertTrue($this->configuration->isOnlineDocumentationEnabled());
    }

    /**
     * @test
     */
    public function canGetExtensionsWithRequiredExtLocalConfFiles()
    {
        $extensionList = $this->configuration->getExtensionsWithRequiredExtLocalConfFiles();
        $this->assertInternalType('array', $extensionList);
        $this->assertEquals(2, count($extensionList));
        $this->assertEquals('extension1', $extensionList[0]);
        $this->assertEquals('extension2', $extensionList[1]);
    }

    /**
     * @test
     */
    public function canGetPathOfOnlineDocumentation()
    {
        $this->assertEquals('api_explorer', $this->configuration->getPathOfOnlineDocumentation());
    }
}
