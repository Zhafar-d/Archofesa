<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Room;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class KamarController extends Controller
{
    public function index()
    {
        $rooms = Room::latest()->paginate(15);

        return view('admin.kamar.index', compact('rooms'));
    }

    public function create()
    {
        return view('admin.kamar.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'room_code' => 'required|string|max:255|unique:rooms,room_code',
            'size' => 'required|string|max:255',
            'price_monthly' => 'required|numeric|min:0',
            'status' => 'required|string',
            'description' => 'nullable|string',
            'image_url' => 'nullable|url',
            'image_files' => 'nullable|array|max:7',
            'image_files.*' => 'image|mimes:jpg,jpeg,png,webp|max:5120',
        ]);

        $imageUrls = [];
        if ($request->hasFile('image_files')) {
            foreach ($request->file('image_files') as $file) {
                $path = $file->store('rooms', 'public');
                $imageUrls[] = Storage::url($path);
            }
        }

        if (! empty($imageUrls)) {
            $validated['image_urls'] = $imageUrls;
            $validated['image_url'] = $imageUrls[0];
        } elseif ($request->filled('image_url')) {
            $validated['image_url'] = $validated['image_url'];
        }

        Room::create($validated);

        return redirect()->route('admin.kamar.index')->with('success', 'Kamar berhasil dibuat.');
    }

    public function edit(Room $room)
    {
        return view('admin.kamar.edit', compact('room'));
    }

    public function update(Request $request, Room $room)
    {
        $validated = $request->validate([
            'room_code' => 'required|string|max:255|unique:rooms,room_code,' . $room->id,
            'size' => 'required|string|max:255',
            'price_monthly' => 'required|numeric|min:0',
            'status' => 'required|string',
            'description' => 'nullable|string',
            'image_url' => 'nullable|url',
            'image_files' => 'nullable|array|max:7',
            'image_files.*' => 'image|mimes:jpg,jpeg,png,webp|max:5120',
        ]);

        $data = [
            'room_code' => $validated['room_code'],
            'size' => $validated['size'],
            'price_monthly' => $validated['price_monthly'],
            'status' => $validated['status'],
            'description' => $validated['description'] ?? null,
        ];

        if ($request->hasFile('image_files')) {
            if (! empty($room->image_urls) && is_array($room->image_urls)) {
                foreach ($room->image_urls as $oldUrl) {
                    if (str_starts_with($oldUrl, Storage::url(''))) {
                        $oldPath = str_replace('/storage/', '', $oldUrl);
                        Storage::disk('public')->delete($oldPath);
                    }
                }
            }

            $imageUrls = [];
            foreach ($request->file('image_files') as $file) {
                $path = $file->store('rooms', 'public');
                $imageUrls[] = Storage::url($path);
            }

            $data['image_urls'] = $imageUrls;
            $data['image_url'] = $imageUrls[0];
        } elseif ($request->filled('image_url')) {
            $data['image_url'] = $validated['image_url'];
        }

        $room->update($data);

        return redirect()->route('admin.kamar.index')->with('success', 'Kamar berhasil diperbarui.');
    }

    public function destroy(Room $room)
    {
        $room->delete();

        return redirect()->route('admin.kamar.index')->with('success', 'Kamar berhasil dihapus.');
    }
}
