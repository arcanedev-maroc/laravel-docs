<?php

/* ------------------------------------------------------------------------------------------------
 |  Locale Events
 | ------------------------------------------------------------------------------------------------
 */
$namespace = 'Arcanedev\\LaravelDocs\\Events\\';

Event::listen('docs.remember.locale', $namespace . 'LocaleHandler@remember');

Event::listen('docs.retrieve.locale', $namespace . 'LocaleHandler@retrieve');