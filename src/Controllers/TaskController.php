<?php

namespace App\Controllers;

use App\Core\View;
use App\Models\Task;
use App\Core\Request;
/**
 * TaskController
 */
class TaskController {
	
	
	/**
	 * Retrieves a single task and renders it using the show.html Twig template.
	 *
	 * @param array $data An array containing the task ID.
	 * @return void
	 */
	public function get(array $data):void
	{
		
		$task = new Task;
		$task = $task->getOne($data['id']);

		View::renderTwig('tasks/show.html', ['task'=>$task]);
	}
	
	/**
	 * Retrieves all tasks and renders them using the tasks.html template.
	 *
	 * @return void
	 */
	public function getAll():void
	{
		$task = new Task;
		$tasks = $task->getAll();

		View::renderTwig('tasks.html', ['tasks'=>$tasks]);
	}
	
	/**
	 * Adds a new task to the database.
	 *
	 * @return void
	 */
	public function add():void
	{

		if(Request::getBody() && Request::getMethod() == 'post'){

			$taskInfo = Request::getBody();

			$task = new Task();
			$task->name = $taskInfo['name'];
			$task->description = $taskInfo['description'];
			$task->long_description = $taskInfo['long_description'];
			$task->add();

			header('Location: /tasks');

		}

		View::renderTwig('tasks/add.html', []); 	
	}
	
	
	/**
	 * Update a task with the given ID using the data from the request body.
	 * If the request method is not POST or the request body is empty, nothing happens.
	 * If the task ID is not provided in the $params array, an exception is thrown.
	 * If the task is successfully updated, the user is redirected to the updated task's page.
	 * If the task ID is provided in the $params array, the task with that ID is retrieved from the database
	 * and its data is passed to the 'tasks/add.html' Twig template for rendering.
	 *
	 * @param array $params An array containing the task ID.
	 * @throws \Exception If the task ID is not found or if there is an error updating the task.
	 */
	public function update(array $params)
	{
		if(Request::getBody() && Request::getMethod() == 'post'){

			$taskInfo = Request::getBody();
			$task = new Task();

			if(isset($params['id'])){

				$task->id = $params['id'];
				$task->name = $taskInfo['name'];
				$task->description = $taskInfo['description'];
				$task->long_description = $taskInfo['long_description'];
				$task->update();

				header('Location: /tasks/'.$task->id);
				
			}
			else throw new \Exception('Task ID not found!');

		}

		if(isset($params['id'])){

			$taskId = $params['id'];
			$task = new Task();
			$params = $task->getOne($taskId);
			$params = $params[0];
			View::renderTwig('tasks/add.html', $params);

		}
		else throw new \Exception('Task ID not found!');
	}

	/**
	 * Deletes a task by ID.
	 *
	 * @param array $params An array containing the task ID.
	 *
	 * @throws \Exception If the task ID is not found.
	 *
	 * @return void
	 */
	public function delete(array $params)
	{

		if(isset($params['id'])){

			$taskId = $params['id'];
			$task = new Task();
			$task->id = $taskId;
			$task->delete();

			header('Location: /tasks');

		}
		else throw new \Exception('Task ID not found!');
	}
}