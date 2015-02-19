<div id="project-documents">
	<?php if(empty($activity->document_links)) {?>
		No information available
	<?php } else { 
		foreach($activity->document_links AS $doc) {
			$class	= "";
			if(!empty($doc->format)) {
				$class = " class='" . str_replace('application/', '', $doc->format) . "'";
			}
			echo "<div class='project-document-block'>";
			echo "<div class='project-document-icon'></div>";
			echo "<div class='project-document-title'>";

			echo "<a target='_blank' href='{$doc->url}'>";
			if(empty($doc->title->narratives[0]->text)) {
				echo $doc->url;
			} else {
				echo $doc->title->narratives[0]->text;
			}

			echo "</a>";
			echo "</div></div>";
		} 
	} ?>
</div>
