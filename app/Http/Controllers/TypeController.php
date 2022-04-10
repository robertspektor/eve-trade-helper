<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateTypeRequest;
use App\Models\Type;
use Illuminate\Http\Request;

class TypeController extends Controller
{
    public function updateTypeById(int $typeId, UpdateTypeRequest $request)
    {
        $validated = $request->validated();
        dd($validated);

        $type = Type::query()->find($typeId)->get();
        $type->favorite = !$type->favorite;
        $type->save();
    }
}
