<?php namespace Arcanedev\LaravelDocs\Services\Docs\Entities;

use Illuminate\Support\Collection		as Collection;
use Illuminate\Support\Facades\Config	as Config;
use Illuminate\Support\Facades\Lang		as Lang;

class DocsCollection extends Collection
{
	/* ------------------------------------------------------------------------------------------------
	 |  Properties
	 | ------------------------------------------------------------------------------------------------
	 */
	protected $locale;

	protected $version;

	protected $tableOfContents = [];

	/* ------------------------------------------------------------------------------------------------
	 |  Constructor
	 | ------------------------------------------------------------------------------------------------
	 */
	function __construct($locale, $version, $files)
	{
		$this->locale	= $locale;
		$this->version	= $version;

		$this->loadTableOfContents();

		foreach ($files as $file)
		{
			$doc = new Doc($locale, $version, $file);

			$this->put($doc->getName(), $doc);
		}
	}

	/* ------------------------------------------------------------------------------------------------
	 |  Getters & Setters
	 | ------------------------------------------------------------------------------------------------
	 */
	public function getLocale()
	{
		return $this->locale;
	}

	public function getVersion()
	{
		return $this->version;
	}

	public function getTableOfContent()
	{
		return $tableOfContents;
	}

	public function getTableOfContentLinks()
	{
		return $this->getTOCLinks();
	}

	public function getTOCLinks()
	{
		return $this->prepareTableOfContents();
	}

	/* ------------------------------------------------------------------------------------------------
	 |  Main Functions
	 | ------------------------------------------------------------------------------------------------
	 */
	public function getDoc($locale, $version, $slug)
	{
		if ( $this->hasDoc($locale, $version, $slug) )
		{
			return $this->get($slug);
		}

		return null;
	}

	private function loadTableOfContents()
	{
		$version	= str_replace('.', '_', $this->version);

		$this->tableOfContents	= Config::get('laravel-docs::table-of-contents.' . $version, []);
	}

	private function prepareTableOfContents()
	{

		$tableOfContents = [];

		foreach ($this->tableOfContents as $title => $links)
		{
			foreach ($links as $key)
			{
				$link = \HTML::linkRoute('arcanedev.docs.show', $this->translateLink($key), [$this->locale, $this->version, $key], []);

				$tableOfContents[$this->translateTitle($title)][$key] = $link;
			}
		}

		return $tableOfContents;
	}

	/* ------------------------------------------------------------------------------------------------
	 |  Translation Functions
	 | ------------------------------------------------------------------------------------------------
	 */
	private function translateTitle($title)
	{
		return $this->translate('titles.' . $title);
	}

	private function translateLink($link)
	{
		return $this->translate('links.' . $link);
	}

	private function translate($key)
	{
		return Lang::trans('laravel-docs::table-of-contents.' . $key, [], [], $this->locale);
	}

	/* ------------------------------------------------------------------------------------------------
	 |  Check Functions
	 | ------------------------------------------------------------------------------------------------
	 */
	public function isLocale($locale)
	{
		return $this->assertLocale($locale);
	}

	private function assertLocale($locale)
	{
		return $this->locale === $locale;
	}

	private function assertVersion($version)
	{
		return $this->version === $version;
	}

	private function assertSlug($slug)
	{
		return $this->has($slug);
	}

	public function hasDoc($locale, $version, $slug)
	{
		return $this->assertLocale($locale) && $this->assertVersion($version) && $this->has($slug);
	}
}