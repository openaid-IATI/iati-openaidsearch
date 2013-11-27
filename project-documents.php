<div id="project-documents">
	<?php if(empty($activity->documents)) {?>
		No information available
		<?php } else { 
			foreach($activity->documents AS $doc) {
				$class	= "";
				if(!empty($doc->format)) {
					$class = " class='" . str_replace('application/', '', $doc->format) . "'";
				}
				echo "<div class='project-document-block'>";
				echo "<div class='project-document-icon'></div>";
				echo "<div class='project-document-title'>";

				echo "<a target='_blank' href='{$doc->url}'>";
				if(empty($doc->title)) {
					echo $activity->titles[0]->title;
				} else {
					echo $doc->title;
				}
				$s = array('bytes', 'kb', 'MB', 'GB', 'TB', 'PB');
				$bytes = strlen(file_get_contents($doc->url));
				$e = floor(log($bytes)/log(1024));
							 
				//CREATE COMPLETED OUTPUT
				$filesize = sprintf('%.2f '.$s[$e], ($bytes/pow(1024, floor($e))));
				echo " (" . $filesize . ")";
				echo "</a>";
				echo "</div></div>";
			} 
		} ?>
</div>
