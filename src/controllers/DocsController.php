<?php namespace Arcanedev\LaravelDocs\Controllers;

use Illuminate\Support\Facades\Redirect	as Redirect;

use Arcanedev\LaravelDocs\Facades\Docs	as Docs;

class DocsController extends Base\BaseController
{
	/* ------------------------------------------------------------------------------------------------
	 |  Functions
	 | ------------------------------------------------------------------------------------------------
	 */
	public function welcome($locale)
	{
		if ( $this->checkIfSupportedLocale($locale) )
		{
			$this->loadAllDocs($locale);

			$this->display('welcome');
		}
		else
			return Redirect::route('arcanedev.docs.travel');
	}

	public function index($locale, $version)
	{
		return $this->show($locale, $version, 'introduction');
	}

	public function show($locale, $version, $slug = 'introduction')
	{
		if ( $this->checkIfSupportedLocale($locale) )
		{
			if ( $this->docsManager->checkDoc($locale, $version, $slug) )
			{
				$this->loadAllDocs($locale);
				$this->data['doc']				= $this->docsManager->getDoc($locale, $version, $slug);
				$this->data['selectedVersion']	= $version;

				$this->display('preview');
			}
			else
				return Redirect::route('arcanedev.docs.index', [$locale, $version]);
		}
		else
			return Redirect::route('arcanedev.docs.travel');
	}
}