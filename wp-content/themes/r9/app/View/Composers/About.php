<?php

namespace App\View\Composers;

use Roots\Acorn\View\Composer;

class About extends Composer
{
    /**
     * List of views served by this composer.
     *
     * @var array
     */
    protected static $views = [
        '*template-about*',
    ];

    /**
     * Data to be passed to view before rendering.
     *
     * @return array
     */
    public function with()
    {
        return [];
    }
}
