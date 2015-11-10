<?php
/**
 *
 * @category linked-swissbib
 * @package  RecordDriver
 * @author   Guenter Hipler <guenter.hipler@unibas.ch>
 * @license  http://opensource.org/licenses/gpl-2.0.php GNU General Public License
 * @link     http://linked.swissbib.ch  Main Page
 */

namespace LinkedSwissbib\RecordDriver;
use VuFind\RecordDriver\AbstractBase;

class ElasticSearchRDF extends AbstractBase {

    protected $propertiesToNameSpaces = [
        'rdf:type'  => 'http://www.w3.org/1999/02/22-rdf-syntax-ns#type',
        'dc11:format'   => 'http://purl.org/dc/elements/1.1/format',
        'dc:hasPart'    => 'http://purl.org/dc/terms/hasPart',
        'dc:issued' =>  'http://purl.org/dc/terms/issued',
        'dc:language'   =>  'http://purl.org/dc/terms/language',
        'dc:title'  =>  'http://purl.org/dc/terms/title',
        'bibo:isbn10'   =>  'http://purl.org/ontology/bibo/isbn10',
        'bibo:isbn13'   =>  'http://purl.org/ontology/bibo/isbn13',
        'ns0:contentType'   =>  'http://rdaregistry.info/Elements/u/contentType',
        'ns0:mediaType' =>  'http://rdaregistry.info/Elements/u/mediaType',
        'ns0:noteOnResource'    =>  'http://rdaregistry.info/Elements/u/noteOnResource',
        'ns0:placeOfPublication'    =>  'http://rdaregistry.info/Elements/u/placeOfPublication',
        'ns0:publicationStatement'  =>  'http://rdaregistry.info/Elements/u/publicationStatement',
        'rdfs:isDefinedBy'  =>  'http://www.w3.org/2000/01/rdf-schema#isDefinedBy'

    ];


    /**
     * Get text that can be displayed to represent this record in breadcrumbs.
     *
     * @return string Breadcrumb text to represent this record.
     */
    public function getBreadcrumb()
    {
        throw new \Exception(__FUNCTION__ . ' currently not supported');
    }

    /**
     * Return the unique identifier of this record for retrieving additional
     * information (like tags and user comments) from the external MySQL database.
     *
     * @return string Unique identifier.
     */
    public function getUniqueID()
    {
        return $this->fields['_source']['@id'];
    }


    public function getRdf($rdfType = 'turtle')
    {

        $rdfGraph = new \EasyRdf_Graph();
        try {
            $rdfGraph->parse(json_encode($this->fields['_source']), 'jsonld');
            return $rdfGraph->serialise($rdfType);
        } catch (\Exception $ex) {
            return "";
        }

    }


    public function getRdfType()
    {
        if (isset($this->fields['_source']['@type']))
        {
            return is_array($this->fields['_source']['@type']) ? $this->fields['_source']['@type'] :
                [$this->fields['_source']['@type']];
        } else {
            return [];
        }


    }

    public function getLanguage()
    {
        if (isset($this->fields['_source']['dct:language']))
        {
            return is_array($this->fields['_source']['dct:language']) ? array_values($this->fields['_source']['dct:language']) :
                [$this->fields['_source']['dct:language']];
        } else {
            return [];
        }

    }

    public function getFirstNameContributor()
    {
        return $this->fields['_source']['foaf:firstName'];
    }

    public function getLastNameContributor()
    {
        return $this->fields['_source']['foaf:lastName'];
    }

/*    public function getFirstNameTest()
    {
        if (isset($this->fields['_source']['dc:contributor']['foaf:Person']['foaf:firstName']))
        {
            return is_array($this->fields['_source']['dc:contributor']) ? array_values($this->fields['_source']['dc:contributor']) :
                [$this->fields['_source']['dc:contributor.foaf:Person.foaf:firstName']];
        } else {
            echo "Fehler";
        }

    }*/

    public function getFirstNameResources()
    {
        return $this->fields['_source']['dc:contributor']['foaf:Person']['foaf:firstName'];
    }

    public function getLastNameResources()
    {
        return $this->fields['_source']['dc:contributor']['foaf:Person']['foaf:lastName'];
    }

    public function getPublicationStatement()
    {
        return $this->fields['_source']['rdau:publicationStatement'];
    }

    public function getFormat()
    {
        return $this->fields['_source']['dc:format'];
    }

    public function getTitle()
    {
        return $this->fields['_source']['dct:title'];
    }

    public function getCover()
    {
       $about = $this->fields['_source']['rdfs:isDefinedBy']['@id'];
       $id = substr($about, 33, 9);
       $url_start = 'https://resources.swissbib.ch/Cover/Show?isn=';
       $url_end = '&size=small';
       $link_cover = $url_start.$id.$url_end;
       return $link_cover;
    }

    public function getISBN10()
    {
       if (isset($this->fields['_source']['bibo:isbn10']))
           {
            $isbn10 = $this->fields['_source']['bibo:isbn10'];
            $url_start = 'https://resources.swissbib.ch/Cover/Show?isn=';
            $url_end = '&size=small';
            $link_cover = $url_start.$isbn10.$url_end;
            return $link_cover;
    } else {
            echo "-";
        }
    }

}