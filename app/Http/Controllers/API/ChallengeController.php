<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ChallengeRequest;
use App\Models\Challenge;
use Illuminate\Http\Request;

class ChallengeController extends Controller
{
    public function index()
    {
        $challenges = Challenge::paginate(10);
        return response()->json($challenges, 200);
    }

    public function store(ChallengeRequest $request)
    {
        $challenge = Challenge::create($request->all());
        return response()->json($challenge, 201);
    }

    public function show($id)
    {
        $challenge = Challenge::find($id);
        if (!$challenge) {
            return response()->json(['message' => 'Desafío no encontrado'], 404);
        }
        return response()->json($challenge, 200);
    }


    public function update(Request $request, $id)
    {
        $challenge = Challenge::find($id);
        if (!$challenge) {
            return response()->json(['message' => 'Desafío no encontrado'], 404);
        }

        $challenge->update($request->all());
        return response()->json($challenge, 200);
    }
    public function destroy($id)
    {
        $challenge = Challenge::find($id);
        if (!$challenge) {
            return response()->json(['message' => 'Desafío no encontrado'], 404);
        }
        $challenge->delete();
        return response()->json(['message' => 'Desafío eliminado correctamente'], 200);
    }

    //
}
