<form role="search" method="get" id="searchform" class="searchform" action="<?php echo home_url( '/' ); ?>">
    <div style="margin:1.5em;">
        <label for="s" class="screen-reader-text"><?php _e('Search for:','leonite'); ?></label>
        <input type="search" id="s" name="s" value="" style="margin:1.5em auto;width:80%;" />

        <button type="submit" id="searchsubmit" class="blue-btn"><?php _e('Search','leonite'); ?></button>
    </div>
</form>