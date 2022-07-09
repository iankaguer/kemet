<div class="wrap">
    <h1><?php echo esc_html(get_admin_page_title()); ?></h1>
    <form action="options.php" method="post">
        <?php
        settings_fields('kemet_projects_options');
        do_settings_sections('kemet_projects');
        submit_button();
        ?>
    </form>
</div>