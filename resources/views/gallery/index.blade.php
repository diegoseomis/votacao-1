@extends('layouts.app')
@section('content')
<section class="hero"><div><p class="eyebrow">FUNDAÇÃO BRADESCO · GALERIA DE ARTE</p><h1>Arte que inspira.<br>Educação que <em>transforma.</em></h1><p class="intro">Um encontro entre arte, criatividade e aprendizagem. Conheça as obras da exposição e vote na sua favorita.</p><a class="button" href="#exposicoes">Explorar exposições <span>↓</span></a></div><div class="art-composition" aria-hidden="true"><div class="art-frame"><div class="art-sun"></div><div class="art-hill"></div><div class="art-line"></div></div><span>CRIATIVIDADE E EDUCAÇÃO / FUNDAÇÃO BRADESCO</span></div></section>
<section id="exposicoes"><div class="section-heading"><div><p class="eyebrow">TALENTOS EM DESTAQUE</p><h2>Em exposição</h2></div><span>{{ $exhibitions->total() }} exposições</span></div><div class="exhibition-grid">
@forelse($exhibitions as $exhibition)
<a class="exhibition-card" href="{{ route('gallery.show', $exhibition) }}"><span class="badge {{ $exhibition->status }}">{{ $exhibition->status_label }}</span><span class="exhibition-number">{{ str_pad($exhibition->id, 2, '0', STR_PAD_LEFT) }}</span><h3>{{ $exhibition->title }}</h3><p>{{ Str::limit($exhibition->description, 180) }}</p><div class="card-bottom"><span>{{ $exhibition->artworks_count }} obras</span><span>Visitar exposição ↗</span></div></a>
@empty
<div class="empty"><span class="empty-icon">◯</span><h3>Novos olhares estão chegando.</h3><p>As exposições aparecerão aqui assim que a curadoria abrir a votação.</p></div>
@endforelse
</div>{{ $exhibitions->links('partials.pagination') }}</section>
<section class="steps"><div><span>01 / EXPLORE</span><h3>Deixe o olhar passear.</h3><p>Conheça as obras e os artistas de cada exposição.</p></div><div><span>02 / ESCOLHA</span><h3>Encontre a sua favorita.</h3><p>Amplie as imagens e observe cada detalhe.</p></div><div><span>03 / PARTICIPE</span><h3>Seu voto faz parte.</h3><p>Informe seu nome e vote na sua favorita.</p></div></section>
@endsection
