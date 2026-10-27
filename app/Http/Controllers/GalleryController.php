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
        $data = $request->validate(['artwork_id' => 'required|integer', 'email' => 'required|email|max:254', 'consent' => 'accepted']);
        DB::transaction(function () use ($data, $exhibition) {
            $locked = Exhibition::whereKey($exhibition->id)->lockForUpdate()->firstOrFail();
            if ($locked->status !== 'open') {
                throw ValidationException::withMessages(['vote' => 'Esta exposição não está recebendo votos.']);
            }
            $artwork = $locked->artworks()->findOrFail($data['artwork_id']);
            $hash = hash_hmac('sha256', mb_strtolower(trim($data['email'])), config('app.key'));
            if (Vote::where('exhibition_id', $locked->id)->where('voter_hash', $hash)->exists()) {
                throw ValidationException::withMessages(['email' => 'Este e-mail já votou nesta exposição.']);
            }
            Vote::create(['exhibition_id' => $locked->id, 'artwork_id' => $artwork->id, 'voter_hash' => $hash]);
        });
        return back()->with('success', 'Seu voto foi registrado. Obrigado por participar!');
    }
}
