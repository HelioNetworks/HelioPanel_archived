<?php

namespace HelioNetworks\HelioPanelBundle\Hook;

class HookManager
{
	protected $sections;

	protected function getStart()
	{
		return <<<'PHP'
<?php

error_reporting(0);

if($_POST['__auth'] !== '%auth%') {
    echo '%hash%';
    die();
}

PHP;
	}

	protected function getEnd()
	{
		return <<<'PHP'

ob_start();
$result = call_user_func_array($_POST['__name'], $_POST);
ob_get_clean();

echo serialize($result);
PHP;
	}

	protected function getBody()
	{
		$source = '';
		foreach ($this->sections as $section) {
			$source .= sprintf('function %s {', $section->getName());
			$source .= $section->getCode();
			$source .= '}';
		}

		return $source;
	}

	public function getHash()
	{
		return md5($this->getBody());
	}

	public function getCode($auth)
	{
		$source = $this->getStart();
		$source = str_replace('%auth%', $auth, $source);
		$source = str_replace('%hash%', $this->getHash(), $source);
		$source .= $this->getBody();
		$source .= $this->getEnd();

		return $source;
	}

	public function addSection(HookSectionInterface $section)
	{
		$this->sections[] = $section;
	}
}