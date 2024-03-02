<?php

namespace App\View\Components;

use Roots\Acorn\View\Component;

class Vacancy extends Component
{
    public $vacancy_id;
    public $title;
    public $department;
    public $link;

    public function __construct($id)
    {
        $this->vacancy_id = $id;
        $this->title = get_the_title($this->vacancy_id);
        $this->link = get_the_permalink($this->vacancy_id);
    }

    public function render()
    {
        return $this->view('components.vacancy');
    }
}
