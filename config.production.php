<?php

return [
    'production' => true,
    'build' => [
        'source'      => 'content',
        'destination' => 'build_production',
    ],
    // on dev environment the url is in a subfolder, as we use github's default domain
    'baseUrl' => '/federation-card/'
];
