<?php 
class TaskGateway {
    private PDO $conn;

    public function __construct(Database $database)
    {
        $this->conn = $database->getConnection();
    }

    public function getById(int $id):array | false{

        $sql="SELECT * FROM task 
                where id = :id";

        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(":id", $id, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getAll():array {

        $sql="SELECT * FROM task";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public function getByState(string $state):array {

        $sql="SELECT * FROM task WHERE state = :state";

        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(":state", $state, PDO::PARAM_STR);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function updateTask(int $id, array $data): int
    {
        $fields = [];

        $fields_map = [
            'name'           => PDO::PARAM_STR,
            'state'     => PDO::PARAM_STR,
        ];

        foreach ($fields_map as $key => $type) {
            if (array_key_exists($key, $data)) {
                $fields[$key] = [
                    $data[$key],
                    $type
                ];
            }
        }

        if (empty($fields)) {

            return 0;
        } else {

            $sets = array_map(function ($value) {
                return "$value = :$value";
            }, array_keys($fields));

            $sql = "UPDATE task "
                . " SET " . implode(", ", $sets)
                . " WHERE id = :id";

            $stmt = $this->conn->prepare($sql);
            $stmt->bindValue(":id", $id, PDO::PARAM_INT);

            foreach ($fields as $name => $value) {
                $stmt->bindValue(":$name", $value[0], $value[1]);
            }

            $stmt->execute();

            return $stmt->rowCount();
        }
    }

    public function addTask(array $data):int{

        $sql = "INSERT INTO `task` (`name`, `state`) VALUES ( :name, :state);";
            
        $stmt = $this->conn->prepare($sql);

        $stmt->bindValue(":name", $data['name'], PDO::PARAM_STR);
        $stmt->bindValue(":state", $data['state'], PDO::PARAM_STR);

        $stmt->execute();

        return $this->conn->lastInsertId();
        
    }

    public function deleteTask(int $id):int{

        $sql="DELETE FROM task 
                where id = :id";

        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(":id", $id, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->rowCount();
    }

}
?>