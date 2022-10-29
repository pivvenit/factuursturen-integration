<div class="wrap">
    <h1><?php esc_attr_e($this->title); ?></h1>

    <?php echo $this->partial('admin/settings-help'); ?>

    <form method="post" action="options.php">
        <?php settings_fields($this->settings_key); ?>
        <?php do_settings_sections($this->settings_key); ?>

        <?php submit_button(); ?>
    </form>
</div> <!-- .wrap -->
