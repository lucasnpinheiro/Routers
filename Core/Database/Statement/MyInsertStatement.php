<?php

/**
 * @license MIT
 * @license http://opensource.org/licenses/MIT
 */

namespace Core\Database\Statement;

use Slim\PDO\Database;

/**
 * Class InsertStatement.
 *
 * @author Fabian de Laender <fabian@faapz.nl>
 */
class MyInsertStatement extends \Slim\PDO\Statement\InsertStatement {

    /**
     * Constructor.
     *
     * @param Database $dbh
     * @param array    $columns
     */
    public function __construct(Database $dbh, array $columns) {
        parent::__construct($dbh, $columns);
    }

}
