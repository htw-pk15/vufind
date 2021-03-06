<?php

/**
 *
 * @category linked-swissbib
 * @package  Backend_Eleasticsearch
 * @author   Guenter Hipler <guenter.hipler@unibas.ch>
 * @license  http://opensource.org/licenses/gpl-2.0.php GNU General Public License
 * @link     http://linked.swissbib.ch  Main Page
 */

namespace LinkedSwissbib\Backend\Elasticsearch\DSLBuilder\Query;

use LinkedSwissbib\Backend\Elasticsearch\DSLBuilder\UserQueryAwareInterface;

interface ESQueryInterface extends UserQueryAwareInterface
{
    public function build();
    public function getName();

}