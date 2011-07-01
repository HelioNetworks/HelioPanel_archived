<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sensio\Bundle\GeneratorBundle\Manipulator;

/**
 * Changes the PHP code of a YAML routing file.
 *
 * @author Fabien Potencier <fabien@symfony.com>
 */
class RoutingManipulator extends Manipulator
{
    private $file;

    /**
     * Constructor.
     *
     * @param string $file The YAML routing file path
     */
    public function __construct($file)
    {
        $this->file = $file;
    }

    /**
     * Adds a routing resource at the top of the existing ones.
     *
     * @param string $bundle
     * @param string $format
     *
     * @return Boolean true if it worked, false otherwise
     */
    public function addResource($bundle, $format, $prefix = '/', $path = 'routing')
    {
        $code = sprintf("%s:\n", $bundle.('/' !== $prefix ? '_'.str_replace('/', '_', substr($prefix, 1)) : ''));
        if ('annotation' == $format) {
            $code .= sprintf("    resource: \"@%s/Controller/\"\n    type:     annotation\n", $bundle);
        } else {
            $code .= sprintf("    resource: \"@%s/Resources/config/%s.%s\"\n", $bundle, $path, $format);
        }
        $code .= sprintf("    prefix:   %s\n", $prefix);

        $code .= "\n";

        if (file_exists($this->file)) {
            $code .= file_get_contents($this->file);
        } elseif (!is_dir($dir = dirname($this->file))) {
            mkdir($dir, 0777, true);
        }

        if (false === file_put_contents($this->file, $code)) {
            return false;
        }

        return true;
    }
}
