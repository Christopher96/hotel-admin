<!--
  Created by: Christopher Gauffin
  Description: Converts the PHP GET object to JavaScript
!-->

<script>
  var $_GET = JSON.parse('<?= json_encode($_GET) ?>');
</script>
