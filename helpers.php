<?php

use Symfony\Component\VarDumper\Cloner\VarCloner;
use Symfony\Component\VarDumper\Dumper\CliDumper;

if (! function_exists('dump')) {
    /**
     * Dump a value with elegance.
     *
     * @param  mixed $value
     * @return void
     */
    function dump($value)
    {
        if (class_exists(CliDumper::class)) {
            (new CliDumper)->dump((new VarCloner)->cloneVar($value));
        } else {
            var_dump($value);
        }
    }
}

if (! function_exists('dd')) {
    /**
     * Dump the passed values and end the script.
     *
     * @param  mixed $value
     * @return void
     */
    function dd()
    {
        array_map('dump', func_get_args());
        die(1);
    }
}
