<div id="map-wrapper">

	<div id="map-filter-overlay">
		<div class="container">
			<div class="row-fluid map-filter-list">

				<?php 
				for ($i = 0;$i<=74;$i++){ 
					if(in_array($i, array(0, 15, 30, 45, 60))){
						echo '<div class="span2">';
					}
					?>

					<div class="squaredThree">
						<input type="checkbox" value="None" id="land<?php echo $i; ?>" name="check" checked />
						<label class="map-filter-cb-value" for="land<?php echo $i; ?>"></label>
						<span>Land <?php echo $i; ?></span>
					</div>

					<?php
					if(in_array($i, array(14,29,44, 59,74))){
						echo '</div>';
					}
				} ?>
				
			</div>

		</div>

		<div id="map-filters-buttons">
			<div class="container">
				<div class="row-fluid">
					<div class="span12">

						<button id="map-filter-cancel" class="hneue-bold">CANCEL</button>
						<button id="map-filter-save" class="hneue-bold">SAVE</button>

					</div>
				</div>
			</div>
		</div>

	</div>

	<div id="map"></div>

</div>

<div id="map-hide-show">
	<div class="container">
		<div class="row-fluid">
			<div class="span12">
				<button id="map-hide-show-button" class="map-show"><img class="hide-show-icon" src="<?php echo get_template_directory_uri(); ?>/images/hide-show.png" alt="" /><span id="map-hide-show-text" class="hneue-bold">HIDE MAP</span></button>
			</div>
		</div>
	</div>
</div>