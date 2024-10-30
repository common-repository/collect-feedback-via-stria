<div class="wrap">
    <h2><?php echo $this->plugin->displayName; ?></h2>

    <?php
    if ( isset( $this->message ) ) {
        ?>
        <div class="updated fade"><p><?php echo $this->message; ?></p></div>
        <?php
    }
    if ( isset( $this->errorMessage ) ) {
        ?>
        <div class="error fade"><p><?php echo $this->errorMessage; ?></p></div>
        <?php
    }
    ?>

    <div id="poststuff">
    	<div id="post-body" class="metabox-holder columns-2">
    		<!-- Content -->
    		<div id="post-body-content">
				<div id="normal-sortables" class="meta-box-sortables ui-sortable">
	                <div class="postbox">
	                    <h3 class="hndle"><?php _e( 'Settings', $this->plugin->name ); ?></h3>
	                    <div class="inside">
		                    <form action="options-general.php?page=<?php echo $this->plugin->name; ?>" method="post">
		                    	<p>
		                    		<label for="stria-token"><strong><?php _e( 'Tracking Code:', $this->plugin->name ); ?></strong></label>
		                    		<textarea name="stria-token" id="stria-token" class="widefat" rows="7" style="font-family:Courier New;"><?php echo $this->settings['stria-token']; ?></textarea>
		                    		<?php _e( 'Paste the Tracking Code here which you get from <a href="https://dashboard.stria.co">Stria Dashboard</a>.'); ?>
		                    	</p>
		                    	<?php wp_nonce_field( $this->plugin->name, $this->plugin->name.'_nonce' ); ?>
		                    	<p>
									<input name="submit" type="submit" name="Submit" class="button button-primary" value="<?php _e( 'Save', $this->plugin->name ); ?>" />
								</p>
						    </form>
	                    </div>
	                </div>
				</div>
    		</div>
    	</div>
	</div>
</div>