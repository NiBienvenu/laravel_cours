<?php

namespace App\Http\Controllers;

use App\Http\Requests\AccidentStoreRequest;
use App\Http\Requests\AccidentUpdateRequest;
use App\Models\Accident;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AccidentController extends Controller
{
    public function index(Request $request): View
    {
        $accidents = Accident::all();

        return view('accident.index', [
            'accidents' => $accidents,
        ]);
    }

    public function create(Request $request): View
    {
        return view('accident.create');
    }

    public function store(AccidentStoreRequest $request): RedirectResponse
    {
        $accident = Accident::create($request->validated());

        $request->session()->flash('accident.id', $accident->id);

        return redirect()->route('accidents.index');
    }

    public function show(Request $request, Accident $accident): View
    {
        return view('accident.show', [
            'accident' => $accident,
        ]);
    }

    public function edit(Request $request, Accident $accident): View
    {
        return view('accident.edit', [
            'accident' => $accident,
        ]);
    }

    public function update(AccidentUpdateRequest $request, Accident $accident): RedirectResponse
    {
        $accident->update($request->validated());

        $request->session()->flash('accident.id', $accident->id);

        return redirect()->route('accidents.index');
    }

    public function destroy(Request $request, Accident $accident): RedirectResponse
    {
        $accident->delete();

        return redirect()->route('accidents.index');
    }
}
