<?php
 
class CompVoteVote extends Model {

    public $table = 'compvoting_votes';
    public $columns = array(
        'ID' => 'ID',
        'UserID' => 'UserID',
        'CompID' => 'CompID',
        'EntryID' => 'EntryID'
    );

    public $primaryKey = 'ID';
    public $one = array(
        'User' => array('UserID' => 'ID'),
        'CompVoteEntry' => array('EntryID' => 'ID'),
        'Comp' => array('CompID' => 'ID'),
    );

    public $many = array();
    
    public $validation = array();

}

?>