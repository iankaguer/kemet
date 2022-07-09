<form action="option.php">
    <?php settings_fields('kemet_projects_options'); ?>
    <?php do_settings_sections('kemet_projects'); ?>
    <?php submit_button(); ?>
</form>