<?php

namespace HelioNetworks\HelioPanelBundle\Job;

use Xaav\QueueBundle\JobQueue\Job\JobInterface;

class TestJob implements JobInterface
{
	public function pass() {

		return true;
	}
}