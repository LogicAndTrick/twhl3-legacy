<div class="single-center">
    <h1>Voting Results for {$comp->Name}</h1>
    <p class="single-center-content">
        {actlink text='Back to Voting Page' action=ViewEntries id=$comp->ID}
    </p>
    <table style="border-collapse: collapse; border: 1px solid #999; width: 95%; margin: 10px auto;">
        <tr>
            <th style="padding: 5px; border: 1px solid #999;">Entry</th>
            <th style="padding: 5px; border: 1px solid #999;">Votes</th>
        </tr>
    {foreach $model as $row}
        <tr>
            <td style="padding: 5px; border: 1px solid #999;"><a href="/user.php?id={$row->UserID}">{$row->Username}</a>{if strlen(trim($row->Name)) > 0} - {$row->Name}{/if}</td>
            <td style="padding: 5px; border: 1px solid #999;">{$row->NumVotes}</td>
        </tr>
    {/foreach}
    </table>
</div>