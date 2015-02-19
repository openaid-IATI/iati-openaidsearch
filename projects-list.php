<div id="page-wrapper"> 
    <div class="page-header">
    	<div class="container">
    		<div id="projects-search-navbar" class="row">
    			<div class="col-md-7">
    				<div class="projects-pagination-info hneue-bold">
    					<div id="pagination-totals">
    						RESULTS <span id="page-first-count">1</span> - <span id="page-last-count"><?php echo OIPA_PER_PAGE; ?></span> OF <span id="page-total-count"></span>
    					</div>

    					<div id="sort-type-amount" class="project-sort-type">
                            <span class="project-sort-text hneue-bold">SHOW <?php echo OIPA_PER_PAGE; ?></span>
                            <span class="project-sort-icon"></span>

                            <div id="dropdown-project-amount" class="dropdown-project">
                                <a href="?per_page=5<?php echo wp_generate_link_parameters("per_page"); ?>" class="dropdown-project-amount-link">5</a>
                                <a href="?per_page=10<?php echo wp_generate_link_parameters("per_page"); ?>" class="dropdown-project-amount-link">10</a>
                                <a href="?per_page=25<?php echo wp_generate_link_parameters("per_page"); ?>" class="dropdown-project-amount-link">25</a>
                                <a href="?per_page=50<?php echo wp_generate_link_parameters("per_page"); ?>" class="dropdown-project-amount-link">50</a>
                            </div>

    					</div>
    					<a id="save-search-results" href="#save-search-results">EXPORT SEARCH RESULTS</a>
                        <a href="#" id="project-share-embed" class="project-page project-share-button hneue-bold">
                            <div class="share-icon"></div>
                            <div class="share-text">EMBED MAP</div>
                            <div id="dropdown-embed-url" class="dropdown-menu-page-header">
                                Code to embed: <br>
                                <textarea name="embed-url"></textarea>
                                <div id="project-share-embed-close">close</div>
                            </div>
                        </a>
    				</div>
    			</div>
    			<div class="col-md-5">
    				<div class="projects-sorting hneue-bold">
                        <div id="sort-type-header">
                            SORT BY:
                        </div>

                        <div id="sort-type-budget" class="project-sort-type">
                            <span class="project-sort-text hneue-bold">BUDGET</span>
                            <span class="project-sort-icon"></span>
                            <div id="dropdown-project-budget" class="dropdown-project">
                                <a href="?order_by=total_budget<?php echo wp_generate_link_parameters("order_by"); ?>" class="project-sort-item">ASCENDING</a>
                                <a href="?order_by=-total_budget<?php echo wp_generate_link_parameters("order_by"); ?>" class="project-sort-item">DESCENDING</a>
                            </div>
                        </div>

                        <div id="sort-type-startdate" class="project-sort-type">
                            <span class="project-sort-text hneue-bold">START DATE</span>
                            <span class="project-sort-icon"></span>
                            <div id="dropdown-project-startdate" class="dropdown-project">
                                <a href="?order_by=start_planned<?php echo wp_generate_link_parameters("order_by"); ?>" class="project-sort-item">ASCENDING</a>
                                <a href="?order_by=-start_planned<?php echo wp_generate_link_parameters("order_by"); ?>" class="project-sort-item">DESCENDING</a>
                            </div>
                        </div>

    				</div>
    			</div>
    		</div>
    	</div>
    </div>
    <div id="project-list-wrapper" class="page-content">
        <?php include( TEMPLATEPATH .'/ajax/project-list-ajax.php' ); ?>
    </div>
    <div>
    	<div class="container">
    		<div class="row">
    			<div class="col-md-12">
    				<div id="project-list-pagination">
    				</div>
    			</div>
    		</div>
    	</div>
    </div>
</div>