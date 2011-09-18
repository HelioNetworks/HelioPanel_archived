<?php

namespace HelioNetworks\SQLBuddyBundle\Command;

use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;

class InstallSQLBuddyCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this->setName('sqlbuddy:install')
            ->setDescription('Install SQLBuddy')
            ->setDefinition(array(
                new InputArgument('target', InputArgument::REQUIRED, 'The target directory (usually "web")'),
            ));
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln('Installing SQLBuddy...');

        $filesystem = $this->getContainer()->get('filesystem');
        $installDir = $input->getArgument('target').'/sqlbuddy';
        $sourceDir = dirname($this->getContainer()->get('kernel')->getRootDir()).'/vendor/sqlbuddy';

        try {
            $filesystem->mirror($sourceDir, $installDir);
        } catch (\Exception $ex) {
            $output->writeln($ex->getMessage());
        }

        file_put_contents($installDir.'/config.php', $this->getConfigFile());

        $output->writeln('SQLBuddy installed');
    }

    protected function getConfigFile()
    {
        return <<<'PHP'
<?php

if(!$_SESSION['DefaultUser'] || !$_SESSION['DefaultPass']) {
    header('Location: '.(@$_GET['dev'] ? '/app_dev.php' : '').'/sqlbuddy/');
    die();
}

$sbconfig['DefaultAdapter'] = "mysql";
$sbconfig['DefaultHost'] = $_SESSION['DefaultHost'];
$sbconfig['DefaultUser'] = $_SESSION['DefaultUser'];
$sbconfig['DefaultPass'] = $_SESSION['DefaultPass'];
$sbconfig['EnableUpdateCheck'] = false;
$sbconfig['RowsPerPage'] = 100;
$sbconfig['EnableGzip'] = false;
PHP;
    }
}
