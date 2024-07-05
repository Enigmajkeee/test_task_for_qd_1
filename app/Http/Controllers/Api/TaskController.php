<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\TaskResource;
use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;

class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $tasks = Task::when($request->has('status'), function ($query) use ($request) {
            $query->where('status', $request->status);
        })
            ->when($request->has('created_at'), function ($query) use ($request) {
                $query->whereDate('created_at', $request->created_at);
            })
            ->get();

        return response($tasks);

        return TaskResource::collection(Task::all());
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     */
    public function store(Request $request)
    {
        $new_task = Task::create($request->all());

        return new TaskResource($new_task);
    }

    /**
     * Display the specified resource.
     */
    public function show(Task $task)
    {
        return new TaskResource($task);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Task $task)
    {
        $task->update($request->all());

        return new TaskResource($task);
    }

    /**
     * Remove the specified resource from storage.
     * @param Task $task
     * @param $task
     */
    public function destroy(Task $task)
    {
        $task->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
