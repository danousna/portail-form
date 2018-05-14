<?php if (isset($_SESSION['error'])) { ?>

    <div class="alert alert-danger alert-dismissible show">
        <strong>Erreur : </strong> <?php echo $_SESSION['error']; ?> 
        <button type="button" class="close" data-dismiss="alert" aria-label="Fermer">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>

    <?php unset($_SESSION['error']); ?>
    
<?php } ?>

<?php if (isset($_SESSION['success'])) { ?>

    <div class="alert alert-success alert-dismissible show">
        <strong>Succès : </strong> <?php echo $_SESSION['success']; ?>
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>

    <?php unset($_SESSION['success']); ?>

<?php } ?>

<?php $user_ideas = $ideas->user_ideas(); ?>
<?php if ($user_ideas >= 5) { ?>
    Nombre maximum d'idées atteint.
<?php } else { ?>
    <div class="my-3 p-3 idea">
        <form method="POST" action="<?php echo $myUrl."?section=post"; ?>" enctype="multipart/form-data" id="post-form">
            <div class="form-group mb-2">
                <input type="text" class="form-control" id="idea" name="idea" minlength="5" maxlength="250" placeholder="Votre idée&hellip;">
            </div>

            <div class="form-group mb-1">
                <input type="file" name="file[]" id="file" class="inputfile" data-multiple-caption="{count} fichiers" multiple>
                <label for="file" class="btn btn-secondary m-0"><span>Ajouter des images</span></label>
                <input class="btn btn-primary" type="submit" value="Envoyer" id="post-btn">
            </div>

            <span class="text-muted small">Il vous reste <?php echo 5 - $user_ideas; ?> idée(s). Taille totale des images : 10M.</span>
        </form>
    </div>
<?php } ?>