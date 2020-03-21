<?php if ( count( $this->tags ) ) { ?>

    <?php foreach ( $this->tags as $tag ) { ?>
    <span style="padding: 8px; color: <?php echo $tag->tag_color; ?>"><?php echo $tag->tag_name; ?></span>
    <?php } ?>

<?php } else { ?>

    <span style="color: grey; padding: 8px;">No Tags Found</span>

<?php } ?>