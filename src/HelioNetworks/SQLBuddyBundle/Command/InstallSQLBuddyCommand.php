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

		$filesystem->mirror($sourceDir, $installDir);

		$output->writeln('SQLBuddy installed.');
	}
}
