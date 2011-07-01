<?php

namespace HelioNetworks\FileSystemBundle\FileSystem;

/**
 * The Base FileSystem
 *
 * All file operations must be run through this class.
 */
class BaseFileSystem
{
    protected $username;
    protected $directory;

    public function __construct($directory, $username)
    {
        $this->username = $username;

        $this->directory = $this->normalizePath($directory);
        $this->ensureDirectoryExists($this->directory, false);
    }

    public function scan($key)
    {
        return scandir($this->computeKey($key));
    }

    /**
     * {@InheritDoc}
     */
    public function rename($key, $new)
    {
        if (!rename($this->computePath($key), $this->computePath($new))) {
            throw new \RuntimeException(sprintf('Could not rename the \'%s\' file to \'%s\'.', $key, $new));
        }
    }

    /**
     * Writes the given content into the file
     *
     * @param  string  $key       Key of the file
     * @param  string  $content   Content to write in the file
     * @param  boolean $overwrite Whether to overwrite the file if exists
     *
     * @return integer The number of bytes that were written into the file
     */
    public function write($key, $content, $overwrite = false)
    {
        if (!$overwrite && $this->has($key)) {
            throw new \InvalidArgumentException(sprintf('The file %s already exists and can not be overwritten.', $key));
        }

        return $this->adapter->write($key, $content);
    }

    /**
     * Reads the content from the file
     *
     * @param  string $key Key of the file
     *
     * @return string
     */
    public function read($key)
    {
        if (!$this->has($key)) {
            throw new \InvalidArgumentException(sprintf('The file %s does not exist.', $key));
        }

        return $this->adapter->read($key);
    }


    /**
     * {@InheritDoc}
     */
    public function delete($key)
    {
        if (!unlink($this->computePath($key))) {
            throw new \RuntimeException(sprintf('Could not remove the \'%s\' file.', $key));
        }
    }

    /**
     * Computes the path from the specified key
     *
     * @param  string $key The key which for to compute the path
     *
     * @return string A path
     *
     * @throws OutOfBoundsException If the computed path is out of the
     *                              directory
     */
    public function computePath($key)
    {
        $path = $this->normalizePath($this->directory . '/' . $key);

        if (0 !== strpos($path, $this->directory)) {
            throw new SecurityBreachException();
        }

        return $path;
    }

    /**
     * Normalizes the given path
     *
     * @param  string $path
     *
     * @return string
     */
    public function normalizePath($path)
    {
        return Path::normalize($path);
    }

    /**
     * Computes the key from the specified path
     *
     * @param  string $path
     *
     * return string
     */
    public function computeKey($path)
    {
        $path = $this->normalizePath($path);
        if (0 !== strpos($path, $this->directory)) {
            throw new \OutOfBoundsException(sprintf('The path \'%s\' is out of the filesystem.', $path));
        }

        return ltrim(substr($path, strlen($this->directory)), '/');
    }

    /**
     * Ensures the specified directory exists, creates it if it does not
     *
     * @param  string  $directory Path of the directory to test
     * @param  boolean $create    Whether to create the directory if it does
     *                            not exist
     *
     * @throws RuntimeException if the directory does not exists and could not
     *                          be created
     */
    public function ensureDirectoryExists($directory, $create = false)
    {
        if (!is_dir($directory)) {
            if (!$create) {
                throw new \RuntimeException(sprintf('The directory \'%s\' does not exist.', $directory));
            }

            $this->createDirectory($directory);
        }
    }

    /**
     * Creates the specified directory and its parents
     *
     * @param  string $directory Path of the directory to create
     *
     * @throws InvalidArgumentException if the directory already exists
     * @throws RuntimeException         if the directory could not be created
     */
    public function createDirectory($directory)
    {
        if (is_dir($directory)) {
            throw new \InvalidArgumentException(sprintf('The directory \'%s\' already exists.', $directory));
        }

        $umask = umask(0);
        $created = mkdir($directory, 0777, true);
        umask($umask);

        if (!$created) {
            throw new \RuntimeException(sprintf('The directory \'%s\' could not be created.', $directory));
        }
    }
}