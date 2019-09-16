<?php
 
class CompVotingController extends Controller {

    public $authorise = array(
        'DeleteEntry' => 40,
        'CreateEntry' => 40,
        'VoteEntry' => 0,
        'ViewResults' => 40
    );

    function ViewResults($compid)
    {
        $this->viewData['comp'] = new Comp($compid);
        $query = CustomQuery::Query(
            'SELECT count(*) AS NumVotes, E.Name, U.uid AS Username, U.UserID
            FROM compvoting_votes V
            LEFT JOIN compvoting_entries E ON E.ID = V.EntryID
            LEFT JOIN users U ON U.userID = E.UserID
            WHERE E.CompID = :id
            GROUP BY E.Name, U.uid, U.UserID
            ORDER BY count(*) DESC',
            array('id' => $compid)
        );
        return $this->View($query);
    }

    function ViewEntries($compid)
    {
        $this->viewData['comp'] = new Comp($compid);
        $this->viewData['imgdir'] = '/compopics/compo_'.str_pad($compid, 3, '0', STR_PAD_LEFT).'/';
        $model = Model::Search('CompVoteEntry', array('CompID = :id'), array('id' => $compid), 'RAND()');
        $cvote = -1;
        if (isset($_SESSION['usr'])) {
            $vq = Query::Create('CompVoteVote')->Where('UserID', '=', $_SESSION['usr'])->Where('CompID', '=', $compid)->One();
            if ($vq->ID !== null) $cvote = $vq->EntryID;
        }
        $this->viewData['cvote'] = $cvote;
        return $this->View($model);
    }

    function VoteEntry($entryid)
    {
        $entry = new CompVoteEntry($entryid);
        $compid = $entry->CompID;
        Database::NonQuery('DELETE FROM compvoting_votes WHERE UserID = :uid AND CompID = :cid', array('uid' => $_SESSION['usr'], 'cid' => $compid));
        $vq = new CompVoteVote();
        $vq->CompID = $compid;
        $vq->EntryID = $entryid;
        $vq->UserID = $_SESSION['usr'];
        $vq->Save();
        return $this->RedirectToAction('ViewEntries', 'CompVoting', array($compid));
    }

    function DeleteEntry($entryid)
    {
        $entry = new CompVoteEntry($entryid);
        $compid = $entry->CompID;
        Database::NonQuery('DELETE FROM compvoting_screenshots WHERE EntryID = :id', array('id' => $entryid));
        $entry->Delete();
        return $this->RedirectToAction('ViewEntries', 'CompVoting', array($compid));
    }

    function EditEntry($entryid)
    {
        $model = Post::Bind(new CompVoteEntry($entryid));
        if (Post::IsPostBack() && Validation::Validate($model)) {
            $model->Save();
            return $this->RedirectToAction('ViewEntries', 'CompVoting', array($model->CompID));
        }
        return $this->View($model);
    }

    function CreateEntry($compid)
    {
        $model = Post::Bind(new CompVoteEntry());
        $model->CompID = $compid;
        if (Post::IsPostBack()) {
            if (!is_numeric($model->UserID)) {
                $user = Query::Create('User')->Where('Username', '=', $model->UserID)->One();
            } else {
                $user = new User($model->UserID);
            }
            $model->UserID = $user->ID;
            if ($model->UserID === null) {
                Validation::AddError('UserID', 'This is not a valid user.');
            }
            if (Validation::Validate($model)) {
                $model->Save();
                $name = str_replace(' ', '_', $user->Username);
                $fold = '/home/twhl/main/compopics/compo_'.str_pad($compid, 3, '0', STR_PAD_LEFT).'/';
                foreach (glob($fold.$name."_*.jpg") as $file)
                {
                    $fname = substr($file, strlen($fold));
                    $screen = new CompVoteScreenshot();
                    $screen->EntryID = $model->ID;
                    $screen->ImageLocation = $fname;
                    $screen->Save();
                }
                return $this->RedirectToAction('ViewEntries', 'CompVoting', array($compid));
            }
        }
        $this->viewData['comp'] = new Comp($compid);
        return $this->View($model);
    }

}

?>