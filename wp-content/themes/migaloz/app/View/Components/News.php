<?php

namespace App\View\Components;

use Carbon\Carbon;
use Roots\Acorn\View\Component;

class News extends Component
{
    public $news_id;
    public $title;
    public $date;
    public $image;
    public $link;

    public function __construct($id)
    {
        $this->news_id = $id;
        $this->title = get_the_title($this->news_id);
        $this->date = migaloz_get_date();
        $this->image = get_the_post_thumbnail_url($this->news_id);
        $this->link = get_the_permalink($this->news_id);
    }

    public function render()
    {
        return $this->view('components.news');
    }
}
