<?php
 
class User extends Model {

    public $table = 'users';
    public $columns = array(
        'userID' => 'ID',
        'uid' => 'Username',
        'lvl' => 'Level'
    );

    public $primaryKey = 'ID';
    public $one = array();

    public $many = array(
        'CompVoteEntry' => array('ID' => 'UserID'),
        'CompVoteVote' => array('ID' => 'UserID')
    );
    
    public $validation = array();

}

?>