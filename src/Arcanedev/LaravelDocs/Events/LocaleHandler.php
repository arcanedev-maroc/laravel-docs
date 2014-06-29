<?php namespace Arcanedev\LaravelDocs\Events;

use Illuminate\Support\Facades\Config	as Config;
use Illuminate\Support\Facades\Session	as Session;

class LocaleHandler
{
	/* ------------------------------------------------------------------------------------------------
	 |  Event Functions
	 | ------------------------------------------------------------------------------------------------
	 */
	public function remember($locale)
	{
		if ( $this->useSessions() )
		{
			if (
				(
					$this->isLocalized()
					&& $locale !== $this->getSession( Config::get('app.locale') )
				)
				|| ! $this->isLocalized()
			)
			{
				Session::put('laravel_docs_locale', $locale);
			}
		}

		return $locale;
	}

	public function retrieve($locale)
	{
		if (
			$this->useSessions()
			&& ! $this->isLocalized()
		)
		{
			$this->remember($locale);
		}
		else
		{
			$locale = $this->getSession( $locale );
		}

		return $locale;
	}

	/* ------------------------------------------------------------------------------------------------
	 |  Check Functions
	 | ------------------------------------------------------------------------------------------------
	 */
	private function getSession($default = null)
	{
		return Session::get('laravel_docs_locale', $default);
	}

	private function useSessions()
	{
		return Config::get('laravel-docs::config.use-sessions', false);
	}

	private function isLocalized()
	{
		return Session::has('laravel_docs_locale');
	}
}
