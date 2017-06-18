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
        $fileStorage = new LocalFileStorage();
        $this->assertInstanceOf(LocalFileStorage::class, $fileStorage);
        $this->assertTrue(is_null($fileStorage->getRootPath()));
        $this->assertTrue(is_null($fileStorage->getBaseUrl()));

        $baseDir = "/testDir";
        $baseUrl = "test.com";

        $fileStorage = new LocalFileStorage($baseDir, $baseUrl);

        $this->assertInstanceOf(LocalFileStorage::class, $fileStorage);
        $this->assertEquals($baseDir, $fileStorage->getRootPath());
        $this->assertEquals($baseUrl, $fileStorage->getBaseUrl());
    }
}
