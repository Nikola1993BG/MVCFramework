<?php
namespace App\Core;
use \PDO;
class DBHelper{

    public string $query = '';
    public array $params;


    /**
     * Selects data from a table with specified fields.
     *
     * @param string $table The name of the table to select data from.
     * @param array $fields An array of fields to select. Defaults to all fields if not specified.
     *
     * @return object Returns the current instance of the DBHelper class.
     */
    public function select(string $table, array $fields = array('*')):object
    {
       
        $this->query = "SELECT ".(implode(', ', $fields))." FROM $table";
        return $this;
    
    }

    public function update($table, $fields=[]) {}

    /**
     * Adds a WHERE clause to the SQL query.
     *
     * @param string $key The column name to compare.
     * @param string $op The comparison operator. Default is '='.
     * @param mixed $val The value to compare against. Default is FALSE.
     * @return object Returns the DBHelper object for method chaining.
     */
    public function where($key, $op='=', $val=FALSE):object
    {

        if($val === FALSE && $op !== NULL) $this->params['where'][] = " $key = $op";
        else if($op === NULL){
            $this->params['where'][] = " $key IS NULL ";
        }
        else if($val === NULL) $this->params['where'][] = " $key $op NULL ";
        else $this->params['where'][] = " $key $op $val";
        //print_r($this->params);
        return $this;
    }
    
    /**
     * Adds an expression to the query.
     *
     * @param string $expr The expression to add.
     *
     * @return object Returns the current instance of the DBHelper class.
     */
    public function expr(string $expr):object
    {

        $this->params['expr'][0] = ' '.$expr;
        return $this;

    }

    public function add() {}

    public function delete() {}

    /**
     * Sets the GROUP BY clause for the SQL query.
     *
     * @param array $fields The fields to group by.
     * @return object Returns the current instance of the DBHelper class.
     */
    public function group(array $fields = []):object
    {
        $this->params['group_by'][0] = $fields;
        return $this;
    }
    /**
     * Set the HAVING clause of the query.
     *
     * @param string $expr The expression to use in the HAVING clause.
     * @return object Returns the current instance of the DBHelper class.
     */
    public function having(string $expr):object
    {
        $this->params['having'][0] = $expr;
        return $this;
    }
    /**
     * Add an ORDER BY clause to the query.
     *
     * @param string $field The name of the field to order by.
     * @param string $order The order direction. Default is 'ASC'.
     *
     * @return object Returns the DBHelper object for method chaining.
     */
    public function order(string $field, string $order='ASC'):object
    {
        $this->params['order_by'][] = "$field $order"; 
        return $this;
    }

    /**
     * Returns an array of results from the database based on the previously set query.
     *
     * @return array An array of results from the database.
     * @throws \Exception If the select() method has not been called before calling this method.
     */
    public function get():array
    {

        if($this->query){

            $this->addParams();
            return App::$app->db->query($this->query)->fetchAll(PDO::FETCH_ASSOC);

        }
        else{
            throw new \Exception('You must call the select() method first!');
        }
    }

    /**
     * Adds parameters to the query based on the values set in the $params property.
     *
     * @return object Returns the current instance of the DBHelper class.
     */
    public function addParams():object
    {

        if(isset($this->params['expr'])){
            foreach($this->params['expr'] as $expr){
                $this->query .= $expr;
            }
           
            return $this;
        }

        //using the order of interpretation of elements in the query

        if(isset($this->params['where'])){
            $whereConditions = $this->params['where'];
            $this->query .= " WHERE "; 
            $and = '';
            foreach($whereConditions as $whereCondition) {
                $this->query .= $and.$whereCondition;
                $and = ' AND ';
            }
        }
        if(isset($this->params['group_by'])){

            $this->query .= " GROUP BY ".(implode($this->params['group_by'][0]));

        }
        if(isset($this->params['having'])){

            $this->query .= " HAVING ".$this->params['having'][0];

        }
        if(isset($this->params['order_by'])){

            $this->query .= " ORDER BY ".implode(',', $this->params['order_by']);
        }

        return $this;
    }


}