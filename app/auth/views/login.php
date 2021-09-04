<?php
 if(hasMessages('login')): ?>
    <div class="alert alert-danger" role="alert">
      <?php messages('login') ?>
    </div>
<?php endif;
echo $loginForm;