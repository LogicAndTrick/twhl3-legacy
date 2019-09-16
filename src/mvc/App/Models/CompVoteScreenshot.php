<?php
 
class CompVoteScreenshot extends Model {

    public $table = 'compvoting_screenshots';
    public $columns = array(
        'ID' => 'ID',
        'EntryID' => 'EntryID',
        'ImageLocation' => 'ImageLocation'
    );

    public $primaryKey = 'ID';
    public $one = array(
        'CompVoteEntry' => array('EntryID' => 'ID')
    );

    public $many = array();

    public $validation = array(
        'ImageLocation' => array('required')
    );

}

?>