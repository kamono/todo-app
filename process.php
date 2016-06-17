<?php
namespace ToDo {
session_start();
require_once('tasks/tasks.class.php');
require_once('includes/functions.php');

    if (isset($_POST['submit'])) {
        // get created task information from form
        if ((!empty($_POST['taskName'])) 
                && (!empty($_POST['taskDescription']))
                && (!empty($_POST['taskDueDate']))) {
            $task_name = $_POST['taskName'];
            $task_description = $_POST['taskDescription'];
            $task_date = $_POST['taskDueDate'];
            $urgency = $_POST['urgency'];
        } else {
            // return error message to user
            $_SESSION['error'] = true;
            redirectTo('index.php');
        }

        // instanciate new class
        $task = new Task($task_name, $task_description, $task_date, $urgency);

        // add newly created task to database
        addTask($task);

        // redirects back to task page 
        // and displays new tasks
        redirectTo('index.php');
        
    } elseif (isset($_POST['checked'])) {
        // get the task id of the item selected
        $id = $_POST['task'];
        $output;
        // delete task
        if(removeTask($id)) {
            // display success message to user
            $output = json_encode(array('type'=>'success', 'text' => 'task deleted.'));
        } else {
            // display error message to user
            $output = json_encode(array('type'=>'error', 'text' => 'task could not be deleted.'));
        }
       
        die($output);
    }
    
    // any other request, redirect back to main page
    redirectTo('index.php');
}
?>