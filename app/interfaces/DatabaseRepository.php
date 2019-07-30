<?php
namespace App\interfaces;

/**
 * The base interface for database operations and methods.
 *
 * @author Ali Tavafi <ali.tavafii@gmail.com>
 */
interface DatabaseRepository
{
    /**
     * Create new record with the given data.
     * 
     * @param array $data Given data to create and fill the record.
     * 
     * @return bool if record is created, or false if record is not created.
     * 
     * @throws Exception if any PDO exception catches, or query is null.
     */
    public function create(array $data): bool;

    /**
     * Select given attributes if is not null or all attributes of a table.
     * 
     * @param array $attrs Attributes to select from the table.
     * 
     * @return self $this The class object.
     */
    public function select(array $attrs = []): self;

    /**
     * Update record attributes.
     * 
     * @param array $attrs Attributes to be updated.
     * 
     * @return bool if update record successfully.
     * 
     * @throws Exception if query does not execute or record does not update.
     */
    public function update(array $attrs): bool;

    /**
     * Delete all records or records with specific condition.
     * 
     * @return bool if delete record succesfully.
     * 
     * @throws Exception if query does not execute or record does not delete.
     */
    public function delete(): bool;

    /**
     * Add or append where conditions to the query.
     * 
     * @param string $column The column name to update its value.
     * @param string $condition The condition to append to the query.
     * @param mixed $value The value to be checked in query condition for the column.
     * 
     * @return self $this The class object.
     */
    public function where(string $column, string $condition = '=', $value): self;

    /**
     * Sort query result based on order type.
     * 
     * @param string $column The column to be sorted by.
     * @param string $orderType Order by type:
     * - ASC means ascending.
     * - DESC means descending.
     * 
     * @return self $this The class object.
     */
    public function orderBy(string $column, string $orderType): self;

    /**
     * Fetch the first founded record.
     * 
     * @return array|null|bool founded record.
     * 
     * @throws Exception if the query can not execute.
     */
    public function one();

    /**
     * Fetch all founded records.
     * 
     * @return array|null|bool founded records.
     * 
     * @throws Exception if the query can not execute.
     */
    public function all();

    /**
     * Execute the query.
     * 
     * @param mixed $args arguments to be binded in query.
     * 
     * @return mixed query result.
     * 
     * @throws Exception if query doesn't execute, or query is null.
     */
    public function execute($args);
}