<div class="single-center">
    <h1>Create Voting Entry for {$comp->Name}</h1>
    {form}
        <p>
            {label for=UserID text='User ID or Username'}
            {field for=UserID model=$model}
            {validation for=UserID}
        </p>
        <p>
            {label for=Name}
            {field for=Name model=$model}
            {validation for=Name}
        </p>
        <p>
            {submit value='Create'}
        </p>
    {/form}
</div>