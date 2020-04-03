<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class Task extends Controller
{
    protected $json_path;

    public function __construct()
    {
        $this->json_path = storage_path() . "\app\ToDo.json";
    }

    public function index()
    {
        return view('task.index',[
            'tasks' => $this->get_task_list(),
        ]);
    }

    public function add_task(Request $request)
    {
        $json_array = $this->get_task_list();
        $this->validate($request, [
            'name' => 'required|max:255',
        ]);

        $json_array[] = $request->name;

        file_put_contents($this->json_path, json_encode($json_array));
        return redirect('/tasks');
    }

    public function remove_task($index)
    {
        $json_array = $this->get_task_list();
        unset($json_array[$index]);

        file_put_contents($this->json_path, json_encode($json_array));
        return redirect('/tasks');
    }

    private function get_task_list()
    {
        $this->check_to_do_json();
        $json = file_get_contents($this->json_path);
        return json_decode($json, true);
    }

    private function check_to_do_json()
    {
        if(!is_file($this->json_path))
        {
            file_put_contents($this->json_path, '[]');
        }
    }
}
