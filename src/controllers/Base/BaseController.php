<?php namespace Arcanedev\LaravelDocs\Controllers\Base;

use Illuminate\Routing\Controller		as Controller;

use Illuminate\Support\Facades\App		as App;
use Illuminate\Support\Facades\Config	as Config;
use Illuminate\Support\Facades\Event	as Event;
use Illuminate\Support\Facades\Redirect	as Redirect;
use Illuminate\Support\Facades\View		as View;

class BaseController extends Controller
{
	/* ------------------------------------------------------------------------------------------------
	 |  Properties
	 | ------------------------------------------------------------------------------------------------
	 */
	protected $layout;

	protected $data	= [];

	/* ------------------------------------------------------------------------------------------------
	 |  Constructor
	 | ------------------------------------------------------------------------------------------------
	 */
	public function __construct()
	{
		$this->loadLayout();

		$this->docsManager	= App::make('lara-docs')->getInstance();

		$this->initDatas();
	}

	private function initDatas()
	{
		$this->data['allDocs']			= [];

		$this->data['content']			= '';

		$this->data['selectedLocale']	= $this->getLastLocale();

		$this->data['selectedVersion']	= '';

		$this->data['supportedLocales']	= $this->docsManager->getLocales();
	}

	/* ------------------------------------------------------------------------------------------------
	 |  Core Functions
	 | ------------------------------------------------------------------------------------------------
	 */
	private function loadLayout()
	{
		$this->layout	= $this->getConfig('laravel-docs::config.layout', 'laravel-docs::_templates.layout');
	}

	protected function setupLayout()
	{
		if ( ! is_null($this->layout) )
		{
			$this->layout = View::make($this->layout);
		}
	}

	public function display($view)
	{
		$view = 'laravel-docs::' . $view;

		$this->layout->with($this->data)->nest('content', $view, $this->data);
	}

	protected function loadAllDocs($locale)
	{
		$this->data['allDocs'] = $this->docsManager->getDocs($locale);
	}

	/* ------------------------------------------------------------------------------------------------
	 |  Localization Functions
	 | ------------------------------------------------------------------------------------------------
	 */
	public function getDefaultLocale()
	{
		return $this->getConfig('app.locale', 'en');
	}

	public function getLastLocale()
	{
		$locale = Event::fire('docs.retrieve.locale', $this->getDefaultLocale());

		$locale = is_array($locale) ? head($locale) : $locale;

		$this->setLocale($locale);

		return $locale;
	}

	public function getSupportedLocale()
	{
		if ( in_array($this->getDefaultLocale(), $this->getAllSupportedLocales()) )
		{
			return $this->getDefaultLocale();
		}
		else
			return head( $this->getAllSupportedLocales() );
	}

	public function getAllSupportedLocales()
	{
		$locales = $this->getConfig('laravel-docs::config.locales', []);

		if ( is_array($locales) && ! empty($locales) )
		{
			return $locales;
		}
		else
			return App::abort(500, 'The supported locales must be an array & not empty');
	}

	public function setLocale($locale)
	{
		$this->data['selectedLocale']	= $locale;

		$this->setConfig('app.locale', $locale);
		\Lang::setLocale($locale);
	}

	public function rememberLang($locale)
	{
		Event::fire('docs.remember.locale', [$locale]);

		$this->setLocale($locale);
	}

	protected function checkIfSupportedLocale($locale)
	{
		$supportedLocales 	= $this->getConfig('laravel-docs::config.locales', ['en', 'fr']);
		$supported			= in_array($locale, $supportedLocales);

		if ( $supported )
			$this->rememberLang($locale);

		return $supported;
	}

	/* ------------------------------------------------------------------------------------------------
	 |  Other stuff
	 | ------------------------------------------------------------------------------------------------
	 */
	public function getConfig($key, $default = null)
	{
		return Config::get($key, $default);
	}

	public function setConfig($key, $value)
	{
		Config::set($key, $value);
	}

	public function flyAway()
	{
		$locale = $this->getLastLocale();

		return Redirect::route('arcanedev.docs.welcome', $locale);
	}
}