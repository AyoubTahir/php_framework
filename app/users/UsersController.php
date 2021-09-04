<?php

namespace App\Users;

use Tahir\Base\Controller;
use App\Users\UsersDatatable;
use App\Users\Forms\AddNewUserForm;

class UsersController extends Controller
{
    protected $models = [
        'users' => 'users/Users'
    ];

    public function index()
    {
        //$data = $this->users->all();

        $data = $this->users->select();

        $dataTable = $this->datatable->create(UsersDatatable::class,$data)->table();

        return $this->view->render('users/views/index',['table'=>$dataTable],__r('title'));
    }

    public function blog($text,$id,$iid)
    {
        /*$this->db->data([
            'name' => 'fdffd',
            'email' => 'fdfdfeeeee'
        ])->insert('users');*///->rawQuery('INSERT INTO users SET lastname=?','sdfdfsdfsfds')

        //dd($this->db->lastId());
            /*
        $this->db->data([
            'lastname' => 'rtrrtrtrtrtrtr',
        ])->where('id', '<', 14)->andWhere('id', '=', 13)->orWhere('id', '<', 12)->update('users');*/
        /*
        $user = $this->db->select()
                         ->from('users')
                         ->where('id', '>', 10)
                         ->andWhere('id', '<', 14)
                         ->orderBy('id','DESC')
                         ->fetchAll();

        return $user;*/

        /*$user = $this->db->where('id', '=', 9)
                         ->delete('users');*/

        //$this->db->fetchAll('users');
        //echo $this->db->count();

        $users = $this->load->model('users/Users');

        //return $users->all();

        //$user = $users->findOrFail(13);

        //return $user;

        /*$users->create([
            'lastname' => 'ayoubbbb',
        ]);*/

        /*$users->update([
            'lastname' => 'raeaedqdf',
        ],10);*/

        //$users->delete([14,15]);

        /*return $users->select('*')
                    //->where('id', '>', 10)
                    //->andWhere('id', '<', 14)
                    //->orderBy('id','DESC')
                    ->get();*/


        //return $this->view->render('users/views/index',['tt'=>'rrrrrrr'],'f ggg');

    }

    public function create()
    {
        $addUserForm = $this->form->create(AddNewUserForm::class)->form();

        return $this->view->render('users/views/create',['addUserForm' => $addUserForm],__('title'));
    }

    public function store($request)
    {
        //dd($request->all());
        //|between:2,6|unique:users,username
        $request = $request->validate([
        'lastname' => 'required|max:5|min:3|unique:users',
        //'email' => 'required|max:200|email',
        //'email_confirmation' => 'required|confirmed:email'
        ]);

        $data = $request->only(['lastname']);

        $image = $request->store('image','public/images');

        $data['image'] = $image;

        $this->users->create($data);

        return $this->url->addMessage('success','added successfuly')->redirectTo('/users');
        //header('Location: /TahirSystem/users');
        
    }

    public function delete($id)
    {
        $this->users->delete($id);

        return $this->url->addMessage('success','deleted successfuly')->redirectTo('/users');
    }
}