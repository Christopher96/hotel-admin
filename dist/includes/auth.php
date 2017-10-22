<script>
    var active = '<?= $page ?>';
    <?php if(isset($user)) { ?>
        var auth = {
            "user_id": "<?= $user['id'] ?>",
            "session_id": "<?= session_id() ?>"
        };
    <?php } ?>
</script>