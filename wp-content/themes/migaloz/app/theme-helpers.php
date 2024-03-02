<?php

use Carbon\Carbon;


function migaloz_get_form_select_options($option, $placeholder)
{
    $options = array();
    $textareaContent = $option;
    $optionsArray = explode("\n", $textareaContent);
    $optionsArray = array_map('trim', $optionsArray);
    $optionsArray = array_filter($optionsArray);
    $options[$placeholder] = $placeholder;
    if ($optionsArray && is_array($optionsArray)) {
        foreach ($optionsArray as $item) {
            $options[$item] = $item;
        }
    }
    return $options;
}

// Register CF7 dynamic select field
function cf7_dynamic_select_field($tag, $unused)
{
    if ($tag['name'] == 'subject') {
        $tag['raw_values'] = $tag['values'] = migaloz_get_form_select_options('contact_form_subject', __('Subject*', 'migaloz'));
    }

    return $tag;
}

add_filter('wpcf7_form_tag', 'cf7_dynamic_select_field', 10, 2);


function migaloz_generate_embed_video($url = null)
{
    if ($url) {
        $video_id = explode('v=', $url);
        return 'https://www.youtube.com/embed/' . $video_id[1];
    }
    return null;
}

function migaloz_get_date($date = null, $format = 'j F Y')
{
    $date = $date ?: get_the_date('Y-m-d');
    return Carbon::parse(get_the_date('Y-m-d'))->translatedFormat('j F Y');
}
