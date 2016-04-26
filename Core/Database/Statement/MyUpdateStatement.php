<?php

/**
 * @license MIT
 * @license http://opensource.org/licenses/MIT
 */

namespace Core\Database\Statement;

use Slim\PDO\Database;

/**
 * Class UpdateStatement.
 *
 * @author Fabian de Laender <fabian@faapz.nl>
 */
class MyUpdateStatement extends \Slim\PDO\Statement\UpdateStatement {

    /**
     * Constructor.
     *
     * @param Database $dbh
     * @param array    $pairs
     */
    public function __construct(Database $dbh, $pairs) {
        parent::__construct($dbh, $pairs);
    }

}
