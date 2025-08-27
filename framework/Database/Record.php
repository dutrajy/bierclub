<?php

namespace Commercial\Framework\Database;

use Commercial\Framework\Lang\Strings;

class Record extends \Commercial\Framework\Core\Base
{
    /** @Read */
    protected $id;

    /** @Read */
    protected $createdAt;

    /** @Read */
    protected $updatedAt;

    public static function findAll($criteria = [])
    {
        $connection = Connection::open();

        $sets = "";

        foreach ($criteria as $key => $value) {
            $key = Strings::snake($key);
            $sets .= "AND `{$key}` = :{$key} ";
        }

        $table = static::getTable();
        $statement = $connection->prepare("SELECT * FROM `{$table}` WHERE 1=1 {$sets}");

        foreach ($criteria as $key => $value) {
            $key = Strings::snake($key);
            $statement->bindValue(":{$key}", $value);
        }

        $statement->execute();

        $rows = $statement->fetchAll(\PDO::FETCH_ASSOC);

        foreach ($rows as $row) {
            $results[] = new static($row);
        }

        return $results ?? [];
    }

    public static function findOne($criteria)
    {
        $connection = Connection::open();

        $sets = "";

        foreach ($criteria as $key => $value) {
            $key = Strings::snake($key);
            $sets .= "AND `{$key}` = :{$key} ";
        }

        $table = static::getTable();
        $statement = $connection->prepare("SELECT * FROM `{$table}` WHERE 1=1 {$sets}");

        foreach ($criteria as $key => $value) {
            $key = Strings::snake($key);
            $statement->bindValue(":{$key}", $value);
        }

        $statement->execute();

        $result = $statement->fetch(\PDO::FETCH_ASSOC);

        return $result ? new static($result) : null;
    }

    public function insert()
    {
        $data = $this->getWriteableData();
        $keys = array_keys($data);

        $connection = Connection::open();

        $fields = "`" . implode("`, `", $keys) . "`";
        $binders = ":" . implode(", :", $keys);
        $table = static::getTable();

        $query = "INSERT INTO `{$table}`({$fields}) VALUES ({$binders})";
        $statement = $connection->prepare($query);

        foreach ($keys as $key) {
            $statement->bindValue(":{$key}", $data[$key]);
        }

        $statement->execute();
        $this->id = $connection->lastInsertId();
    }

    public function update()
    {
        $data = $this->getWriteableData();
        $keys = array_keys($data);

        $connection = Connection::open();

        $sets = [];
        foreach ($data as $key => $value) {
            $sets[] = "`{$key}` = :{$key}";
        }
        $sets = implode(", ", $sets);

        $table = static::getTable();

        $query = "UPDATE `{$table}` SET {$sets} WHERE `id` = :id";
        $statement = $connection->prepare($query);

        foreach ($keys as $key) {
            $statement->bindValue(":{$key}", $data[$key]);
        }

        $statement->bindValue(":id", $this->getId());

        $statement->execute();
    }

    public function delete()
    {
        $connection = Connection::open();

        $table = static::getTable();

        $query = "DELETE FROM `{$table}` WHERE `id` = :id";
        $statement = $connection->prepare($query);
        $statement->bindValue(":id", $this->getId());

        $statement->execute();
    }

    public static function getTable()
    {
        $base = basename(str_replace("\\", "/", static::class));
        $plural = Strings::plural($base);
        return Strings::snake($plural);
    }
}
