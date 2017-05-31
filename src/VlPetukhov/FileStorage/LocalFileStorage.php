<?php
/**
 * @author Vladimir Petukhov
 * @version 0.1
 * @php-version ^7.0
 *
 * @license GPL
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 */

namespace VlPetukhov\FileStorage;


class LocalFileStorage implements FileStorageInterface
{
    /**
     * @var string|null
     */
    protected $rootPath = null;
    /**
     * @var string|null
     */
    protected $baseUrl = null;

    /**
     * @param string|null $rootPath
     * @param string|null $baseUrl
     */
    public function __consruct($rootPath = null, $baseUrl = null)
    {
        if (is_string($rootPath)) {
            $this->rootPath = $rootPath;
        }

        if (is_string($baseUrl)) {
            $this->baseUrl = $baseUrl;
        }
    }

    /**
     * @param string $path
     * @return string|null
     */
    public function getFileUrl(string $path)
    {
        return '';
    }

    /**
     * @param string $baseUrl
     * @return void
     */
    public function setBaseUrl(string $baseUrl)
    {
        $this->baseUrl = $baseUrl;
    }

    /**
     * @return string
     */
    public function getBaseUrl(): string
    {
        return $this->baseUrl;
    }

    /**
     * @param string $rootPath
     * @return void
     */
    public function setRootPath(string $rootPath)
    {
        $this->rootPath = $rootPath;
    }

    /**
     * @return string
     */
    public function getRootPath(): string
    {
        return $this->rootPath;
    }

    /**
     * @param string $source
     * @param string $destination
     * @return bool
     */
    public function copy(string $source, string $destination): boolean
    {
        // TODO: Implement copy() method.
    }

    /**
     * @param string $source
     * @param string $destination
     * @return bool
     */
    public function move(string $source, string $destination): boolean
    {
        // TODO: Implement move() method.
    }

    /**
     * @param string $filePath
     * @param string $destination
     * @return bool
     */
    public function copyFile(string $filePath, string $destination): boolean
    {
        // TODO: Implement copyFile() method.
    }

    /**
     * @param string $filePath
     * @param string $destination
     * @return bool
     */
    public function moveFile(string $filePath, string $destination): boolean
    {
        // TODO: Implement moveFile() method.
    }

    /**
     * @param string $filePath
     * @param string $destination
     * @return bool
     */
    public function moveUploadedFile(string $filePath, string $destination): boolean
    {
        // TODO: Implement moveUploadedFile() method.
    }

    /**
     * @param string $path
     * @param string $content
     * @param int $flags
     * @return int|false
     */
    public function putFileContents(string $path, string $content, integer $flags = 0)
    {
        // TODO: Implement putFileContents() method.
    }

    /**
     * @param string $path
     * @param int $offset
     * @param int $maxlen
     * @return false|string
     */
    public function getFileContents(string $path, integer $offset = 0, integer $maxlen = 0)
    {
        // TODO: Implement getFileContents() method.
    }

    /**
     * @param string $path
     * @return bool
     */
    public function delete(string $path): boolean
    {
        // TODO: Implement delete() method.
    }

    /**
     * @param string $path
     * @return bool
     */
    public function mkdir(string $path): boolean
    {
        // TODO: Implement mkdir() method.
    }

    /**
     * @param string $path
     * @return bool
     */
    public function rmdir(string $path): boolean
    {
        // TODO: Implement rmdir() method.
    }

    /**
     * @param string $oldName
     * @param string $newName
     * @return bool
     */
    public function rename(string $oldName, string $newName): boolean
    {
        // TODO: Implement rename() method.
    }

    /**
     * @param string $path
     * @return bool
     */
    public function fileExists(string $path): boolean
    {
        // TODO: Implement fileExists() method.
    }

    /**
     * @param string $path
     * @return bool
     */
    public function isFile(string $path): boolean
    {
        // TODO: Implement isFile() method.
    }

    /**
     * @param string $path
     * @return bool
     */
    public function isDir(string $path): boolean
    {
        // TODO: Implement isDir() method.
    }

    /**
     * @param string $path
     * @return bool
     */
    public function isReadable(string $path): boolean
    {
        // TODO: Implement isReadable() method.
    }

    /**
     * @param string $path
     * @return bool
     */
    public function isWritable(string $path): boolean
    {
        // TODO: Implement isWritable() method.
    }

    /**
     * @param string $path
     * @return int|false
     */
    public function fileSize(string $path)
    {
        // TODO: Implement fileSize() method.
    }

    /**
     * @param string $path
     * @return int|false
     */
    public function fileAccessTime(string $path)
    {
        // TODO: Implement fileAccessTime() method.
    }

    /**
     * @param string $path
     * @return int|false
     */
    public function fileCeationTime(string $path)
    {
        // TODO: Implement fileCeationTime() method.
    }

    /**
     * @param string $path
     * @return int|false
     */
    public function fileModificationTime(string $path)
    {
        // TODO: Implement fileModificationTime() method.
    }
}