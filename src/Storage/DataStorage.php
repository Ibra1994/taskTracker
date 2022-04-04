<?php

namespace App\Storage;

use App\Model;

class DataStorage
{
    /**
     * @var \PDO 
     */
    public $pdo;

    public function __construct()
    {
        //use password & charset, also set values from env variables, it's more safe and easier to setup
        $this->pdo = new \PDO('mysql:dbname=task_tracker;host=127.0.0.1', 'user');
    }

    /**
     * @param int $projectId
     * @throws Model\NotFoundException
     */
    public function getProjectById($projectId)
    {
        //use pdo prepare if variables to be used, it prevents sql injection
        $stmt = $this->pdo->query('SELECT * FROM project WHERE id = ' . (int) $projectId);

        //PDO::FETCH_CLASS can be used
        if ($row = $stmt->fetch(\PDO::FETCH_ASSOC)) {
            return new Model\Project($row);
        }

        throw new Model\NotFoundException();
    }

    /**
     * @param int $project_id
     * @param int $limit
     * @param int $offset
     */
    public function getTasksByProjectId(int $project_id, $limit, $offset)
    {
        //it's impossible to use positional placeholders with query method.
        //use pdo prepare if variables to be used, it prevents sql injection
        //for limit & offset use bindValue method with PARAM_INT option as LIMIT clause in emulation mode or any other SQL clause that just cannot accept a string operand.
        $stmt = $this->pdo->query("SELECT * FROM task WHERE project_id = $project_id LIMIT ?, ?");
        $stmt->execute([$limit, $offset]);

        $tasks = [];
        //PDO::FETCH_CLASS mode can be used
        foreach ($stmt->fetchAll() as $row) {
            $tasks[] = new Model\Task($row);
        }

        return $tasks;
    }

    /**
     * @param array $data
     * @param int $projectId
     * @return Model\Task
     */
    public function createTask(array $data, $projectId)
    {
        $data['project_id'] = $projectId;

        //use mysql_real_escape_string to prevent sql injection
        $fields = implode(',', array_keys($data));
        $values = implode(',', array_map(function ($v) {
            return is_string($v) ? '"' . $v . '"' : $v;
        }, $data));

        //use prepare & execute methods
        //use $this->pdo->lastInsertId(), It will be the last inserted ID of the connection underlying the PDO instance
        $this->pdo->query("INSERT INTO task ($fields) VALUES ($values)");
        $data['id'] = $this->pdo->query('SELECT MAX(id) FROM task')->fetchColumn();

        return new Model\Task($data);
    }
}
