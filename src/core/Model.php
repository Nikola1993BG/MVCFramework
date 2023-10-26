<?php
namespace App\Core;
use App\Core\DBHelper;
/**
 * Model
 */
class Model{
    public function __construct(){}
    
    public function validate() {}

    /**
     * Deletes the current model instance from the database.
     *
     * @throws \Exception If the model does not have a $table_name property or if the ID is not set.
     *
     * @return bool True if the deletion was successful, false otherwise.
     */
    public function delete() 
    {
        if(property_exists(get_called_class(), 'table_name')) {

            if(!isset($this->id)) throw new \Exception('You must set the ID first!');

            return  Db::getDB()->prepare("DELETE FROM $this->table_name WHERE id=?")->execute([$this->id]);

        }
        else{
            throw new \Exception('Models must contain $table_name property!');
        }

    }

    /**
     * Adds a new record to the database table for the current model instance.
     *
     * @return bool True if the record was successfully added, false otherwise.
     * @throws \Exception If the model does not have a $table_name property.
     */
    public function add() 
    {
        if(property_exists(get_called_class(), 'table_name')) {
            foreach(get_class_vars(get_called_class()) as $field=>$val) {
                if($field == 'id' || $field == 'table_name') continue;
                $data[$field] = $this->$field;
                $fields[] = ':'.$field;
            }

            $values = implode(', ', $fields); 
            $fields = str_replace(':', '', $values);
            return Db::getDB()->prepare("INSERT INTO $this->table_name ($fields) VALUES ($values)")->execute($data);
        }
        else{
            throw new \Exception('Models must contain $table_name property!');
        }
    }
    /**
     * Update the current model instance in the database.
     *
     * @throws \Exception If the model does not have a $table_name property or if the ID is not set.
     *
     * @return bool True if the update was successful, false otherwise.
     */
    public function update() 
    {
        if(property_exists(get_called_class(), 'table_name')) {

            if(!isset($this->id)) throw new \Exception('You must set the ID first!');

            $data = []; $fields = [];
            foreach(get_class_vars(get_called_class()) as $field=>$val) {
                if($field == 'id' || $field == 'table_name') continue;
                $data[$field] = $this->$field;
                $fields[] = $field.'=:'.$field;
            }
            $data['id'] = $this->id;
            $set = (implode(',', $fields));

            return Db::getDB()->prepare("UPDATE $this->table_name SET $set WHERE id=:id")->execute($data);
        }
        else{
            throw new \Exception('Models must contain $table_name property!');
        }
    }
   
    /**
     * Retrieves a single record from the database table based on the provided ID and fields.
     *
     * @param int $id The ID of the record to retrieve.
     * @param array $fields The fields to retrieve from the record. Defaults to all fields.
     * @return array The retrieved record as an associative array.
     * @throws \Exception If the model does not contain a $table_name property.
     */
    public function getOne(int $id, array $fields=['*']):array
    {
        if(property_exists(get_called_class(), 'table_name')) {
            $db = new DBHelper;
            return $db->select($this->table_name, $fields)->where('id', $id)->get();
        }
        else{
            throw new \Exception('Models must contain $table_name property!');
        }
    }    
   
    /**
     * Get all records from the database table associated with the model.
     *
     * @param array $fields The fields to retrieve from the table. Default is all fields.
     *
     * @return array An array of records retrieved from the database table.
     *
     * @throws \Exception If the model does not have a $table_name property.
     */
    public function getAll($fields=['*']):array
    {   
        if(property_exists(get_called_class(), 'table_name')) {
            $db = new DBHelper;
            return $db->select($this->table_name, $fields)->get();
        }
        else{
            throw new \Exception('Models must contain $table_name property!');
        }
    }

}