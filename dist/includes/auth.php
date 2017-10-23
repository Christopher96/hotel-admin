<script>
    var active = '<?= $page ?>';
    var auth = {};
    <?php if(isset($user)) { ?>
        auth.user_id = "<?= $user['id'] ?>";
        auth.session_id = "<?= session_id() ?>";
    <?php } ?>
</script>