<?php

namespace ToDo;

class Task {
	private $name;
	private $description;
	private $date;
    private $urgency;
	//public static $counter;

	public function __construct($name, $description, $date, $urgency) {
		$this->name = $name;
		$this->description = $description;
		$this->date = $date;
        $this->urgency = $urgency;
	}

	public function getName() {
		return $this->name;
	}

	public function getDescription() {
		return $this->description;
	}

	public function getDate() {
		return $this->date;
	}
    
    public function getUrgency() {
		return $this->urgency;
	}
}

?>