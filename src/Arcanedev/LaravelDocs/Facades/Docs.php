<?php namespace Arcanedev\LaravelDocs\Facades;

use Illuminate\Support\Facades\Facade;

class Docs extends Facade
{
    protected static function getFacadeAccessor() { return 'lara-docs'; }
}