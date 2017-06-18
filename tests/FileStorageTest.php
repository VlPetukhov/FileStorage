<?php
/**
 * @author Vladimir Petukhov
 * @version 0.1
 * @php-version ^7.0
 *
 * @license GPL
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 */

namespace  ESlovo\FileStorage;


use PHPUnit\Framework\TestCase;

class FileStorageTest extends TestCase
{
    public function testFileStorageConstructor()
    {
        //setting parameters after storage creation
        $fileStorage = new LocalFileStorage();
        $this->assertInstanceOf(LocalFileStorage::class, $fileStorage);
        $this->assertTrue(is_null($fileStorage->getRootPath()));
        $this->assertTrue(is_null($fileStorage->getBaseUrl()));

        $baseDir = "/testDir";
        $baseUrl = "test.com";

        $fileStorage->setRootPath($baseDir);
        $fileStorage->setBaseUrl($baseUrl);
        $this->assertEquals($baseDir, $fileStorage->getRootPath());
        $this->assertEquals($baseUrl, $fileStorage->getBaseUrl());

        unset($ileStorage);
        //setting via constructor parameters
        $fileStorage = new LocalFileStorage($baseDir, $baseUrl);

        $this->assertInstanceOf(LocalFileStorage::class, $fileStorage);
        $this->assertEquals($baseDir, $fileStorage->getRootPath());
        $this->assertEquals($baseUrl, $fileStorage->getBaseUrl());

        //dir mode setter and getter
        $this->assertEquals(0777, $fileStorage->getDefaultDirMode());
        $newMode = 0600;
        $fileStorage->setDefaultDirMode($newMode);
        $this->assertEquals($newMode, $fileStorage->getDefaultDirMode());
    }
}
