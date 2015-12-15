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

    //Function that returns array for timeline
    public function timelineArray()
    {
        /*$array = array(
            array('2000', 150),
            array('2001', 200),
            array('2002', 300),
            array('2004', 500),
            array('2005', 100)
        );*/

        $array = "[['2000', 150],['2001', 200], ['2002', 300], ['2004', 500],['2005', 100]]";

        /*$arrayLength = count($array);
        $jsString = "<script>var data = [";
        for ($row = 0; $row < $arrayLength; $row++) {
            $jsString .= "[" . $array[$row];
            //for ($col = 0; $col < 2; $col++) {
            if ($row == $arrayLength-1) {
                $jsString .= $array[$row][0] . ", " . $array[$row][1] . "]";
            }
            else {
                $jsString .= $array[$row][0] . ", " . $array[$row][1] . "],";
            }
        }
        $jsString .= "];</script>";
        return $jsString;*/

        //return  "<script>data.push(" . $array . ");</script>";
        echo "<script>var data = " . $array . ";</script>";

    }


}