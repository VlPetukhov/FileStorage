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


interface FileStorageInterface
{
    /**
     * @param string $path
     * @return string|null
     */
    public function getFileUrl(string $path);

    /**
     * @param string $baseUrl
     * @return void
     */
    public function setBaseUrl(string $baseUrl);

    /**
     * @return string
     */
    public function getBaseUrl():string;

    /**
     * @param string $rootPath
     * @return void
     */
    public function setRootPath(string $rootPath);

    /**
     * @return string
     */
    public function getRootPath():string;

    /**
     * @param string $source
     * @param string $destination
     * @return bool
     */
    public function copy(string $source, string $destination):boolean;

    /**
     * @param string $source
     * @param string $destination
     * @return bool
     */
    public function move(string $source, string $destination):boolean;

    /**
     * @param string $filePath
     * @param string $destination
     * @return bool
     */
    public function copyFile(string $filePath, string $destination):boolean;

    /**
     * @param string $filePath
     * @param string $destination
     * @return bool
     */
    public function moveFile(string $filePath, string $destination):boolean;

    /**
     * @param string $filePath
     * @param string $destination
     * @return bool
     */
    public function moveUploadedFile(string $filePath, string $destination):boolean;

    /**
     * @param string $path
     * @param string $content
     * @param int $flags
     * @return int|false
     */
    public function putFileContents(string $path, string $content, integer $flags = 0);

    /**
     * @param string $path
     * @param int $offset
     * @param int $maxlen
     * @return false|string
     */
    public function getFileContents(string $path, integer $offset = 0, integer $maxlen = 0);

    /**
     * @param string $path
     * @param bool $recursive
     * @return bool
     */
    public function delete(string $path, boolean $recursive = false):boolean;

    /**
     * @param string $path
     * @return bool
     */
    public function mkdir(string $path):boolean;

    /**
     * @param string $path
     * @param bool $recursive
     * @return bool
     */
    public function rmdir(string $path, boolean $recursive = false):boolean;

    /**
     * @param string $oldName
     * @param string $newNames
     * @return bool
     */
    public function rename(string $oldName, string $newName):boolean;

    /**
     * @param string $path
     * @return bool
     */
    public function fileExists(string $path):boolean;

    /**
     * @param string $path
     * @return bool
     */
    public function isFile(string $path):boolean;

    /**
     * @param string $path
     * @return bool
     */
    public function isDir(string $path):boolean;

    /**
     * @param string $path
     * @return bool
     */
    public function isReadable(string $path):boolean;

    /**
     * @param string $path
     * @return bool
     */
    public function isWritable(string $path):boolean;

    /**
     * @param string $path
     * @return int|false
     */
    public function fileSize(string $path);

    /**
     * @param string $path
     * @return int|false
     */
    public function fileAccessTime(string $path);

    /**
     * @param string $path
     * @return int|false
     */
    public function fileCeationTime(string $path);

    /**
     * @param string $path
     * @return int|false
     */
    public function fileModificationTime(string $path);
}
