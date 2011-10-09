<?php

namespace HelioNetworks\HelioPanelBundle\Job;

use Xaav\QueueBundle\Queue\Job\JobInterface;

class TestJob implements JobInterface
{
	public function process() {

		return true;
	}
}