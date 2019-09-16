<?php
 
class CompVoteEntry extends Model {

    public $table = 'compvoting_entries';
    public $columns = array(
        'ID' => 'ID',
        'CompID' => 'CompID',
        'UserID' => 'UserID',
        'Name' => 'Name'
    );

    public $primaryKey = 'ID';
    public $one = array(
        'Comp' => array('CompID' => 'ID'),
        'User' => array('UserID' => 'ID')
    );

    public $many = array(
        'CompVoteScreenshot' => array('ID' => 'EntryID'),
        'CompVoteVote' => array('ID' => 'EntryID'),
    );

    public $validation = array(
        'CompID' => array('required'),
        'UserID' => array('required')
    );

}

?>