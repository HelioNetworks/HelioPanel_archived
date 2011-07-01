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

    /**
     * Scans the directory
     *
     * @param string $key The directory to scan
     *
     * @return array The contents of the directory
     */
    public function scan($key)
    {
        return scandir($this->computePath($key));
    }


    public function isDir($key)
    {
        return is_dir($this->computePath($key));
    }

    /**
     * Renames a file
     *
     * @param string $key Source
     * @param string $new Destination
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
     *
     * @return integer The number of bytes that were written into the file
     */
    public function write($key, $content)
    {
        return file_put_contents($this->computeKey(key), $content);
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
        return file_get_contents($this->computeKey($key));
    }


    /**
     * Deletes the file
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