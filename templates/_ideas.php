<?php foreach($ideas->all() as $idea) { ?>
    
    <?php $images = $ideas->get_images($idea); ?>

    <div class="my-3 p-3 idea" id="<?php echo $idea['id']; ?>">
        <h5 class="card-title font-weight-normal"><?php echo $idea["content"]; ?></h5>
        
        <div class="mb-3">
            <?php foreach($ideas->get_images($idea) as $image) { ?>
                <img class="img-fluid" src="<?php echo $myUrl.$ideas->dir_images.$image['image']; ?>">
            <?php } ?>
        </div>

        <?php if (!isset($_SESSION['user'])) { ?>
        
            <span><?php echo $idea["nb_votes"] ?> votes</span>

        <?php } else if (in_array($idea["id"], $ideas->votes())) { ?>
        
            <form class="d-inline-block" method="POST" action="<?php echo $myUrl."?section=vote"; ?>">
                <input type="hidden" name="id" value="<?php echo $idea["id"]; ?>">
                <button type="submit" class="btn btn-like">
                    <span class="text-primary"><?php echo $idea["nb_votes"]; ?> votes</span>
                </button>
            </form>

        <?php } else { ?>

            <form class="d-inline-block" method="POST" action="<?php echo $myUrl."?section=vote"; ?>">
                <input type="hidden" name="id" value="<?php echo $idea["id"]; ?>">
                <button type="submit" class="btn btn-like">
                    <span><?php echo $idea["nb_votes"]; ?> votes</span>
                </button>
            </form>

        <?php } ?>
    </div>

<?php } ?>