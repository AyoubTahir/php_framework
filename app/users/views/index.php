<h1>Users List</h1>

<?php if(hasMessages('success')): ?>
  <div class="alert alert-success" role="alert">
    <?php messages('success') ?>
  </div>
<?php endif;

echo $table;
?>