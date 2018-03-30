<div class="my-3 p-3 idea">
    <form method="POST" action="<?php echo $myUrl."?section=post"; ?>">
        <label for="idea">Soumettre une idée :</label>
        <div class="input-group">
            <input type="text" class="form-control" id="idea" name="idea">
            <div class="input-group-append">
                <button class="btn btn-primary" type="submit">Envoyer</button>
            </div>
        </div>
    </form>
</div>