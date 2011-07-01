<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sensio\Bundle\GeneratorBundle\Generator;

use Symfony\Component\HttpKernel\Util\Filesystem;

/**
 * Generates a bundle.
 *
 * @author Fabien Potencier <fabien@symfony.com>
 */
class BundleGenerator extends Generator
{
    private $filesystem;
    private $skeletonDir;

    public function __construct(Filesystem $filesystem, $skeletonDir)
    {
        $this->filesystem = $filesystem;
        $this->skeletonDir = $skeletonDir;
    }

    public function generate($namespace, $bundle, $dir, $format, $structure)
    {
        $dir .= '/'.strtr($namespace, '\\', '/');
        if (file_exists($dir)) {
            throw new \RuntimeException(sprintf('Unable to generate the bundle as the target directory "%s" is not empty.', realpath($dir)));
        }

        $parameters = array(
            'namespace' => $namespace,
            'bundle'    => $bundle,
            'format'    => $format,
        );

        $this->renderFile($this->skeletonDir, 'Bundle.php', $dir.'/'.$bundle.'.php', $parameters);
        $this->renderFile($this->skeletonDir, 'DefaultController.php', $dir.'/Controller/DefaultController.php', $parameters);
        $this->renderFile($this->skeletonDir, 'DefaultControllerTest.php', $dir.'/Tests/Controller/DefaultControllerTest.php', $parameters);
        $this->renderFile($this->skeletonDir, 'index.html.twig', $dir.'/Resources/views/Default/index.html.twig', $parameters);

        if ('annotation' != $format) {
            $this->renderFile($this->skeletonDir, 'routing.'.$format, $dir.'/Resources/config/routing.'.$format, $parameters);
        }

        if ($structure) {
            $this->filesystem->mkdir($dir.'/Resources/doc');
            $this->filesystem->touch($dir.'/Resources/doc/index.rst');
            $this->filesystem->mkdir($dir.'/Resources/translations');
            $this->filesystem->copy($this->skeletonDir.'/structure/messages.fr.xliff', $dir.'/Resources/translations/messages.fr.xliff');
            $this->filesystem->mkdir($dir.'/Resources/public/css');
            $this->filesystem->mkdir($dir.'/Resources/public/images');
            $this->filesystem->mkdir($dir.'/Resources/public/js');
        }
    }
}
