<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserRequest;
use App\User;
use Illuminate\Http\Request;
use DB;
use Validator;
use Crypt;
use Yajra\DataTables\Facades\DataTables;

class UserController extends Controller
{
    public function index() {
        return view('users.index');
    }

    public function validationRules($request) {
        if (!$request->ajax()) {
            abort('404');
        }

        if (isset($request->eid) || !empty($request->eid)) {

            $id = Crypt::decrypt($request->eid);
            $rules = [
                'name' => 'required|string|max:100|regex:/(^([a-zA-Z ]+)$)/u',
                'email' => 'required|string|email|max:200|unique:users,email,'.$id
            ];

            isset($request->password) ? $rules['password'] =
                'required|string|min:8|confirmed|regex:/(^\S*(?=\S{8,})(?=\S*[a-z])(?=\S*[A-Z])(?=\S*[\d])(?=\S*[\W])\S*$)/u' :
                null;

            return $rules;
        }

        $rules =  [
            'name' => 'required|string|max:100|regex:/(^([a-zA-Z ]+)(\d+)?$)/u',
            'email' => 'required|string|email|max:200|unique:users',
            'password' => 'required|string|min:8|confirmed|regex:/(^\S*(?=\S{8,})(?=\S*[a-z])(?=\S*[A-Z])(?=\S*[\d])(?=\S*[\W])\S*$)/u'
        ];

        return $rules;
    }

    public function store(Request $request) { //UserRequest $request
        DB::beginTransaction();

        $validator = Validator::make($request->all(), $this->validationRules($request));

        if ($validator->fails()) {
            return ['type' => 'error', 'message' => $validator->errors()->first()];
        }

        #$request->validated();
        $saved = User::create($request->all());

        if ($saved) {
            DB::commit();
            return ['type' => 'success', 'message' => 'Successfully saved'];
        }

        DB::rollBack();
        return ['type' => 'error', 'message' => 'Failed to save'];

    }

    public function show(Request $request) {
        if (!$request->ajax()) {
            abort('404');
        }

        $id = (int) Crypt::decrypt($request->get('id'));
        $user = User::findOrFail($id);

        if ($user) {
            $data = [
                'response' => 'success',
                'name' => e($user->name),
                'email' => (string) e($user->email),
            ];

            return json_encode($data);
        }

        $data = [
            'response' => 'failed',
            'message' => 'No Service Found'
        ];

        return json_encode($data);
    }

    public function update(Request $request) {
        DB::beginTransaction();

        $validator = Validator::make($request->all(), $this->validationRules($request));

        if ($validator->fails()) {
            return ['type' => 'error', 'message' => $validator->errors()->first()];
        }

        $id = (int) Crypt::decrypt($request->eid);
        $update = User::findOrFail($id);

        $update->name = $request->name;
        $update->email = $request->email;
        isset($request->password) ? $update->password = $request->password : null;

        if ($update->save()) {
            DB::commit();
            return ['type' => 'success', 'message' => 'Successfully updated'];
        }

        DB::rollBack();
        return ['type' => 'error', 'message' => 'Failed to update'];

    }

    public function delete(Request $request) {
        if (!$request->ajax()) {
            abort('404');
        }

        DB::beginTransaction();
        $id = (int) Crypt::decrypt($request->id);

        $user = User::findOrFail($id)->delete();

        if ($user) {
            DB::commit();
            return ['type' => 'success', 'message' => 'Successfully deleted'];
        }

        DB::rollBack();
        return ['type' => 'error', 'message' => 'Failed to update'];

    }

    public function userList() {
        return Datatables::eloquent(User::select(['id', 'name', 'email', 'created_at'])->latest())
            ->addColumn('created_at', function($query){
                return date('l, F, d, Y h:i',strtotime($query->created_at));
            })
            ->addColumn('actions', function ($selectQuery) {
                $buttons = '';
                $id = Crypt::encrypt($selectQuery->id);
                $buttons = '<button id="' . e($id) . '" class="btn btn-primary btn-sm callModal" data-action="update">Edit </button> <button id="' . e($id) . '" class="btn btn-danger btn-sm delete">Delete </button>';
                return $buttons;
            })
            ->smart(true)
            ->escapeColumns([])
            ->addIndexColumn()
            ->make(true);

    }

}
