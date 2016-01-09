<?php

/**
 *
 * @category linked-swissbib
 * @package  Search_Elasticsearch_Results
 * @author   Guenter Hipler <guenter.hipler@unibas.ch>
 * @license  http://opensource.org/licenses/gpl-2.0.php GNU General Public License
 * @link     http://linked.swissbib.ch  Main Page
 */

namespace LinkedSwissbib\Search\Elasticsearch;


use VuFind\Search\Base\Results as BaseResults;

class Results extends BaseResults
{

    /**
     * Search backend identifiers.
     *
     * @var string
     */
    protected $backendId = 'ElasticSearch';


    /**
     * Returns the stored list of facets for the last search
     *
     * @param array $filter Array of field => on-screen description listing
     * all of the desired facet fields; set to null to get all configured values.
     *
     * @return array        Facets data arrays
     */
    public function getFacetList($filter = null)
    {
        // TODO: Implement getFacetList() method.
    }

    /**
     * Abstract support method for performAndProcessSearch -- perform a search based
     * on the parameters passed to the object.  This method is responsible for
     * filling in all of the key class properties: results, resultTotal, etc.
     *
     * @return void
     */
    protected function performSearch()
    {
        $query  = $this->getParams()->getQuery();
        $limit  = $this->getParams()->getLimit();
        $offset = $this->getStartRecord() - 1;
        $params = $this->getParams()->getBackendParameters();
        $searchService = $this->getSearchService();

        try {
            $collection = $searchService
                ->search($this->backendId, $query, $offset, $limit, $params);
        } catch (\VuFindSearch\Backend\Exception\BackendException $e) {
            throw $e;
        }

        // Construct record drivers for all the items in the response:
        //todo: by now we don't get any result
        $this->results = $collection->getRecords();
    }

    /*Function that returns array required for timeline as a multiple
    indexed array; the parameter $yearArray is an array of all the fields
    "dct:issued" in the result list. It is created and transmitted in
    authordetails.phtml */

    public function timelineArray($yearArray)
    {
        $sortArray = sort($yearArray); //sorts the array in ascending order
        $timelineArrayAssoc = array_count_values($yearArray);
        /* creates an associative array with publication years as keys and number of
        publications as values */
        $timelineData = [];
        foreach($timelineArrayAssoc as $year => $pubNumber) {
            array_push($timelineData, [$year, $pubNumber]);
        }
        $timelineString = "[";
        $timelineLength = count($timelineData);
        for ($i = 0; $i < $timelineLength; $i++) {
            if ($i == $timelineLength - 1) {
                $timelineString .= "{data:[[" . $timelineData[$i][0] .
                    "," . $timelineData[$i][1] . "]]}]";
            }
            else {
                $timelineString .= "{data:[[" . $timelineData[$i][0] .
                    "," . $timelineData[$i][1] . "]]},";
            }
        }
        echo "<script>var data = " . $timelineString . ";</script>";
    }


}