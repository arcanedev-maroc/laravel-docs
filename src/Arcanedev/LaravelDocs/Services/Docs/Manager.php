<?php namespace Arcanedev\LaravelDocs\Services\Docs;

use Illuminate\Support\Facades\Config	as Config;
use Illuminate\Support\Facades\File		as File;

class Manager
{
	/* ------------------------------------------------------------------------------------------------
	 |  Properties
	 | ------------------------------------------------------------------------------------------------
	 */
	protected $docs;

	protected $directories = [];

	protected $locales;

	protected $supportedLocales;

	/* ------------------------------------------------------------------------------------------------
	 |  Constructor
	 | ------------------------------------------------------------------------------------------------
	 */
	public function __construct($directory)
	{
		$this->setDirectory($directory);

		$this->init();
	}

	public function init()
	{
		$this->supportedLocales		= Config::get('laravel-docs::config.locales', ['en', 'fr']);

		$this->supportedVersions	= Config::get('laravel-docs::config.versions', ['4.2', '4.1', '4.0']);

		$this->loadDocuments();
	}

	/* ------------------------------------------------------------------------------------------------
	 |  Getters & Setters
	 | ------------------------------------------------------------------------------------------------
	 */
	public function setDirectory($directory)
	{
		if ( ! in_array($directory, $this->directories ) )
		{
			$this->directories[] = $directory;
		}
	}

	public function getInstance()
	{
		return $this;
	}

	public function getLocales()
	{
		return $this->locales;
	}

	/* ------------------------------------------------------------------------------------------------
	 |  Functions
	 | ------------------------------------------------------------------------------------------------
	 */
	public function loadDocuments()
	{
		foreach ($this->directories as $directory)
		{
			$directories = File::directories($directory);

			foreach ($directories as $lang)
			{
				$locale = basename($lang);

				if ( strlen($locale) == 2 && in_array($locale, $this->supportedLocales) )
				{
					$this->locales[] = $locale;

					$versions = File::directories($lang);

					foreach ($versions as $vers)
					{
						$version	= basename($vers);

						if ( in_array($version, $this->supportedVersions) )
						{
							$files		= File::files($vers);

							if ( !empty($files) )
							{
								$this->docs[] = new Entities\DocsCollection($locale, $version, $files);
							}
						}
					}
				}
			}
		}
	}

	public function getDoc($locale, $version, $slug)
	{
		return $this->getFile($locale, $version, $slug);
	}

	public function getDocs($locale, $version = '')
	{
		$docs  = [];

		foreach ($this->docs as $docCollection)
		{
			if ( $docCollection->isLocale($locale) )
			{
				if (
					! empty($version) && $docCollection->isVersion($version)
					|| empty($version)
				)
				{
					$docs[$docCollection->getVersion()] = $docCollection;
				}
			}
		}

		krsort($docs);

		return $docs;
	}

	private function getFile($locale, $version, $slug)
	{
		foreach ($this->docs as $docCollection)
		{
			if ( $docCollection->hasDoc($locale, $version, $slug) )
			{
				return $docCollection->getDoc($locale, $version, $slug);
			}
		}

		return null;
	}

	/* ------------------------------------------------------------------------------------------------
	 |  Check Functions
	 | ------------------------------------------------------------------------------------------------
	 */
	public function checkDoc($locale, $version, $slug)
	{
		return ! is_null($this->getFile($locale, $version, $slug));
	}
}