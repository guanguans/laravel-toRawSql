<?php

/*
 * This file is part of the guanguans/laravel-raw-sql.
 *
 * (c) guanguans <ityaozm@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled.
 */

namespace Guanguans\LaravelRawSql;

use Illuminate\Database\Query\Builder as QueryBuilder;
use Illuminate\Database\Eloquent\Builder as EloquentBuilder;

class ServiceProvider extends \Illuminate\Support\ServiceProvider
{
    /**
     * Perform post-registration booting of services.
     */
    public function boot()
    {
        $this->registerToRawSqlMacro();
    }

    /**
     * Register the `toRawSql` macro.
     */
    protected function registerToRawSqlMacro()
    {
        QueryBuilder::macro('toRawSql', function () {
            return array_reduce($this->getBindings(), function ($sql, $binding) {
                return preg_replace('/\?/', is_numeric($binding) ? $binding : "'".$binding."'", $sql, 1);
            }, $this->toSql());
        });

        EloquentBuilder::macro('toRawSql', function () {
            return ($this->getQuery()->toRawSql());
        });
    }

    /**
     * Register the provider.
     */
    public function register()
    {
    }
}
