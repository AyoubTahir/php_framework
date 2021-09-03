<h1>create new user</h1>

<form action="/TahirSystem/store/user" method="post" enctype="multipart/form-data">
  <div class="mb-3">
    <label for="exampleInputEmail1" class="form-label">Email address</label>
    <input type="text" name="email" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp">
    <div class="form-text text-danger"><?php errorField('email') ?></div>
  </div>
  <div class="mb-3">
    <label for="exampleInputEmail11" class="form-label">confirme Email address</label>
    <input type="text" name="email_confirmation" class="form-control" id="exampleInputEmail11" aria-describedby="emailHelp">
    <div class="form-text text-danger"><?php errorField('email_confirmation') ?></div>
  </div>
  <div class="mb-3">
    <label for="exampleInputPassword1" class="form-label">Password</label>
    <input type="text" name="lastname" class="form-control" id="exampleInputPassword1">
    <div class="form-text text-danger"><?php errorField('lastname') ?></div>
  </div>
  <div class="mb-3">
    <label for="image" class="form-label">Image</label>
    <input type="file" name="image" class="form-control" id="image">
    <div class="form-text text-danger"><?php /*errorField('image') */?></div>
  </div>
  <button type="submit" class="btn btn-primary">Submit</button>
</form>