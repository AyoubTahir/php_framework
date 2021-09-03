<h1>Users List</h1>

<?php if(hasMessages('success')): ?>
  <div class="alert alert-success" role="alert">
    <?php messages('success') ?>
  </div>
<?php endif; ?>
<!--
<table class="table">
  <thead>
    <tr>
      <th scope="col">#</th>
      <th scope="col">image</th>
      <th scope="col">Last Name</th>
      <th scope="col">Created At</th>
      <th scope="col">Modified At</th>
      <th scope="col">Actions</th>
    </tr>
  </thead>
  <tbody>
    <?php //foreach($users as $user):?>
        <tr>
            <th scope="row"><?php //echo $user->id; ?></th>
            <td><img src="<?php //storage('public/images/'.$user->image); ?>" class="rounded-circle" style="width: 50px;" alt="..."></td>
            <td><?php //echo $user->lastname; ?></td>
            <td><?php //echo $user->created_at; ?></td>
            <td><?php //echo $user->modified_at; ?></td>
            <td>
                <a class="btn btn-primary" href="/TahirSystem/edit/user/<?php //echo $user->id; ?>">edit</a>
                <a class="btn btn-danger" href="/TahirSystem/delete/user/<?php //echo $user->id; ?>">delete</a>
            </td>
        </tr>
    <?php //endforeach; ?>
  </tbody>
</table>
    -->
<?php
echo $table;
echo $pagination;
?>