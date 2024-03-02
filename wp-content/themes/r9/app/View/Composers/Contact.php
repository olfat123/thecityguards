<?php

namespace App\View\Composers;

use Roots\Acorn\View\Composer;

class Contact extends Composer
{
    /**
     * List of views served by this composer.
     *
     * @var array
     */
    protected static $views = [
        '*template-contact*',
    ];

    /**
     * Data to be passed to view before rendering.
     *
     * @return array
     */
    public function with()
    {
        $contact_form_code = '[contact-form-7 id="4804926" title="Contact Us"]';
        return [
            'contact_form_code' => $contact_form_code
        ];
    }
}
