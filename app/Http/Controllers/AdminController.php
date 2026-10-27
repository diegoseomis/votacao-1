<?php
namespace App\Http\Controllers;
use App\Models\Exhibition;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\{DB, Storage};
use Illuminate\Validation\ValidationException;
class AdminController extends Controller {
    public function index() {
        return view('admin.index', ['exhibitions' => Exhibition::withCount(['artworks', 'votes'])->latest()->paginate(12)]);
    }
    public function store(Request $request) {
        $exhibition = Exhibition::create($request->validate(['title' => 'required|string|max:150', 'description' => 'nullable|string|max:3000']));
        return redirect()->route('admin.show', $exhibition)->with('success', 'Exposição criada. Adicione as obras antes de abrir a votação.');
    }
    public function show(Exhibition $exhibition) {
        return view('admin.show', ['exhibition' => $exhibition, ...$this->results($exhibition)]);
    }
    public function upload(Request $request, Exhibition $exhibition) {
        $data = $request->validate(['title' => 'required|string|max:150', 'artist' => 'nullable|string|max:150', 'description' => 'nullable|string|max:3000', 'image' => 'required|image|mimes:jpg,jpeg,png,webp|max:8192|dimensions:max_width=12000,max_height=12000']);
        $path = $request->file('image')->store('artworks', 'public');
        try {
            DB::transaction(function () use ($exhibition, $data, $path) {
                $locked = Exhibition::whereKey($exhibition->id)->lockForUpdate()->firstOrFail();
                if ($locked->status !== 'draft') {
                    throw ValidationException::withMessages(['image' => 'As obras só podem ser adicionadas durante a preparação.']);
                }
                $locked->artworks()->create([...collect($data)->except('image')->all(), 'artist' => ($data['artist'] ?? ''), 'image_path' => $path]);
            });
        } catch (\Throwable $error) { Storage::disk('public')->delete($path); throw $error; }
        return back()->with('success', 'Obra adicionada à exposição.');
    }
    public function status(Request $request, Exhibition $exhibition) {
        $data = $request->validate(['status' => 'required|in:open,closed']);
        DB::transaction(function () use ($exhibition, $data) {
            $locked = Exhibition::whereKey($exhibition->id)->lockForUpdate()->firstOrFail();
            $valid = ($locked->status === 'draft' && $data['status'] === 'open' && $locked->artworks()->exists()) || ($locked->status === 'open' && $data['status'] === 'closed');
            if (! $valid) {
                throw ValidationException::withMessages(['status' => 'Transição inválida. Adicione obras antes de abrir; exposições encerradas não podem ser reabertas.']);
            }
            $locked->update($data);
        });
        return back()->with('success', 'Situação da votação atualizada.');
    }
    public function pdf(Exhibition $exhibition) {
        $snapshot = DB::transaction(function () use ($exhibition) {
            $locked = Exhibition::whereKey($exhibition->id)->lockForUpdate()->firstOrFail();
            return ['exhibition' => $locked, ...$this->results($locked)];
        });
        return Pdf::loadView('reports.results', [...$snapshot, 'generatedAt' => now()])->setPaper('a4')->download('resultado-exposicao-'.$exhibition->id.'.pdf');
    }
    private function results(Exhibition $exhibition): array {
        $artworks = $exhibition->artworks()->withCount('votes')->orderByDesc('votes_count')->orderBy('id')->get();
        $total = $artworks->sum('votes_count'); $previous = null; $rank = 0;
        foreach ($artworks as $index => $artwork) {
            if ($previous !== $artwork->votes_count) { $rank = $index + 1; }
            $artwork->rank = $rank; $previous = $artwork->votes_count;
        }
        return compact('artworks', 'total');
    }
}
