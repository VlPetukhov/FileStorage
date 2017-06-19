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
    /** @var string  */
    protected $storageRootPath = __DIR__ .DIRECTORY_SEPARATOR . 'test';
    protected $storageOuterUrl = "http://test.com/";

    public function setUp()
    {
        if (file_exists($this->storageRootPath)) {
            $this->recursiveDirRemove($this->storageRootPath);
        }
    }

    /**
     * Deletes directory recursively
     * @param $src
     */
    protected function recursiveDirRemove($src) {
        $dir = opendir($src);
        while(false !== ($file = readdir($dir))) {

            if (($file != '.') && ($file != '..')) {
                $full = $src . DIRECTORY_SEPARATOR . $file;

                if (is_dir($full)) {
                    $this->recursiveDirRemove($full);
                }
                else {
                    unlink($full);
                }
            }
        }

        closedir($dir);
        rmdir($src);
    }

    /**
     * @test
     */
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

    /**
     * @test
     */
    public function testDirectoryCreation()
    {
        $fileStorage = new LocalFileStorage($this->storageRootPath, $this->storageOuterUrl);
        //file storage creates directories
        $dirPath = 'dir';
        $fileStorage->mkdir($dirPath);
        $this->assertDirectoryExists($this->storageRootPath . DIRECTORY_SEPARATOR . $dirPath);
        //file storage creates directory recursively
        $dirPath2 = 'dir/sub_dir1/sub_dir2';
        $fileStorage->mkdir($dirPath2);
        $path = str_replace('/', DIRECTORY_SEPARATOR, $dirPath2);
        $this->assertDirectoryExists($this->storageRootPath . DIRECTORY_SEPARATOR . $path);
    }

    /**
     * Data provider
     * @return array
     */
    public function incorrectDirNames()
    {
        return [
            ['.'],
            ['~'],
            ['$dir'],
            ['~dir'],
            ['!dir'],
            ['@dir'],
            ['#dir'], //and so on with the path which contains non alphabetical symbols
            ['..'],
            ['/../'],
            ['/dir/../../../'],
        ];
    }

    /**
     * @dataProvider incorrectDirNames
     * @expectedException \Exception
     */
    public function testIncorrectDirectoriesCreation($dirPath)
    {
        $fileStorage = new LocalFileStorage($this->storageRootPath, $this->storageOuterUrl);
        //file storage creates directories
        $fileStorage->mkdir($dirPath);
    }

    public function testPuFileContents()
    {
        $storageOuterUrl = "test.com/files";
        $fileStorage = new LocalFileStorage($this->storageRootPath, $storageOuterUrl);

        $fileContent = "This is plain text.";
        $fileDirectoryPath = "/test/subdir/";
        $filePath = $fileDirectoryPath . 'file.txt';
        $fileInStoragePath = $this->storageRootPath . DIRECTORY_SEPARATOR .
            ltrim(str_replace('/', DIRECTORY_SEPARATOR, $filePath), '/');

        $fileStorage->putFileContents($filePath, $fileContent);
        $this->assertFileExists($fileInStoragePath);
        $this->assertEquals($fileContent, file_get_contents($fileInStoragePath));

        $fileStorage->putFileContents($filePath, $fileContent, FILE_APPEND);
        $this->assertFileExists($fileInStoragePath);
        $this->assertEquals($fileContent . $fileContent, file_get_contents($fileInStoragePath));

        //binary data

        $sourceFilePath = __DIR__ . DIRECTORY_SEPARATOR . 'externalFiles' . DIRECTORY_SEPARATOR . 'binary.file';
        $fileContent = file_get_contents($sourceFilePath);

        $fileDirectoryPath = "/test1/subdir1/";
        $filePath = $fileDirectoryPath . 'file1.txt';
        $fileInStoragePath = $this->storageRootPath . DIRECTORY_SEPARATOR .
            ltrim(str_replace('/', DIRECTORY_SEPARATOR, $filePath), '/');

        $fileStorage->putFileContents($filePath, $fileContent);
        $this->assertFileExists($fileInStoragePath);
        $this->assertEquals($fileContent, file_get_contents($fileInStoragePath));
    }

    public function testCopyFile()
    {
        $fileStorage = new LocalFileStorage($this->storageRootPath, $this->storageOuterUrl);

        $sourceFilePath = __DIR__ . DIRECTORY_SEPARATOR . 'externalFiles' . DIRECTORY_SEPARATOR . 'text.file';

        $fileDirectoryPath = "/test1/subdir1/";
        $filePath = $fileDirectoryPath . 'file1.txt';
        $fileInStoragePath = $this->storageRootPath . DIRECTORY_SEPARATOR .
            ltrim(str_replace('/', DIRECTORY_SEPARATOR, $filePath), '/');

        $fileStorage->copyFile($sourceFilePath, $filePath);
        $this->assertFileExists($fileInStoragePath);
        $this->assertEquals(file_get_contents($sourceFilePath), file_get_contents($fileInStoragePath));
    }

    public function testMoveFile()
    {
        $fileStorage = new LocalFileStorage($this->storageRootPath, $this->storageOuterUrl);

        $sourceFilePath = __DIR__ . DIRECTORY_SEPARATOR . 'externalFiles' . DIRECTORY_SEPARATOR . 'text.file';
        $copiedFilePath = __DIR__ . DIRECTORY_SEPARATOR . 'externalFiles' . DIRECTORY_SEPARATOR . 'forCopies' .
            DIRECTORY_SEPARATOR . 'text.file';

        copy($sourceFilePath, $copiedFilePath);

        $fileDirectoryPath = "/test3/subdir3/";
        $filePath = $fileDirectoryPath . 'file3.txt';
        $fileInStoragePath = $this->storageRootPath . DIRECTORY_SEPARATOR .
            ltrim(str_replace('/', DIRECTORY_SEPARATOR, $filePath), '/');

        $fileStorage->moveFile($copiedFilePath, $filePath);
        $this->assertFileExists($fileInStoragePath);
        $this->assertFileNotExists($copiedFilePath);
        $this->assertEquals(file_get_contents($sourceFilePath), file_get_contents($fileInStoragePath));
    }
}
