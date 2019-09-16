<div class="single-center">
    <h1>Edit Voting Entry: {$model->Name}</h1>
    {form}
        <p>
            {label for=Name}
            {field for=Name model=$model}
            {validation for=Name}
        </p>
        <p>
            {submit value='Edit'}
        </p>
    {/form}
</div>