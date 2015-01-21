<?php
class Db extends PDO
{
    public function __construct($dsn, $username, $password, array $options = array())
    {
        try {
            parent::__construct($dsn, $username, $password, $options);
        } catch (PDOException $e) {
            exit($e->getMessage());
        }
    }

    public function fetchAll($query, array $parameters = array(), $type = PDO::FETCH_ASSOC)
    {
        $rs = $this->query($query, $parameters);
        $results = $rs->fetchAll($type);
        $rs->closeCursor();
        $rs = null;
        
        return $results;
    }

    public function fetch($query, array $parameters = array(), $type = PDO::FETCH_ASSOC)
    {
        $rs = $this->query($query, $parameters);
        $results = $rs->fetch($type);
        $rs->closeCursor();
        $rs = null;
        
        return $results;
    }

    public function fetchColumn($query, array $parameters = array(), $column = 0)
    {
        $rs = $this->query($query, $parameters);
        $results = $rs->fetchColumn($column);
        $rs->closeCursor();
        $rs = null;
        
        return $results;
    }

    public function insert($table, array $data, $type = 'INSERT')
    {
        return $this->query($type . ' INTO `' . $table . '` (`' . implode('`,`', array_keys($data)) . '`) VALUES (' . implode(',', array_fill(0, count($data), '?')) . ');', array_values($data));
    }

    public function update($table, array $data, $where = '1')
    {
        return $this->query('UPDATE `' . $table . '` SET `' . implode('`=?,`', array_keys($data)) . '`=? WHERE ' . $where . ';', array_values($data));
    }

    public function delete($table, $where = '1')
    {
        return $this->query('DELETE FROM `' . $table . '` WHERE ' . $where . ';');
    }

    public function query($query, array $parameters = array())
    {
        if ($rs = $this->prepare($query)) {
            $rs->execute($parameters);
            return $rs;
        } else {
            exit($query);
        }
    }
}
