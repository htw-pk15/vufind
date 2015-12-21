<?php


/**
 *
 * @category linked-swissbib
 * @package  /
 * @author   Guenter Hipler <guenter.hipler@unibas.ch>
 * @license  http://opensource.org/licenses/gpl-2.0.php GNU General Public License
 * @link     http://linked.swissbib.ch  Main Page
 */


namespace Swissbib\Module\Config;




$config = [
    'router' => [
        'routes' => []
    ],
    'controllers' => [
        'invokables' => [
            'exploration'    => 'LinkedSwissbib\Controller\ElasticsearchController',
            'inference'    => 'LinkedSwissbib\Controller\SparqlController',

        ]
    ],
    'service_manager' => [
        'factories' => [
            'LinkedSwissbib\SearchOptionsPluginManager' => 'LinkedSwissbib\Service\Factory::getSearchOptionsPluginManager',
            'LinkedSwissbib\SearchParamsPluginManager' => 'LinkedSwissbib\Service\Factory::getSearchParamsPluginManager',
            'LinkedSwissbib\SearchResultsPluginManager' => 'LinkedSwissbib\Service\Factory::getSearchResultsPluginManager',
            'LinkedSwissbib\RecordDriverPluginManager' => 'LinkedSwissbib\Service\Factory::getRecordDriverPluginManager',

            ]

    ],
    'vufind' => [
        'plugin_managers' => [
            'search_backend'           => [
                'factories' => [
                    'ElasticSearch' => 'LinkedSwissbib\Search\Factory\ElasticSearchBackendFactory',
                ]
            ],


            'search_options' => [
                'abstract_factories' => ['LinkedSwissbib\Search\Options\PluginFactory'],
            ],
            'search_params' => [
                'abstract_factories' => ['LinkedSwissbib\Search\Params\PluginFactory'],
            ],
            'search_results' => [
                'abstract_factories' => ['LinkedSwissbib\Search\Results\PluginFactory'],
            ],
            'recorddriver'             => array(
                'abstract_factories' => ['LinkedSwissbib\RecordDriver\PluginFactory'],
                'factories' => array(
                    'elasticsearchRecordDriver'  => 'LinkedSwissbib\RecordDriver\Factory::getElasticSearchRdfRecordDriver',

                )
            ),


        ]
    ],
    'swissbib' => [

        //todo: do we need a single linkedSwissbib key for the plugin managers which should be merged with the swissbib key and in the
        // with the vufind keys
        //vufind_search_options seems to be deprecated now search_options
        //look it up (we need it for saved searches
        'plugin_managers' => [
            'vufind_search_options' => [
                'abstract_factories' => array('LinkedSwissbib\Search\Options\PluginFactory'),
            ],
            'vufind_search_params'  => [
                'abstract_factories' => array('LinkedSwissbib\Search\Params\PluginFactory'),
            ],
            'vufind_search_results' => [
                'abstract_factories' => array('LinkedSwissbib\Search\Results\PluginFactory'),
            ]
        ],
    ]



];


// Define static routes -- Controller/Action strings
$staticRoutes = [
    'Exploration/Search', 'Elasticsearch/Results', 'Sparql/Results', 'Exploration/Author', 'Exploration/AuthorDetails', 'Exploration/Work', 'Exploration/Work2'
    ];

$routeGenerator = new \VuFind\Route\RouteGenerator();
$routeGenerator->addStaticRoutes($config, $staticRoutes);


return $config;

