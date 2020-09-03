    <div id="areamanager-category-extend">
        <div class="form-field form-type-wrap">
            <label><?php echo __('category_type',AREAMANAGER); ?></label>
            <select id="type" name="type">
            	<option value="notarea"<?php if ($this->type == 'notarea') echo ' selected="selected"'; ?>>
            		<?php echo __('notarea',AREAMANAGER); ?></option>
            	<option value="continent"<?php if ($this->type == 'continent') echo ' selected="selected"'; ?>>
            		<?php echo __('continent',AREAMANAGER); ?></option>
            	<option value="country"<?php if ($this->type == 'country') echo ' selected="selected"'; ?>>
            		<?php echo __('country',AREAMANAGER); ?></option>
            	<option value="region_1"<?php if ($this->type == 'region_1') echo ' selected="selected"'; ?>>
            		<?php echo __('region_1',AREAMANAGER); ?></option>
            	<option value="region_2"<?php if ($this->type == 'region_2') echo ' selected="selected"'; ?>>
            		<?php echo __('region_2',AREAMANAGER); ?></option>
            	<option value="locality"<?php if ($this->type == 'locality') echo ' selected="selected"'; ?>>
            		<?php echo __('locality',AREAMANAGER); ?></option>
            	<option value="sublocality"<?php if ($this->type == 'sublocality') echo ' selected="selected"'; ?>>
            		<?php echo __('sublocality',AREAMANAGER); ?></option>
            	<option value="postalcode"<?php if ($this->type == 'postalcode') echo ' selected="selected"'; ?>>
            		<?php echo __('postalcode',AREAMANAGER); ?></option>
            	<option value="local_pol_zone"<?php if ($this->type == 'local_pol_zone') echo ' selected="selected"'; ?>>
            		<?php echo __('local_pol_zone',AREAMANAGER); ?></option>
            	<option value="country_pol_zone"<?php if ($this->type == 'county_pol_zone') echo ' selected="selected"'; ?>>
            		<?php echo __('country_pol_zone',AREAMANAGER); ?></option>
            </select>
        </div>
        
        <div id="areamanagerAreaInfo" style="display:none">
            <div class="form-field form-enable_start-wrap">
                <label><?php echo __('enable_start',AREAMANAGER); ?></label>
                <input type="text" id="enableStart" name="enableStart" value="<?php  echo $this->enableStart; ?>" />
        	</div>
            <div class="form-field form-enable_end-wrap">
                <label><?php echo __('enable_end',AREAMANAGER); ?></label>
                <input type="text" id="enableEnd" name="enableEnd" value="<?php  echo $this->enableEnd; ?>" />
        	</div>
            <div class="form-field form-central-wrap">
                <label><?php echo __('central',AREAMANAGER); ?></label>
                <input type="text" id="central" name="central" value="<?php  echo $this->central; ?>" />
        	</div>
            <div class="form-field form-population-wrap">
                <label><?php echo __('population',AREAMANAGER); ?></label>
                <input type="text" id="population" name="population" value="<?php  echo $this->population; ?>" />
        	</div>
            <div class="form-field form-place-wrap">
                <label><?php echo __('place',AREAMANAGER); ?>&nbsp;&nbsp;&nbsp;</label>
                <input type="text" id="place" name="place" value="<?php  echo $this->place; ?>" />
        	</div>
            <div class="form-field form-poligon-wrap" style="display:none">
                <label><?php echo __('poligon',AREAMANAGER); ?></label>
                <textarea row="20" cols="80" id="poligon" name="poligon"><?php echo $this->poligon; ?></textarea>
        	</div>
            <div class="form-field form-map-wrap">
                <div id="map" style="width:520px; height:450px"></div>
        	</div>
            <div class="form-field form-button-wrap">
                <button type="button" id="delpoligonBtn">
                	<?php echo __('del_poligon',AREAMANAGER); ?></button>
        	</div>
    	</div>
	</div>
   <script src="<?php echo get_site_url();?>/wp-content/plugins/areamanager/js/areamanager.js"></script>
   <script src="https://maps.googleapis.com/maps/api/js?key=<?php echo $this->gApiKey; ?>&callback=initMap&libraries=&v=weekly"></script>
   <script src="https://polyfill.io/v3/polyfill.min.js?features=default"></script>
