<?php

namespace Anything;

use Illuminate\Foundation\AliasLoader as OriginalAliasLoader;

class AliasLoader extends OriginalAliasLoader
{
    /**
     * Create a new AliasLoader instance.
     *
     * @param  array  $aliases
     * @return void
     */
    private function __construct($aliases)
    {
		$this->classmap = require base_path('vendor/composer/autoload_classmap.php');
        $this->aliases = $aliases;
    }

    /**
     * Load a class alias if it is registered.
     *
     * @param  string  $alias
     * @return bool|null
     */
    public function load($alias)
    {
        if (static::$facadeNamespace && str_starts_with($alias, static::$facadeNamespace)) {
            $this->loadFacade($alias);

            return true;
        }

        if (isset($this->aliases[$alias])) {
            return class_alias($this->aliases[$alias], $alias);
        }

		if(array_key_exists($alias, $this->classmap)) return;
		if(class_exists($alias)) return;
		if(interface_exists($alias)) return;
		if(trait_exists($alias)) return;
		
		if(str_starts_with($alias, 'App\\')) {
			$path = base_path(str_replace('\\', DIRECTORY_SEPARATOR, $alias));
			if(is_file($path) || is_dir($path)) return;
			
			$alias_without_backslashes = str_replace('\\', '_', $alias);
			$content = str_replace(
				'class AnythingBuilder',
				'class AnythingBuilder_' . $alias_without_backslashes,
				file_get_contents(__DIR__ . '/AnythingBuilder.php')
			);
			$path = storage_path('framework/cache/AnythingBuilder_' . $alias_without_backslashes . '.php');
			file_put_contents($path, $content);

			require_once $path;
			class_alias('\Anything\AnythingBuilder_' . $alias_without_backslashes, $alias);
		}
    }

    /**
     * Get or create the singleton alias loader instance.
     *
     * @param  array  $aliases
     * @return \Illuminate\Foundation\AliasLoader
     */
    public static function getInstance(array $aliases = [])
    {
        if (is_null(static::$instance)) {
            return static::$instance = new static($aliases);
        }

        $aliases = array_merge(static::$instance->getAliases(), $aliases);

        static::$instance->setAliases($aliases);

        return static::$instance;
    }	
}