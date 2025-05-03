<?php
/**
 * @package ${NAMESPACE}
 */
return [
    'types' => [
        [
            'cache_type' => 'Configuration',
            'command' => 'config:cache',
            'description' => 'Cache configuration files',
            'tag' => 'framework',
            'status' => true,
        ],
        [
            'cache_type' => 'Routing',
            'command' => 'route:clear',
            'description' => 'Clear cached routes',
            'tag' => 'routing',
            'status' => true,
        ],
    ],
];
