<?php
namespace App\repositories;

use Exception;
use App\core\DB;
use App\interfaces\DatabaseRepository as MainDatabaseRepository;

/**
 * The base class to handle database operations.
 *
 * @author Ali Tavafi <ali.tavafii@gmail.com>
 */
class DatabaseRepository implements MainDatabaseRepository
{
    /**
     * @var \App\core\DB Database connection.
     */
    protected $db;

    /**
     * @var string Table name.
     */
    protected $table;

    /**
     * @var string Query to be executed.
     */
    private $query;

    /**
     * @var array|null Prepared columns in queries.
     */
    private $columns;

    /**
     * Set the database connection in db property of the class.
     */
    public function __construct()
    {
        $this->db = self::getDB();
    }

    /**
     * Get the database connection.
     * 
     * @return \App\core\DB The database connection instance of \App\core\DB class.
     */
    protected static function getDB()
    {
        return DB::getInstance()->getConnection();
    }

    /**
     * Prepare data for different situations.
     * 
     * @param array|null $data The data to be prepared for the query.
     * @param string $type Define the data preparation functionality. It is modified while inserting new record.
     * 
     * @return array The prepared data.
     */
    protected function prepareData(?array $data, string $type = 'general'): array
    {
        if ($data) {
            return [
                "data" => $data,
                "query" => $type != 'general' ? "(" . implode(', ', array_keys($data)) . ")" : implode(', ', array_keys($data)),
                "binding" => $type != 'general' ? "(:" . implode(', :', array_keys($data)) . ")" : ':' . implode(', :', array_keys($data))
            ];
        } else {
            return [];
        }
    }
    
    /**
     * {@inheritdoc}
     */
    public function create(array $data): bool
    {
        if (!$data) {
            return false;
        }
        
        $this->columns = $this->prepareData($data, 'insert');
        $this->query = $this->db->prepare("INSERT INTO {$this->table} {$this->columns['query']} VALUES {$this->columns['binding']}");

        return $this->execute($this->columns['data']);
    }

    /**
     * {@inheritdoc}
     */
    public function select(array $attrs = []): MainDatabaseRepository
    {
        if ($attrs) {
            $selectColumns = [];
            foreach ($attrs as $key => $attr) {
                $selectColumns[$attr] =  $attr;
            }
        } else {
            $selectColumns = ['*' => '*'];
        }
        $selectColumns = $this->prepareData($selectColumns);
        $this->columns = $this->prepareData($this->columns);
        $this->query = $this->db->prepare("SELECT {$selectColumns['query']} FROM {$this->table} {$this->query}");

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function one()
    {
        $data = $this->columns['data'] ?? null;

        if ($this->execute($data)) {
            return $this->query->fetch();
        } else {
            throw new Exception("Can not select!");
        }
    }

    /**
     * {@inheritdoc}
     */
    public function all()
    {
        $data = $this->columns['data'] ?? null;
        
        if ($this->execute($data)) {
            return $this->query->fetchAll();
        } else {
            throw new Exception("Can not select!");
        }

    }

    /**
     * {@inheritdoc}
     */
    public function update(array $attrs): bool
    {
        if ($attrs) {
            $updateQuery = null;
            foreach ($attrs as $key => $attr) {
                $updateQuery .= "{$key} = :{$key},";
            }
            $updateQuery = rtrim($updateQuery, ',');
            
            $attrs += $this->columns;

            $this->query = $this->db->prepare("UPDATE {$this->table} SET {$updateQuery} {$this->query}");

            if (!$this->execute($attrs)) {
                throw new Exception("Can not update!");
            }

            return true;
        }
    }

    /**
     * {@inheritdoc}
     */
    public function delete(): bool
    {
        $this->query = $this->db->prepare("DELETE FROM {$this->table} {$this->query}");

        if (!$this->execute($this->columns)) {
            throw new Exception("Can not delete!");
        }

        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function where(string $column, string $condition = '=', $value): MainDatabaseRepository
    {
        $this->columns[$column] = $value;

        if (empty($this->query)) {
            $this->query .= " WHERE {$column} {$condition} :{$column}";
        } else {
            $this->query .= " AND {$column} {$condition} :{$column}";
        }

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function orderBy(string $column, string $orderType = 'ASC'): MainDatabaseRepository
    {
        $this->query .= " ORDER BY {$column} {$orderType}";

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function execute($args)
    {
        try {
            if ($this->query) {
                if ($args) {
                    foreach ($args as $key => $value) {
                        $this->query->bindParam($key, $value);
                    }
                }
                return $this->query->execute($args);
            } else {
                throw new Exception("Query can not be null!");
            }
        } catch (\Throwable $th) {
            throw $th;
        }
    }
}