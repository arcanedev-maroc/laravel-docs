<?php namespace Arcanedev\LaravelDocs\Services\Docs\Entities;

use Illuminate\Support\Facades\Config	as Config;
use Illuminate\Support\Facades\File		as File;

use ParsedownExtra;

class Doc
{
	/* ------------------------------------------------------------------------------------------------
	 |  Properties
	 | ------------------------------------------------------------------------------------------------
	 */
	protected $name		= '';

	protected $path		= '';

	protected $content	= '';

	protected $exists	= false;

	/* ------------------------------------------------------------------------------------------------
	 |  Constructor
	 | ------------------------------------------------------------------------------------------------
	 */
	function __construct($locale, $version, $file)
	{
		$this->path	= $file;

		$this->setName($file);

		$this->loadContent($locale, $version);
	}

	/* ------------------------------------------------------------------------------------------------
	 |  Getters & Setters
	 | ------------------------------------------------------------------------------------------------
	 */
	public function setName($name)
	{
		$this->name = basename($name, ".md");
	}

	public function getName()
	{
		return $this->name;
	}

	public function getContent()
	{
		return $this->content;
	}

	public function getHtml()
	{
		return $this->parseContent();
	}

	public function exists()
	{
		return $this->exists;
	}

	/* ------------------------------------------------------------------------------------------------
	 |  Main Functions
	 | ------------------------------------------------------------------------------------------------
	 */
	private function loadContent($locale, $version)
	{
		if ( $this->isFileExists() && $this->isMarkdownFile() )
		{
			$this->exists	= true;

			$this->content	= File::get($this->path);

			$this->fixUrls($locale, $version);
		}
	}

	private function parseContent()
	{
		return with(new ParsedownExtra)->text($this->content);
	}

	private function fixUrls($locale, $version)
	{
		$url = implode('/', [
			Config::get('laravel-docs::config.prefix', 'docs'),
			$locale,
			$version
		]);

		$this->content = str_replace('(/docs/', '(/'. $url .'/', $this->content);
	}

	/* ------------------------------------------------------------------------------------------------
	 |  Check Functions
	 | ------------------------------------------------------------------------------------------------
	 */
	private function isFileExists()
	{
		return File::exists($this->path);
	}

	public function isMarkdownFile()
	{
		return File::isFile($this->path) && File::extension($this->path) == 'md';
	}

}