<?php

namespace App\Http\Traits;

use Illuminate\Support\Facades\Gate;

trait Accessable {

    /**
     * @param ...$checks
     * @return bool
     */
    protected function anyOfMany(...$checks): bool
    {
        $checks = func_get_args();

        foreach ($checks as $check) {
            if ($check) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param ...$checks
     * @return bool
     */
    protected function allOfMany(...$checks): bool
    {
        $counter = 0;

        $checks = func_get_args();

        foreach ($checks as $check) {
            if ($check) {
                $counter++;
            }
        }

        return $counter == count($checks);
    }
}
