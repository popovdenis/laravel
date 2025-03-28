<?php

return [
    //Your custom User model
    //Change it to \App\User::class for previous laravel versions
    'user_model' => \App\Models\User::class,
    'reading_progress_bar' => true,
    'include_default_routes' => true,
    'blog_prefix' => "blog",
    'admin_prefix' => "blog_admin", // similar to above, but used for the admin panel for the blog. Default: blog_admin
    'use_custom_view_files' => false,
    'per_page' => 10, // how many posts to show per page on the blog index page. Default: 10
    'image_upload_enabled' => true, // true or false, if image uploading is allowed.
    'blog_upload_dir' => "blog_images",
    'memory_limit' => '2048M',
    'echo_html' => true, // default true
    'strip_html' => false, // Default: false.
    'auto_nl2br' => true, // Default: true.
    'use_wysiwyg' => true, // Default: true
    'image_quality' => 80,
    'image_sizes' => [
        'large' => [ // this key must start with 'image_'. This is what the DB column must be named
            'w' => 600, // width in pixels
            'h' => 400, //height
            'basic_key' => "large", // same as the main key, but WITHOUT 'image_'.
            'name' => "Large", // description, used in the admin panel
            'enabled' => true, // see note above
            'crop' => true,
        ],
        'medium' => [ // this key must start with 'image_'. This is what the DB column must be named
            'w' => 300, // width in pixels
            'h' => 150, //height
            'basic_key' => "medium",// same as the main key, but WITHOUT 'image_'.
            'name' => "Medium",// description, used in the admin panel
            'enabled' => true, // see note above
            'crop' => true,
        ],
        'thumbnail' => [ // this key must start with 'image_'. This is what the DB column must be named
            'w' => 50, // width in pixels
            'h' => 50, //height
            'basic_key' => "thumbnail",// same as the main key, but WITHOUT 'image_'.
            'name' => "Thumbnail",// description, used in the admin panel
            'enabled' => true, // see note above
        ],
    ],
    'captcha' => [
        'captcha_enabled' => true,
        'captcha_type' => \App\Blog\Captcha\Basic::class,
        'basic_question' => "What is the opposite of white?",
        'basic_answers' => "black,dark", // comma separated list of possible answers. Don't worry about case.
    ],
    'comments' => [
        'type_of_comments_to_show' => 'built_in', // default: built_in
        'max_num_of_comments_to_show' => 1000,
        'save_ip_address' => true, // Default: true
        'auto_approve_comments' => false, // default: false
        'save_user_id_if_logged_in' => true,
        'user_field_for_author_name' => "name",
        'ask_for_author_email' => true, // show 'author email' on the form ?
        'require_author_email' => false,
        'ask_for_author_website' => true, // show 'author website' on the form, show the link when viewing the comment
        'disqus' => [
            'src_url' => "https://GET_THIS_FROM_YOUR_EMBED_CODE.disqus.com/embed.js",
        ],
    ],
    'search' => [
        'search_enabled' => true, //you can easily turn off search functionality
        'limit-results' => 50,
        'enable_wildcards' => true,
        'weight' => [
            'title' => 1.5,
            'content' => 1,
        ],
    ],
    'show_full_text_at_list' => true,
];
