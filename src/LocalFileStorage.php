<?php
/**
 * @author Vladimir Petukhov
 * @version 0.1
 * @php-version ^7.0
 *
 * @license GPL
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 */

namespace ESlovo\FileStorage;


use Exception;

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
     * @var int
     */
    protected $defaultDirMode = 0777;

    /**
     * @param string|null $rootPath
     * @param string|null $baseUrl
     */
    public function __construct($rootPath = null, $baseUrl = null)
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
        return $this->baseUrl ? $this->baseUrl . '/' . ltrim($path, ' /') : null;
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
    public function getBaseUrl()
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
    public function getRootPath()
    {
        return $this->rootPath;
    }

    /**
     * @param int $mode
     */
    public function setDefaultDirMode(int $mode)
    {
        $this->defaultDirMode = $mode;
    }

    /**
     * @return int
     */
    public function getDefaultDirMode()
    {
        return $this->defaultDirMode;
    }

    /**
     * @param string $path
     * @return string
     * @throws \Exception
     */
    protected function normalizePath(string $path):string
    {
        if (!preg_match("#^[\/\w]+(\/[\w]+(\.[\w]+)?)?$#", $path)) {
            throw new \Exception("Incorrect path was given! Path: '{$path}'");
        }

        return $this->rootPath . DIRECTORY_SEPARATOR . ltrim($path, '/');
    }

    /**
     * @param string $filePath
     * @return bool
     */
    protected function ensureFileDirectoryExists(string $filePath)
    {
        $dirPath = dirname($filePath);

        if (file_exists(dirname($dirPath))) {
            return true;
        }

        return mkdir($dirPath, $this->defaultDirMode, true);
    }

    /**
     * @param string $source
     * @param string $destination
     * @return bool
     */
    public function copy(string $source, string $destination): bool
    {
        $sourcePath = $this->normalizePath($source);

        if (!file_exists($sourcePath)) {
            return false;
        }

        $destPath = $this->normalizePath($destination);

        if ($this->ensureFileDirectoryExists($destPath)) {
            return copy($sourcePath, $destPath);
        }

        return false;
    }

    /**
     * @param string $source
     * @param string $destination
     * @return bool
     */
    public function move(string $source, string $destination): bool
    {
        $sourcePath = $this->normalizePath($source);

        if (!file_exists($sourcePath)) {
            return false;
        }

        $destPath = $this->normalizePath($destination);

        if ($this->ensureFileDirectoryExists($destPath)) {
            return rename($sourcePath, $destPath);
        }

        return false;
    }

    /**
     * @param string $filePath
     * @param string $destination
     * @return bool
     */
    public function copyFile(string $filePath, string $destination): bool
    {
        if (!file_exists($filePath)) {
            return false;
        }

        $destPath = $this->normalizePath($destination);

        if ($this->ensureFileDirectoryExists($destPath)) {
            return copy($filePath, $destPath);
        }

        return false;
    }

    /**
     * @param string $filePath
     * @param string $destination
     * @return bool
     */
    public function moveFile(string $filePath, string $destination): bool
    {
        if (!file_exists($filePath)) {
            return false;
        }

        $destPath = $this->normalizePath($destination);

        if ($this->ensureFileDirectoryExists($destPath)) {
            return rename($filePath, $destPath);
        }

        return false;
    }

    /**
     * @param string $filePath
     * @param string $destination
     * @return bool
     */
    public function moveUploadedFile(string $filePath, string $destination): bool
    {
        if (!file_exists($filePath)) {
            return false;
        }

        $destPath = $this->normalizePath($destination);

        if ($this->ensureFileDirectoryExists($destPath)) {
            return move_uploaded_file($filePath, $destPath);
        }

        return false;
    }

    /**
     * @param string $path
     * @param string $content
     * @param int $flags
     * @return int|false
     */
    public function putFileContents(string $path, string $content, int $flags = 0)
    {
        $filePath = $this->normalizePath($path);

        if ($this->ensureFileDirectoryExists($filePath)) {
            return file_put_contents($filePath, $content, $flags);
        }

        return false;
    }

    /**
     * @param string $path
     * @param int $offset
     * @param int $maxlen
     * @return false|string
     */
    public function getFileContents(string $path, int $offset = 0, int $maxlen = 0)
    {
        $filePath = $this->normalizePath($path);

        if (file_exists($filePath)) {
            return file_get_contents($filePath, false, NULL, $offset, $maxlen);
        }

        return false;
    }

    /**
     * @param string $dirPath
     * @return bool
     * @throws Exception
     */
    protected function isDirEmpty(string $dirPath): bool
    {
        $dirHandle = opendir($dirPath);

        if (!$dirHandle) {
            throw new Exception("No directory exists or it inaccessible!");
        }

        while (false !== ($file = readdir($dirHandle))) {
            if ($file != "." && $file != "..") {
                closedir($dirHandle);

                return false;
            }
        }

        closedir($dirHandle);

        return true;
    }

    /**
     * @param string $dirPath
     * @return bool
     */
    protected function deleteRecursively(string $dirPath):bool
    {
        $dirHandle = opendir($dirPath);

        if (!$dirHandle) {
            return false;
        }

        while (false !== ($entry = readdir($dirHandle))) {
            if ($entry == "." || $entry == "..") {
                continue;
            }

            $fullPath = $dirPath . DIRECTORY_SEPARATOR . $entry;

            if ( is_dir($fullPath) ) {
                $this->deleteRecursively($fullPath);
            }
            else {
                unlink($fullPath);
            }
        }

        closedir($dirHandle);

        return rmdir($dirPath);
    }

    /**
     * @param string $path
     * @param bool $recursive
     * @return bool
     */
    public function delete(string $path, bool $recursive = false): bool
    {
        $destPath = $this->normalizePath($path);

        if (!file_exists($destPath)) {
            return true;
        }

        if (is_dir($destPath)) {
            try {
                $empty = $this->isDirEmpty($destPath);
                if (!$recursive && !$empty) {
                    return false;
                }

                if ($recursive && !$empty) {
                    return $this->deleteRecursively($destPath);
                }
            } catch (Exception $exception) {
                return false;
            }

            return rmdir($destPath);
        }

        return unlink($destPath);
    }

    /**
     * @param string $path
     * @return bool
     */
    public function mkdir(string $path): bool
    {
        $dirPath = $this->normalizePath($path);

        if (file_exists($dirPath && is_dir($dirPath))) {
            return false;
        }

        return mkdir($dirPath, $this->defaultDirMode, true);
    }

    /**
     * @param string $path
     * @param bool $recursive
     * @return bool
     */
    public function rmdir(string $path, bool $recursive = false): bool
    {
        return $this->delete($path, $recursive);
    }

    /**
     * @param string $oldName
     * @param string $newName
     * @return bool
     */
    public function rename(string $oldName, string $newName): bool
    {
        return $this->move($oldName, $newName);
    }

    /**
     * @param string $path
     * @return bool
     */
    public function fileExists(string $path): bool
    {
        return file_exists($this->normalizePath($path));
    }

    /**
     * @param string $path
     * @return bool
     */
    public function isFile(string $path): bool
    {
        return is_file($this->normalizePath($path));
    }

    /**
     * @param string $path
     * @return bool
     */
    public function isDir(string $path): bool
    {
        return is_dir($this->normalizePath($path));
    }

    /**
     * @param string $path
     * @return bool
     */
    public function isReadable(string $path): bool
    {
        return is_readable($this->normalizePath($path));
    }

    /**
     * @param string $path
     * @return bool
     */
    public function isWritable(string $path): bool
    {
        return is_writable($this->normalizePath($path));
    }

    /**
     * @param string $path
     * @return int|false
     */
    public function fileSize(string $path)
    {
        return filesize($this->normalizePath($path));
    }

    /**
     * @param string $path
     * @return int|false
     */
    public function fileAccessTime(string $path)
    {
        return fileatime($this->normalizePath($path));
    }

    /**
     * @param string $path
     * @return int|false
     */
    public function fileCeationTime(string $path)
    {
        return filectime($this->normalizePath($path));
    }

    /**
     * @param string $path
     * @return int|false
     */
    public function fileModificationTime(string $path)
    {
        return filemtime($this->normalizePath($path));
    }

    /**
     * @param string $name
     * @return mixed
     * @throws Exception
     */
    public function __get($name)
    {
        $methodName = 'get' . ucfirst($name);

        if (method_exists($this, $methodName)) {
            return $this->$methodName;
        }

        throw new Exception("Parameter '{$name}' or its getter not found in " . static::class);
    }

    /**
     * @param $name
     * @param $value
     * @return mixed
     * @throws Exception
     */
    public function __set($name, $value)
    {
        $methodName = 'set' . ucfirst($name);

        if (method_exists($this, $methodName)) {
            return $this->$methodName($value);
        }

        throw new Exception("Parameter '{$name}' or its setter not found in " . static::class);
    }
}
