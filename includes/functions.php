<?php
// connect to the database
function dbInit() {
	// instanciates database
	$db = new SQLite3('sqlite/todo.db');
	return $db;
}

// redirects user to specified page
function redirectTo($page) {
    header("Location: {$page}");
    exit();
}

// add a task to the database 
function addTask(ToDo\Task $task) {
	$db = dbInit();
    
    // sanitize data before query
	$name = SQLite3::escapeString($task->getName());
    $description = SQLite3::escapeString($task->getDescription());
    $date = SQLite3::escapeString($task->getDate());
    $urgency = SQLite3::escapeString($task->getUrgency());
    $visible = 1;
    $user_id = 1;
    
	// add a new task to the database
	$sql  = 'INSERT INTO tasks ';
	$sql .= "(name, description, date, visible, user_id, urgency) ";
	$sql .= 'VALUES ';
    $sql .= "('{$name}','{$description}','{$date}',{$visible},{$user_id},{$urgency});";
	$db->exec($sql);
}

// delete a task from the database
function removeTask($id) {
	$db = dbInit();
	$result = false;
    // sanitize data before query
	$id = SQLite3::escapeString($id);
	// soft delete tasks
	$sql  = 'UPDATE tasks ';
	$sql .= 'SET visible = 0 ';
	$sql .= "WHERE id = {$id};";
	$query = $db->exec($sql);
    
    // checks if rows were updated
    if($query) {
        $result = true;
    }
    return $result;
}

// get all tasks which belong to a specific user
function getTasks() {
	$db = dbInit();
	$output = "";
    $due_date;
    $date_now = new DateTime("now");
    $date_now = date_format($date_now, 'Y-m-d');
	// get all visible tasks for a user
	$sql  = 'SELECT * FROM tasks ';
	$sql .= 'WHERE user_id = 1 ';
	$sql .= 'AND visible = 1 ';
    $sql .= 'ORDER BY date(date) ASC;';
	$result = $db->query($sql);
    
	// build the table record containing the task information
	// for each visible task in the database
	while ($row = $result->fetchArray()) {
        $class;
        switch ($row['urgency']) {
            case 0: 
            	$class = 'class="info"';    
            	break;
            case 1: 
            	$class = 'class="warning"'; 
            	break;
            case 2: 
            	$class = 'class="danger"';  
            	break;
        }
        // if due date past, style date in red bold
        if($row['date'] < $date_now){
            $due_date = '<span class="red"><strong>'.$row['date'].'</strong></span>';
        } else {
            $due_date = $row['date'];
        }
		$output .= "<tr {$class}>"
		.	'<td>'
		.		'<input type="checkbox" name="checkbox" id="'.$row['id'].'">'
		.	'</td>'
		.	"<td data-toggle=\"collapse\" data-target=\"#task{$row['id']}\">"
			.		'<span class="panel-title">'
        .			'<a data-toggle="collapse"'
		.			'data-target="#task'.$row['id']
		.			'" class="accordion-toggle"'
		.			'href="#">'.$row['name'].'</a>'
		.		'</span>'
		.	'</td>'
        .	'<td>'.$due_date.'</td>'
		.	'</tr>'
		.'<tr>'
		.	'<td colspan="3" class="hidden-row">'
		.		'<div class="accordian-body collapse" id="task'.$row['id'].'">'
		. 			$row['description']
		.		'</div>'		
		.	'</td>'
		.'</tr>';
	}
	return $output;
}
?>