<?php

namespace App\Http\Controllers;

use App\Models\Part;
use Illuminate\Http\Request;

class PartController extends Controller
{

    public function markAsReplaced(Request $request, Part $part)
    {
        $this->authorize('update', $part->car);

        $part->markAsReplaced();

        return response()->json(['success' => true]);
    }


    public function store(Request $request)
    {
        $request->validate([
            'car_id' => 'required|exists:cars,id',
            'name' => 'required|string|max:255',
            'category' => 'required|string|max:100',
            'replacement_interval' => 'required|integer|min:1000',
        ]);

        $car = \App\Models\Car::findOrFail($request->car_id);
        $this->authorize('update', $car);

        $part = Part::create([
            'car_id' => $car->id,
            'name' => $request->name,
            'category' => $request->category,
            'replacement_interval' => $request->replacement_interval,
            'last_replacement_mileage' => $car->current_mileage,
            'next_replacement_mileage' => $car->current_mileage + $request->replacement_interval,
            'status' => 'good',
            'notes' => $request->notes,
        ]);

        return response()->json([
            'success' => true,
            'part' => $part
        ]);
    }


    public function update(Request $request, Part $part)
    {
        $this->authorize('update', $part->car);

        $request->validate([
            'name' => 'required|string|max:255',
            'replacement_interval' => 'required|integer|min:1000',
        ]);

        $part->update($request->only(['name', 'replacement_interval', 'notes']));
        $part->updateStatus();

        return response()->json(['success' => true]);
    }


    public function destroy(Part $part)
    {
        $this->authorize('update', $part->car);

        $part->delete();

        return response()->json(['success' => true]);
    }


    public function destroyBatch(Request $request)
    {
        $request->validate([
            'part_ids' => 'required|array',
            'part_ids.*' => 'integer|exists:parts,id',
        ]);

        $deleted = 0;
        foreach ($request->part_ids as $id) {
            $part = Part::find($id);
            if ($part && $request->user()->can('update', $part->car)) {
                $part->delete();
                $deleted++;
            }
        }

        return response()->json(['success' => true, 'deleted' => $deleted]);
    }

    public function replaceBatch(Request $request)
    {
        $request->validate([
            'part_ids' => 'required|array',
            'part_ids.*' => 'integer|exists:parts,id',
        ]);

        $replaced = 0;
        $parts = Part::with('car')->whereIn('id', $request->part_ids)->get();

        foreach ($parts as $part) {
            if ($request->user()->can('update', $part->car)) {
                $part->markAsReplaced();
                $replaced++;
            }
        }

        return response()->json([
            'success' => true,
            'replaced' => $replaced,
        ]);
    }
}
