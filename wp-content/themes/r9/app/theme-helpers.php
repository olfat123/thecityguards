<?php

use Carbon\Carbon;

function r9_get_option($option)
{
    $languages = r9_get_languages();
    $current_lang = $languages['current']['code'];
    $other_lang = $languages['another']['code'];
    if ($value = get_theme_mod($option . '_' . $current_lang)) {
        return $value;
    } elseif ($value = get_theme_mod($option . '_' . $other_lang)) {
        return $value;
    } elseif ($value = get_theme_mod($option)) {
        return $value;
    }
}

function r9_get_form_select_options($option, $placeholder)
{
    $options = array();
    $textareaContent = r9_get_option($option);
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
        $tag['raw_values'] = $tag['values'] = r9_get_form_select_options('contact_form_subject', __('Subject*', 'r9'));
    }

    return $tag;
}

add_filter('wpcf7_form_tag', 'cf7_dynamic_select_field', 10, 2);

function r9_pages()
{
    return [
        'about' => r9_get_current_object_id(0, 'page'),
        'blog' => r9_get_current_object_id(0, 'page'),
        'careers' => r9_get_current_object_id(0, 'page'),
        'contact' => r9_get_current_object_id(0, 'page'),
    ];
}

function r9_pages_for_customizer()
{
    $pages = r9_pages();
    return [
        $pages['about'] => 'About Us',
        $pages['careers'] => 'Careers',
        $pages['contact'] => 'Contact Us',
    ];
}

function r9_generate_embed_video($url = null)
{
    if ($url) {
        $video_id = explode('v=', $url);
        return 'https://www.youtube.com/embed/' . $video_id[1];
    }
    return null;
}

function r9_get_languages()
{
    $languages = apply_filters('wpml_active_languages', NULL);
    $data = [
        'current' => [
            'name' => 'English',
            'code' => 'en'
        ],
        'another' => [
            'name' => 'العربية',
            'code' => 'ar',
            'url' => home_url(),
        ],
    ];
    foreach ($languages as $language) {
        if ($language['code'] == ICL_LANGUAGE_CODE) {
            $data['current'] = [
                'name' => $language['native_name'],
                'code' => $language['code']
            ];
        } else {
            $data['another'] = [
                'name' => $language['native_name'],
                'code' => $language['code'],
                'url' => $language['url'],
            ];
        }
    }

    return $data;
}

function r9_get_current_object_id($id = null, $type = null)
{
    return apply_filters('wpml_object_id', $id ?: get_the_ID(), $type ?: get_post_type());
}

function r9_get_original_object_id($id = null, $type = null)
{
    return apply_filters('wpml_object_id', $id ?: get_the_ID(), $type ?: get_post_type(), false, 'en');
}

function r9_get_date($date = null, $format = 'j F Y')
{
    $date = $date ?: get_the_date('Y-m-d');
    return Carbon::parse(get_the_date('Y-m-d'))->translatedFormat('j F Y');
}
