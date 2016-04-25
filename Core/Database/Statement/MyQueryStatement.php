<?php

/**
 * @license MIT
 * @license http://opensource.org/licenses/MIT
 */

namespace Core\Database\Statement;

use Slim\PDO\Database;

/**
 * Class SelectStatement.
 *
 * @author Fabian de Laender <fabian@faapz.nl>
 */
class MyQueryStatement extends \Slim\PDO\Statement\SelectStatement {

    private $sql = null;
    /**
     * Constructor.
     *
     * @param Database $dbh
     * @param array    $columns
     */
    public function __construct(Database $dbh, $sql = null) {
        parent::__construct($dbh,[]);
        $this->sql = $sql;
    }

    public function __toString() {
        return $this->sql;
    }

    public function debug() {
        debug($this->__toString());
    }

}
