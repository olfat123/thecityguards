<?php
/*

    r9_add_panel('panel_id', 'My Panel');
    r9_add_section('section_id', 'My Section', 'panel_id');
    r9_add_field('text', 'my_text', 'My Text', 'section_id');
    r9_add_field('textarea', 'my_textarea', 'My Textarea', 'section_id');
    r9_add_field('editor', 'my_editor', 'My Editor', 'section_id');
    r9_add_field('image', 'my_image', 'My Image', 'section_id');
    r9_add_field('select', 'my_select', 'My Select', 'section_id', [
        'choices' => [
            'value_1' => 'item_1',
            'value_2' => 'item_2',
        ],
    ]);
    r9_add_field('repeater', 'my_repeater', 'My Repeater', 'section_id', [
        'fields' => [
            'my_repeater_text' => [
                'type' => 'text',
                'label' => 'Title',
            ],
            'my_repeater_textarea' => [
                'type' => 'textarea',
                'label' => 'Description',
            ],
        ],
        'row_label' => 'my item',
    ]);

 */

function r9_kirki_fields()
{
    if (!class_exists('Kirki\Panel')) {
        return;
    }
    // GENERAL
    r9_customizer_general();

    // HOME PAGE
    r9_customizer_home();

    //  ABOUT US
    r9_customizer_about();

    // CAREERS
    r9_customizer_careers();

    // CONTACT US
    r9_customizer_contact();
}

function r9_customizer_general()
{
    r9_add_panel('general', 'General');
    r9_add_section('logo', 'Logo', 'general');
    r9_add_field('image', 'white_logo', 'White Logo', 'logo');
    r9_add_field('image', 'colored_logo', 'Colored Logo', 'logo');
    r9_add_field('image', 'favicon', 'Favicon', 'logo');

    r9_add_section('contact_info', 'Contact Info', 'general');
    r9_add_field('text', 'phone', 'Phone', 'contact_info');
    r9_add_field('textarea', 'address', 'Address', 'contact_info');

    r9_add_section('social_links', 'Social Links', 'general');
    r9_add_field('text', 'facebook', 'Facebook', 'social_links');
    r9_add_field('text', 'twitter', 'Twitter', 'social_links');
    r9_add_field('text', 'linkedin', 'Linkedin', 'social_links');
    r9_add_field('text', 'instagram', 'Instagram', 'social_links');
    r9_add_field('text', 'youtube', 'Youtube', 'social_links');

    r9_add_section('footer', 'Footer', 'general');
    r9_add_field('textarea', 'footer_about', 'About', 'footer', [
        'multi_language' => true,
    ]);

    r9_add_section('default', 'Default', 'general');
    r9_add_field('image', 'default_header_background', 'Default Header Background', 'default');
}

function r9_customizer_home()
{
    r9_add_panel('home', 'Home Page');

    r9_add_section('home_intro', 'Intro Section', 'home');
    r9_add_field('image', 'home_intro_background', 'Background', 'home_intro');
    r9_add_field('text', 'home_intro_button_text', 'Button Text', 'home_intro', [
        'multi_language' => true,
    ]);
    r9_add_field('select', 'home_intro_button_link', 'Button Link', 'home_intro', [
        'choices' => r9_pages_for_customizer(),
    ]);
    r9_add_field('repeater', 'home_intro_slides', 'Slides', 'home_intro', [
        'fields' => [
            'title_en' => [
                'type' => 'text',
                'label' => 'Title [English]',
            ],
            'title_ar' => [
                'type' => 'text',
                'label' => 'Title [Arabic]',
            ],
            'content_en' => [
                'type' => 'textarea',
                'label' => 'Content [English]',
            ],
            'content_ar' => [
                'type' => 'textarea',
                'label' => 'Content [Arabic]',
            ],
        ],
        'row_label' => 'Slide',
    ]);
}

function r9_customizer_about()
{
    r9_add_panel('about', 'About Us');
    r9_add_section('about_mission_vision', 'Mission/Vision', 'about');
    r9_add_field('textarea', 'about_mission', 'Mission', 'about_mission_vision', [
        'multi_language' => true,
    ]);
    r9_add_field('textarea', 'about_vision', 'Vision', 'about_mission_vision', [
        'multi_language' => true,
    ]);
}

function r9_customizer_careers()
{
    r9_add_panel('careers', 'Careers');
}

function r9_customizer_contact()
{
    r9_add_panel('contact', 'Contact');

    r9_add_section('contact_form', 'Contact Form', 'contact');
    r9_add_field('text', 'contact_form_title', 'Title', 'contact_form', [
        'multi_language' => true,
    ]);
    // If there's a dropdown in the contact form
    r9_add_field('textarea', 'contact_form_subject', 'Options for "Subject"', 'contact_form', [
        'description' => 'Each option in new line',
        'multi_language' => true,
    ]);
}

add_action('init', 'r9_kirki_fields');
