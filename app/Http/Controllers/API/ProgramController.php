<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProgramRequest;
use App\Models\Program;
use Illuminate\Http\Request;

class ProgramController extends Controller
{

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $programs = Program::paginate(10);
        return response()->json($programs, 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ProgramRequest $request)
    {
        $program = Program::create($request->validated());
        return response()->json($program, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $program = Program::find($id);
        if (!$program) {
            return response()->json(['message' => 'Program not found'], 404);
        }
        return response()->json($program, 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ProgramRequest $request, $id)
    {
        $program = Program::find($id);
        if (!$program) {
            return response()->json(['message' => 'Program not found'], 404);
        }
        $program->update($request->validated());
        return response()->json($program, 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $program = Program::find($id);
        if (!$program) {
            return response()->json(['message' => 'Program not found'], 404);
        }
        $program->delete();
        return response()->json(['message' => 'Program deleted successfully'], 200);
    }
}
