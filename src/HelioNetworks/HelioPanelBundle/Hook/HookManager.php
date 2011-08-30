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
    echo '403 Unauthorized';
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

	public function getSource()
	{
		$source = $this->getStart();
		foreach ($this->sections as $section) {
			$source .= sprintf('function %s {', $section->getName());
			$source .= $section->getCode();
			$source .= '}';
		}
		$source .= $this->getEnd();

		return $source;
	}

	public function addSection(HookSectionInterface $section)
	{
		$this->sections[] = $section;
	}
}