<?php

return [

    '_source' => [
        'enabled' => true
    ],

    '_meta' => [
        'version' => 0.06,
        'updated' => 'Jan 02, 2016 01:15 AM',
        'languages' => [
            'en' => 'English',
            'bn' => 'Bengali'
        ],
        'defaults' => [
            'language' => 'en'
        ]
    ],

    'properties' => [
        'refs' => [
            'type' => 'object',
            'include_in_all' => false,
            'properties' => [
                'release_id' => [ 'type' => 'integer' ],
                'slug' => [ 'type' => 'string' ],
                'publisher_ref_no' => [ 'type' => 'string' ]
            ]
        ],
        'name' => [
            'type' => 'string',
            'fields' => [
                'name_en' => [ 'type' => 'string', 'analyzer' => 'english' ],
                'name_bn' => [ 'type' => 'string', 'analyzer' => 'standard' ],
            ]
        ],
        'summary' =>   [
            'type' => 'string',
            'fields' => [
                'summary_en' => [ 'type' => 'string', 'analyzer' => 'english' ],
                'summary_bn' => [ 'type' => 'string', 'analyzer' => 'standard' ],
            ]
        ],
        'house' =>   [ 'type' => 'string', 'include_in_all' => false ],
        'publisher_ref' =>   [ 'type' => 'string', 'include_in_all' => false ],
        'release_type' =>   [ 'type' => 'string', 'include_in_all' => true ],
        'release_year' =>   [ 'type' => 'date', 'format' => 'year', 'include_in_all' => true ],
        'labels' => [
            'type' => 'string',
            'include_in_all' => true,
            'fields' => [
                'labels_en' => [ 'type' => 'string', 'analyzer' => 'english' ],
                'labels_bn' => [ 'type' => 'string', 'analyzer' => 'standard' ]
            ]
        ],
        'scores' => [
            'type' => 'nested',
            'include_in_all' => false,
            'include_in_parent' => true,
            'include_in_root' => false,
            'properties' => [
                'authority' => [ 'type' => 'string' ],
                'source' => [ 'type' => 'string' ],
                'score' => [ 'type' => 'double' ],
                'max_score' => [ 'type' => 'integer' ],
                'updated' => [ 'type' => 'date', 'format' => 'yyyy-MM-dd HH:mm:ss' ],
                'note' => [ 'type' => 'string' ]
            ]
        ],

        'released_date' => [ 'type' => 'date', 'format' => 'yyyy-MM-dd', 'include_in_all' => false ],
        'release_status' => [ 'type' => 'string', 'include_in_all' => false ],

        'grouping' => [ 'type' => 'string', 'include_in_all' => false ],
        'series' => [
            'type' => 'nested',
            'properties' => [
                'title' => [
                    'type' => 'string',
                    'fields' => [
                        'title_en' => [ 'type' => 'string', 'analyzer' => 'english' ],
                        'title_bn' => [ 'type' => 'string', 'analyzer' => 'standard' ]
                    ]
                ],
                'note' => [
                    'type' => 'string',
                    'fields' => [
                        'note_en' => [ 'type' => 'string', 'analyzer' => 'english' ],
                        'note_bn' => [ 'type' => 'string', 'analyzer' => 'standard' ]
                    ]
                ],
                'season' => [ 'type' => 'integer' ],
                'serial' => [ 'type' => 'integer' ],
                'airtime' => [ 'type' => 'string' ],
                'video_id' => [ 'type' => 'integer' ]
            ]
        ],

        'peoples' => [
            'type' => 'nested',
            'properties' => [
                'people_id' => [ 'type' => 'integer' ],
                'name' => [
                    'type' => 'string',
                    'fields' => [
                        'name_en' => [ 'type' => 'string', 'analyzer' => 'english' ],
                        'name_bn' => [ 'type' => 'string', 'analyzer' => 'standard' ]
                    ]
                ],
                'role' => [
                    'type' => 'string',
                    'fields' => [
                        'role_en' => [ 'type' => 'string', 'analyzer' => 'english' ],
                        'role_bn' => [ 'type' => 'string', 'analyzer' => 'standard' ]
                    ]
                ],
                'photo' => [ 'type' => 'string' ],
                'alias' => [
                    'type' => 'string',
                    'fields' => [
                        'alias_en' => [ 'type' => 'string', 'analyzer' => 'english' ],
                        'alias_bn' => [ 'type' => 'string', 'analyzer' => 'standard' ]
                    ]
                ],
                'order' => [ 'type' => 'string' ],
                'cast' => [ 'type' => 'boolean' ]
            ]
        ],

        'filing_tags' => [ 'type' => 'string', 'include_in_all' => false ],
        'context' => [ 'type' => 'string', 'include_in_all' => false ]

    ]



];