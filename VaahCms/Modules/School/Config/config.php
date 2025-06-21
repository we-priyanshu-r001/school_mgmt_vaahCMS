<?php

return [
    "name"=> "School",
    "title"=> "School Management",
    "slug"=> "school",
    "thumbnail"=> "https://img.site/p/300/160",
    "is_dev" => env('MODULE_SCHOOL_ENV')?true:false,
    "excerpt"=> "School Management for Admin",
    "description"=> "School Management for Admin",
    "download_link"=> "",
    "author_name"=> "vaah",
    "author_website"=> "https://vaah.dev",
    "version"=> "0.0.1",
    "is_migratable"=> true,
    "is_sample_data_available"=> true,
    "db_table_prefix"=> "vh_school_",
    "providers"=> [
        "\\VaahCms\\Modules\\School\\Providers\\SchoolServiceProvider"
    ],
    "aside-menu-order"=> null
];
