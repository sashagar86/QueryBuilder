<?php

class QueryBuilder
{
    const EMPTY_CONDIION = "1 = 1";

    private $rowCounts = 0;

    private $pdo;

    public function __construct()
    {
        $this->pdo = Connection::make();
    }

    //CRUD
    //Create
    public function create($table, $data)
    {
        $values = [];
        foreach ($data as $key => $value) {
            $values[] = ':' . $key;
        }

        $keys = implode(', ', array_keys($data));
        $values = implode(', ', $values);

        $stmt = $this->pdo->prepare("INSERT INTO {$table}({$keys}) VALUES ({$values})");

        $stmt->execute($data);

        return $this->pdo->lastInsertId();
    }

    //Read
    public function getOne($table, $value, $column = 'id')
    {
        $stmt = $this->pdo->prepare("SELECT * FROM {$table} WHERE {$column}=:value");
        $stmt->execute(['value' => $value]);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    //Update
    public function update($table, $data, $id)
    {
        $values = [];

        foreach ($data as $key => $value) {
            $values[] = $key . ' = :' .$key;
        }

        $values = implode(',', $values);

        $data['id'] = $id;
        $stmt = $this->pdo->prepare("UPDATE {$table} SET {$values} WHERE id = :id");
        $stmt->execute($data);
    }

    //Delete
    public function delete($table, $id)
    {
        $stmt = $this->pdo->prepare("DELETE FROM {$table} WHERE id IN (:id)");
        $stmt->execute(['id' => $id]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    //getAll
    public function getAll($table, $where = self::EMPTY_CONDIION, $start = 0, $limit = null)
    {
        if (empty($where)) {
            $where = self::EMPTY_CONDIION;
        }

        $sql = "SELECT SQL_CALC_FOUND_ROWS * FROM {$table} WHERE :where";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['where' => $where]);

        $this->rowCounts = $stmt->rowCount();

        if ($limit && $limit != 0) {
            $sql .= " LIMIT {$start}, {$limit}";
        }

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['where' => $where]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    //Count rows
    public function countRows()
    {
        return $this->rowCounts;
    }
}