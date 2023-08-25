<div class="col-sm-3 offset-1 blog-sidebar">
    <div class="sidebar-module sidebar-module-insert">
    <h5>Mon Widget des compétition</h5>
        <?php
        // affichage du widget dans la sidebar
        if (is_active_sidebar('new-widget-area')) : ?>
            <div id="secondary-sidebar" class="new-widget-area">
                <?php dynamic_sidebar("new-widget-area") ?>
            </div>

        <?php endif; ?>
    </div>
</div>