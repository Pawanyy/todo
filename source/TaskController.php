<?php
class TaskController
{

    public function __construct(private TaskGateway $gateway)
    {

    }

    public function getTasks(string $state): void
    {

        // $data = (array)json_decode(file_get_contents("php://input"), true);

        $errors = $this->getValidationErrorsState($state);

        if (!empty($errors)) {
            $this->responseUnprocessableEntity($errors);
            return;
        }

        $task_data = $this->gateway->getByState($state);

        echo json_encode([
            'tasks' => $task_data,
        ]);

    }

    public function getAllTasks()
    {
        $task_data = $this->gateway->getAll();

        echo json_encode([
            'tasks' => $task_data,
        ]);
    }

    public function createTask()
    {

        $data = (array) json_decode(file_get_contents("php://input"), true);

        $errors = $this->getValidationErrorsCreate($data);

        if (!empty($errors)) {
            $this->responseUnprocessableEntity($errors);
            return;
        }

        $task_id = $this->gateway->addTask($data);

        echo json_encode([
            'message' => 'Task Created!',
            'Task_Id' => $task_id,
        ]);
    }

    public function updateTask($id)
    {

        $data = (array) json_decode(file_get_contents("php://input"), true);

        $rowCount = $this->gateway->updateTask($id, $data);

        echo json_encode([
            'message' => 'Task Updated!',
            'Affected' => $rowCount,
        ]);
    }

    public function deleteTask($id)
    {

        $errors = [];

        $task = $this->gateway->getById($id);

        if($task === false){

            $errors[] = "Task Does Not exist!";

        }

        if (!empty($errors)) {
            $this->responseUnprocessableEntity($errors);
            return;
        }

        $rowCount = $this->gateway->deleteTask($id);

        echo json_encode([
            'message' => 'Task Deleted!',
            'Affected' => $rowCount,
        ]);
    }

    private function responseUnprocessableEntity(array $errors): void
    {
        http_response_code(422);
        echo json_encode(["errors" => $errors]);
    }

    private function getValidationErrors(array $data): array
    {

        $errors = [];

        if (!array_key_exists('state', $data)) {
            $errors[] = "state is Required!";
        } else {
            if( ! in_array($data['state'], ['pending', 'completed'])){
                $errors[] = "Invalid State! Only ['pending', 'completed'] allowed!";
            }
        }

        return $errors;
    }
    private function getValidationErrorsState(string $state): array
    {

        $errors = [];

        
        if( ! in_array($state, ['pending', 'completed'])){
            $errors[] = "Invalid State! Only ['pending', 'completed'] allowed!";
        }

        return $errors;
    }

    private function getValidationErrorsCreate(array $data): array
    {

        $errors = [];

        if( ! array_key_exists('name', $data)){
            $errors[] = "Task Name Is Required!";
        }
        
        if( ! array_key_exists('state', $data)){
            $errors[] = "State is Required!";
        } else {

            if( ! in_array($data['state'], ['pending', 'completed'])){
                $errors[] = "Invalid State! Only ['pending', 'completed'] allowed!";
            }

        }


        return $errors;
    }
}
?>