<?php $user_ideas = $ideas->user_ideas(); ?>
<?php if ($user_ideas >= 5) { ?>
    Nombre maximum d'idées atteint.
<?php } else { ?>
    <div class="my-3 p-3 idea">
        <form method="POST" action="<?php echo $myUrl."?section=post"; ?>" enctype="multipart/form-data">
            <div class="form-group">
                <input type="text" class="form-control" id="idea" name="idea" minlength="5" maxlength="250" placeholder="Votre idée&hellip;">
            </div>

            <div class="form-group mt-2 mb-1">
                <input type="file" name="file[]" id="file" class="inputfile" data-multiple-caption="{count} fichiers" multiple>
                <label for="file" class="btn btn-secondary"><span>Ajouter des images</span></label>
                <input class="btn btn-primary" type="submit" value="Envoyer">
            </div>

            <span class="text-muted small">Il vous reste <?php echo 5 - $user_ideas; ?> idée(s).</span>
        </form>
    </div>
<?php } ?>