<?php $user_ideas = $ideas->user_ideas(); ?>
<?php if ($user_ideas >= 5) { ?>
    Nombre maximum d'idées atteint.
<?php } else { ?>
    <div class="my-3 p-3 idea">
        <form method="POST" action="<?php echo $myUrl."?section=post"; ?>">
            <label for="idea">Soumettre une idée :</label>
            <div class="input-group">
                <input type="text" class="form-control" id="idea" name="idea" minlength="5" maxlength="250">
                <div class="input-group-append">
                    <button class="btn btn-primary" type="submit">Envoyer</button>
                </div>
            </div>
            <span class="text-muted">Il vous reste <?php echo 5 - $user_ideas; ?> idée(s).</span>
        </form>
    </div>
<?php } ?>