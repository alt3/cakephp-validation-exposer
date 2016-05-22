<?php
namespace Alt3\ValidationExposer\Lib;

use Cake\Datasource\ConnectionManager;
use Cake\Network\Exception\InternalErrorException;
use Cake\ORM\TableRegistry;
use Cake\Utility\Hash;

/**
 * Class library to help expose a list of all validation rules in an application.
 */
class ValidationExposer
{
    /**
     * Array with merged configuration settings.
     *
     * @var array
     */
    protected $_config = [
        'tableExclusions' => [
            'phinxlog'
        ]
    ];

    /**
     * Flat array holding all tables found in the application, minus exclusions.
     *
     * @var array
     */
    protected $_tables;

    /**
     * @var string $_excludedTables Flat array with table names to exclude.
     */
    protected $_excludedTables;

    /**
     * Class constructor.
     *
     * @param array $config Configuration options
     */
    public function __construct($config = [])
    {
        $this->_config = Hash::merge($this->_config, $config);
        $this->_excludedTables = $this->_config['tableExclusions'];
        $this->_tables = $this->_getApplicationTables();
    }

    /**
     * Returns an array with all tables included in validation rule aggregation.
     *
     * @return array
     */
    public function tables()
    {
        return $this->_tables;
    }

    /**
     * Returns an array with all tables excluded from validation rule aggregation.
     *
     * @return mixed|string
     */
    public function excludedTables()
    {
        return $this->_excludedTables;
    }

    /**
     * Returns an array with validation rules for all tables.
     *
     * @return array Array holding validation rules
     */
    public function applicationRules()
    {
        // process rules per table
        $result = [];
        foreach ($this->_tables as $key => $table) {
            $tableRules = $this->_getTableValidationRules($table);
            if (count($tableRules)) {
                $result[$table] = $tableRules;
            }
        }

        return $result;
    }

    /**
     * Returns a flat array with lowercased/underscored names for all tables
     * found in the application, minus configuration excluded tables.
     *
     * @throws \Cake\Network\Exception\InternalErrorException
     * @return array Tables to include in rule aggregation
     */
    protected function _getApplicationTables()
    {
        $tables = ConnectionManager::get('default')->schemaCollection()->listTables();
        if (!count($tables)) {
            throw new InternalErrorException("Could not find any tables in the application");
        }

        if (!count($this->_excludedTables)) {
            return $tables;
        }

        return array_diff($tables, $this->_excludedTables);
    }

    /**
     * Returns a hash with all validation rules for any given table.
     *
     * @param string $table Valid table name
     * @return array|void Hash if table contains validation rules, void otherwise
     */
    protected function _getTableValidationRules($table)
    {
        $tableObject = TableRegistry::get($table);
        $validator = $tableObject->validator();
        $validationSetIterator = $validator->getIterator();

        if (!$validationSetIterator->count()) {
            return;
        }

        $result = [];

        foreach ($validationSetIterator as $field => $validationSet) {
            $requiredFor = $validationSet->isPresenceRequired();
            if (!$requiredFor) {
                $requiredFor = null;
            }
            $result[$field]['requiredFor'] = $requiredFor;

            $allowedEmptyFor = $validationSet->isEmptyAllowed();
            if (!$allowedEmptyFor) {
                $allowedEmptyFor = null;
            }
            $result[$field]['allowedEmptyFor'] = $allowedEmptyFor;

            // continue foreach if field has no rules
            $validationRuleIterator = $validationSet->getIterator();
            if (!$validationRuleIterator->count()) {
                continue;
            }

            // add create rules array to store
            $result[$field]['rules'] = [];

            foreach ($validationRuleIterator as $ruleName => $validationRule) {
                $temp = [
                    'name' => $ruleName,
                    'rule' => $validationRule->get('rule'),
                    'message' => $validationRule->get('message')
                ];

                $pass = $validationRule->get('pass');
                if (!empty($pass)) {
                    $temp['pass'] = $validationRule->get('pass');
                }

                // continue with next rule if we don't need to hide parts
                if (!isset($this->_config['hiddenRuleParts'])) {
                    $result[$field]['rules'][] = $temp;
                    continue;
                }

                // remove validation parts marked as hidden in config
                if (count($this->_config['hiddenRuleParts'])) {
                    foreach ($this->_config['hiddenRuleParts'] as $arrayKey) {
                        if (array_key_exists($arrayKey, $temp)) {
                            unset($temp[$arrayKey]);
                        };
                    }
                }

                $result[$field]['rules'][] = $temp;
            }
        }

        return $result;
    }
}
