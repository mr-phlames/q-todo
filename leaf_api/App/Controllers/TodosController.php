<?php
namespace App\Controllers;

use App\Models\Todo;

class TodosController extends Controller {
    /**
     * Display a listing of the resource.
     */
    public function index() {
        // check and validate bearer token (proof of authentication)
        $payload = $this->auth->validateToken();
        // throw an error if there's a problem with token
        if (!$payload) $this->throwErr($this->auth->errors());

        // get user id from token
        $user_id = $payload->user_id;

        // retrieve user's todos
        $this->respondWithCode(Todo::where("user_id", $user_id)->get());
    }

    /**
     * Display a listing of the resource.
     */
    public function find($search) {
        // check and validate bearer token (proof of authentication)
        $payload = $this->auth->validateToken();
        // throw an error if there's a problem with token
        if (!$payload) $this->throwErr($this->auth->errors());

        // get user id from token
        $user_id = $payload->user_id;

        $search = Todo::where("user_id", $user_id)->where("name", "LIKE", "$search%")->get();

        // retrieve user's todos
        $this->respondWithCode($search);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create() {
        // 
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store() {
        // token validation
        $payload = $this->auth->validateToken();
        if (!$payload) $this->throwErr($this->auth->errors());

        $user_id = $payload->user_id;

        // get values passed into request, values are checked by default for scripts
        $task = $this->request->get("todo");

        // add a new todo
        $todo = new Todo;
        $todo->user_id = $user_id;
        $todo->name = $task;
        $todo->status = "in_progress";
        $todo->save();

        $this->respondWithCode($todo);
    }

    /**
     * Display the specified resource.
     */
    public function show($id) {
        // make sure user is logged in
        $payload = $this->auth->validateToken();
        if (!$payload) $this->throwErr($this->auth->errors());

        // retrieve user's todos
        $this->respondWithCode(Todo::find($id));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id) {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update($id) {
        // make sure user is logged in
        $payload = $this->auth->validateToken();
        if (!$payload) $this->throwErr($this->auth->errors());

        // get values passed into request, values are checked by default for scripts
        $task = $this->request->get("todo");
        $status = $this->request->get("status");

        // add a new todo
        $todo = Todo::find($id);
        $todo->name = $task;
        $todo->status = $status;
        $todo->save();

        $this->respondWithCode(Todo::find($id));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id) {
        // make sure user is logged in
        $payload = $this->auth->validateToken();
        if (!$payload) $this->throwErr($this->auth->errors());

        $user_id = $payload->user_id;

        // delete todo
        Todo::find($id)->delete();

        $this->respondWithCode(Todo::where("user_id", $user_id)->get());
    }
}