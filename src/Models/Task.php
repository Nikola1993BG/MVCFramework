<?php
namespace App\Models;
use App\Core\Model;
use App\Core\Db;
/**
 * Task
 */
/**
 * Class Task
 * 
 * Represents a task model with its properties and methods.
 */
class Task extends Model{

    /**
     * @var int|null The task ID.
     */
    public ?int $id;

    /**
     * @var string|null The task name.
     */
    public ?string $name;

    /**
     * @var string|null The task description.
     */
    public ?string $description;

    /**
     * @var string|null The task long description.
     */
    public ?string $long_description;

    /**
     * @var string The name of the database table associated with the model.
     */
    protected string $table_name = 'tasks';

    /**
     * Task constructor.
     */
    public function __construct(){}

}