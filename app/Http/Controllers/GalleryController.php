<?php
namespace App\Http\Controllers;
use App\Models\{Exhibition, Vote};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
class GalleryController extends Controller {
    public function index() {
        return view('gallery.index', ['exhibitions' => Exhibition::whereIn('status', ['open', 'closed'])->withCount('artworks')->latest()->paginate(12)]);
    }
    public function show(Exhibition $exhibition) {
        abort_if($exhibition->status === 'draft', 404);
        return view('gallery.show', ['exhibition' => $exhibition, 'artworks' => $exhibition->artworks()->orderBy('id')->paginate(24)]);
    }
    public function vote(Request $request, Exhibition $exhibition) {
        $data = $request->validate(['artwork_id' => 'required|integer', 'voter_name' => 'required|string|max:255']);
        DB::transaction(function () use ($data, $exhibition) {
            $locked = Exhibition::whereKey($exhibition->id)->lockForUpdate()->firstOrFail();
            if ($locked->status !== 'open') {
                throw ValidationException::withMessages(['vote' => 'Esta exposição não está recebendo votos.']);
            }
            $artwork = $locked->artworks()->findOrFail($data['artwork_id']);
            Vote::create(['exhibition_id' => $locked->id, 'artwork_id' => $artwork->id, 'voter_name' => trim($data['voter_name'])]);
        });
        return back()->with('success', 'Seu voto foi registrado. Obrigado por participar!');
    }
}
