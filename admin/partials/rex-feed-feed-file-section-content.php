<h2> <?php _e( 'Your Feed URL', 'rex-product-feed' )?> </h2>

<input type="text" name="<?php echo $this->prefix?>xml_file" id="<?php echo $this->prefix?>xml_file" value="<?php echo $feed_url?>" disabled>

<a href="<?php echo $feed_url?>" target="_blank" class="btn waves-effect waves-light btn-default">
    <i class="fa fa-external-link" aria-hidden="true"></i>
    <?php _e( 'View Feed', 'rex-product-feed' )?>
</a>

<a href="<?php echo $feed_url?>" target="_blank" class="btn waves-effect waves-light btn-default" download="">
    <i class="fa fa-download" aria-hidden="true"></i>
    <?php _e( 'Download Feed', 'rex-product-feed' )?>
</a>