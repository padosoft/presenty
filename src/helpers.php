<?php
/**
 * Copyright (c) Padosoft.com 2018.
 */

if (!function_exists('presenty')) {
    /**
     * Alias Object Presenty
     *
     * @return object
     */
    function presenty($string)
    {
        return \Padosoft\Presenty\Presenty::create($string);
    }
}
