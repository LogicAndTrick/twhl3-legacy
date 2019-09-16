<?php
 
class Comp extends Model {

    public $table = 'compos';
    public $columns = array(
        'compID' => 'ID',
        'compName' => 'Name',
        'votestatus' => 'VoteStatus'
    );

    public $primaryKey = 'ID';
    public $one = array();

    public $many = array(
        'CompVoteEntry' => array('ID' => 'CompID')
    );
    
    public $validation = array();

}

?>