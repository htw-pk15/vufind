<?php

/**
 *
 * @category linked-swissbib
 * @package  Controller_Elasticsearch
 * @author   Guenter Hipler <guenter.hipler@unibas.ch>
 * @license  http://opensource.org/licenses/gpl-2.0.php GNU General Public License
 * @link     http://linked.swissbib.ch  Main Page
 */

namespace LinkedSwissbib\Controller;
use VuFind\Controller\AbstractSearch;

class SparqlController extends AbstractSearch
{

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->searchClassId = 'Sparql';
        parent::__construct();
    }


    public function searchAction ()
    {
        return parent::resultsAction();

    }
    /**
     * Convenience method for accessing results
     *
     * @return \LinkedSwissbib\Search\Results\PluginManager
     */
    protected function getResultsManager()
    {
        return $this->getServiceLocator()->get('LinkedSwissbib\SearchResultsPluginManager');
    }


}