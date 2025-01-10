<?php

namespace Teufelaudio\Zed\SprykerDebug\Communication\Model\Propel;

use Propel\Runtime\Map\DatabaseMap;
use Propel\Runtime\Map\Exception\TableNotFoundException;

class TablesFactory
{
    /**
     * @var \Propel\Runtime\Map\DatabaseMap
     */
    private $map;

    /**
     * @var \Teufelaudio\Zed\SprykerDebug\Communication\Model\Propel\TableNameFinder
     */
    private $finder;

    public function __construct(DatabaseMap $map, TableNameFinder $finder)
    {
        $this->map = $map;
        $this->finder = $finder;
    }

    public function createTables(): Tables
    {
        $names = $this->finder->findEntityNames();

        return new Tables(array_filter(array_map(function (string $name) {
            try {
                return $this->map->getTableByPhpName($name);
            } catch (TableNotFoundException $notFound) {
                return null;
            }
        }, $names)));
    }
}
