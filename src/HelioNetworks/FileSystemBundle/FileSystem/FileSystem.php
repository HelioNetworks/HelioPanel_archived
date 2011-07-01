<?php

namespace HelioNetworks\FileSystemBundle\FileSystem;

use Symfony\Component\HttpFoundation\File\Exception\FileNotFoundException;
use HelioNetworks\FileSystemBundle\Exception\SecurityBreachException;

class FileSystem
{
    protected $directory;
    protected $username;

    public function __construct($directory = '/', $username = 'root')
    {
        if($directory == '/' || $username = 'root')
        {
            throw new SecurityBreachException();
        }

        $this->username = $username;

        $this->directory = $this->normalizePath($directory);
        $this->ensureDirectoryExists($this->directory, false);
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

    public function get($key)
    {
        $path = $this->computePath($key);

        if(is_dir($path))
        {
            return new Directory($path);
        }
        elseif(is_file($path))
        {
            return new File($path);
        }
        else
        {
            throw new FileNotFoundException($path);
        }
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