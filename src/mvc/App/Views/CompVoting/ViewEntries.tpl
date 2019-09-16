<div class="single-center">
    <h1>Voting Page for {$comp->Name}</h1>
    <p class="single-center-content">
        The order of entries is randomised on each refresh. You may only vote for one entry, but you can change your vote if you like.
    </p>
    {if Authorisation::$_method->HasCredentials(null, 40)}
        <p class="single-center-content">
            {actlink text='Create Entry' action=CreateEntry id=$comp->ID}<br/>
            {actlink text='View Results' action=ViewResults id=$comp->ID}
        </p>
    {/if}
    <table style="border-collapse: collapse; border: 1px solid #999; width: 95%; margin: 10px auto;">
    {$tnum=0}
    {foreach from=$model item=entry}
        {if $tnum==0}<tr>{/if}
        {$voted=($cvote==$entry->ID)}
        <td style="border: 1px solid #999; width: 33%; padding: 10px 0; text-align: center;{if $voted} background-color: #F9DBA7;{/if}">
            {$euser=$entry->Get('User')}
            <a href="/user.php?id={$euser->ID}">{$euser->Username}</a>
            {if Authorisation::$_method->HasCredentials(null, 40)}
                <span style="font-size: 10px;">
                    {actlink text='[E]' action=EditEntry id=$entry->ID}|{actlink text='[D]' action=DeleteEntry id=$entry->ID}
                </span>
            {/if}<br />
            {$entry->Name}
            <br/>
            {$shots=$entry->Find('CompVoteScreenshot')}
            {if count($shots) > 0}
                <div>
                {$screen=$shots[0]}
                <a rel="external" href="{$imgdir}{$screen->ImageLocation}">
                    <img style="display: block; margin: 5px auto; width: 95%;" src="{$imgdir}{$screen->ImageLocation}" alt="{$euser->Username}" />
                </a>
                {foreach from=$shots item=screen}
                    {if $screen@index != 0}
                        <a rel="external" href="{$imgdir}{$screen->ImageLocation}">
                            <img style="margin: 5px auto; width: 30%;" src="{$imgdir}{$screen->ImageLocation}" alt="{$euser->Username}" />
                        </a>
                    {/if}
                {/foreach}
                </div>
            {/if}
            {if Authorisation::$_method->HasCredentials(null, 0)}
                {if $voted}
                    You have voted for this entry.
                {elseif $comp->VoteStatus == 1}
                    {actlink text='Vote for this entry' action='VoteEntry' id=$entry->ID}
                {/if}
            {/if}
        </td>
        {$tnum=$tnum+1}
        {if $tnum==3||$entry->ID==$model[count($model)-1]->ID}</tr>{$tnum=0}{/if}
    {/foreach}
    </table>
</div>