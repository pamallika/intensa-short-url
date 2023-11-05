<?php

namespace Projects\Intensa\Models;

use Exception;
use PDO;
use PDOException;
use Projects\Intensa\Database\Db;

class Model
{
    protected PDO $db;
    protected string $method = 'SELECT';
    protected string $sql;
    protected array $values;

    /**
     * @throws Exception
     */
    public function __construct()
    {
        $this->db = (new Db())->connect();
    }

    public static function getTableName(): string
    {
        $className = substr(static::class, strrpos(static::class, '\\') + 1);

        return strtolower($className);
    }

    public function all(): static
    {
        $this->sql = 'SELECT * FROM ' . static::getTableName();

        return $this;
    }

    /**
     * @throws Exception
     */
    public function exec(): false|array|string
    {
        $stmt = $this->db->prepare($this->sql);
        foreach ($this->values as $param => $value) {
            switch ($param) {
                case ':limit':
                case ':offset':
                    $stmt->bindValue($param, $value, PDO::PARAM_INT);
                    break;
                default:
                    $stmt->bindValue($param, $value);
                    break;
            }
        }
        try {
            $stmt->execute();
        } catch (PDOException $e) {
            throw new Exception($e);
        }

        if ($this->method === 'SELECT') {
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }

        return $this->findBy(['id' => $this->db->lastInsertId()])->exec();
    }

    public function insert(array $params): Model
    {
        $fillable = $this->fillable ?? null;

        if (!$fillable) {
            return false;
        }
        $fields = implode(', ', $fillable);
        $fillable[0] = ':' . $fillable[0];
        $values = implode(', :', $fillable);
        $this->sql = 'INSERT INTO ' . static::getTableName() . ' (' . $fields . ')' . ' VALUES ' . '(' . $values . ')';
        $this->values = $params;
        $this->method = 'INSERT';

        return $this;
    }

    /**
     * @throws Exception
     */
    public function findBy(array $param, array $selectedFields = ['*']): static
    {
        $this->method = 'SELECT';
        if (count($param) === 1) {
            $selectedFieldsQuery = implode(', ', $selectedFields);
            foreach ($param as $field => $value) {
                $this->sql = 'SELECT ' . $selectedFieldsQuery . ' FROM ' . static::getTableName() . ' WHERE ' . $field . ' = :' . $field;
                $this->values = $param;
            }

            return $this;
        }

        throw new Exception('param имеет вид field => value');
    }

}