<div id="areamanagerAdminForm"> 
 <h1>AREA MANAGER PLUGIN ADMIN</h1>
 <p><?php echo __('Extend area manager capacity into wooCoomerce category',AREAMANAGER); ?></p>
 <p>Licence: GNU/GPL  <?php echo __('Author',AREAMANAGER); ?>: Tibor Fogler   tibor.fogler@gmail.com</p>
 <img src="<?php echo get_site_url(); ?>/wp-content/plugins/areamanager/images/kepernyokep.png"
     style="width:400px;"/>
 <h2><?php echo __('Setup',AREAMANAGER); ?></h2>
 <form method="post" action="#" class="form">
 	<input type="hidden" name="task" value="setupSave" />
 	<div class="form-group">
 		<label><?php echo __('google_api_key',AREAMANAGER); ?>:</label>
        <input type="text" name="gApiKey" value="<?php echo $this->gApiKey; ?>" style="width:600px" />
 	</div>
 	<div>see 
 	<a href="https://developers.google.com/maps/documentation/javascript/get-api-key" target="_new">
 		https://developers.google.com/maps/documentation/javascript/get-api-key
 	</a>
 	<div class="form-group">&nbsp;</div>
 	<div class="form-group">
 		<button type="submit" class="button button-primary">
 			<?php echo __('save',AREAMANAGER); ?>:</button>
        <button type="button" class="button button-secondary" 
        	onclick="location='<?php echo get_site_url(); ?>/wp-admin/options-general.php?page=areamanager';">
        	<?php echo __('cancel',AERAMANAGER); ?></button>
 	</div>
 </form>
 <div>&nbsp;</div>
 <!--  Késöbb esetleg...  div style="text-align:center">
    <button type="button" class="button button-secondary" 
    	onclick="location='<?php echo get_site_url(); ?>/wp-admin/options-general.php?page=areamanager&option=admin&task=createareas';">
    	<?php echo __('create_areas',AREAMANAGER); ?></button>
    <button type="button" class="button button-secondary" 
    	onclick="location='<?php echo get_site_url(); ?>/wp-admin/options-general.php?page=areamanager&option=admin&task=deleteareas';">
    	<?php echo __('delete_areas',AREAMANAGER); ?></button>
 </div -->
</div> 