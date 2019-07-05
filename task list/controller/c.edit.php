<?php

	class edit {
		protected $id, $userName, $email, $taskContent, $status;

		function viewEditList($currentPage, $COUNT) {
			$this->id = $this->userName = $this->email = $this->taskContent = $this->status = array();

			$shift = ($currentPage - 1)*$COUNT;

			$array = mysql_fetch_array(mysql_query("SELECT count(*) FROM tasklist"));
			$countRecord=$array[0]; //количество записей

			$q = mysql_query("SELECT * FROM tasklist LIMIT $shift, $COUNT");

			while ($row = mysql_fetch_assoc($q)){
				$this->id[] = $row['id'];
				$this->userName[] = $row['userName'];
				$this->email[] = $row['email'];
				$this->taskContent[] = $row['taskContent'];
				$this->status[] = $row['status'];
			};

			ob_start();
	 		include "/view/v.edit.task.list.php";
	 		return ob_get_clean();
		}


		function saveChanges($_id, $_status, $_content) {
			$id = '\''.$_id.'\'';
			$status = (isset($_status)) ? 1 : 0;
			$content = '\''.$_content.'\'';

			$q = mysql_query("UPDATE tasklist SET status=$status, taskContent=$content WHERE id=$id");
			if ($q)
				header('Location: index.php');
		}

	};

?>