@extends('layouts.app')
@section('title', $exhibition->title)
@section('content')
<a class="back" href="{{ route('gallery') }}">← Todas as exposições</a>
<section class="page-heading"><span class="badge {{ $exhibition->status }}">{{ $exhibition->status_label }}</span><h1>{{ $exhibition->title }}</h1><p class="intro">{{ $exhibition->description }}</p><p>{{ $artworks->total() }} obras · Clique em uma imagem para ampliar.</p></section>
@if($exhibition->status === 'closed')<div class="notice">A votação foi encerrada. Continue apreciando as obras da exposição.</div>@endif
<div class="artwork-grid">
@forelse($artworks as $artwork)
<article class="artwork-card"><button class="image-button" data-image="{{ Storage::url($artwork->image_path) }}" data-title="{{ $artwork->title }}" aria-label="Ampliar {{ $artwork->title }}"><img loading="lazy" src="{{ Storage::url($artwork->image_path) }}" alt="{{ $artwork->title }}{{ $artwork->artist ? ', por '.$artwork->artist : '' }}"><span>Ampliar ↗</span></button><div class="artwork-info"><span class="eyebrow">OBRA {{ str_pad($artwork->id, 3, '0', STR_PAD_LEFT) }}</span><h2>{{ $artwork->title }}</h2>@if($artwork->artist)<p class="artist">{{ $artwork->artist }}</p>@endif<p>{{ $artwork->description }}</p>@if($exhibition->status === 'open')<button class="button secondary" data-vote="{{ $artwork->id }}" data-title="{{ $artwork->title }}">Votar nesta obra <span>↗</span></button>@endif</div></article>
@empty<div class="empty">Nenhuma obra nesta exposição.</div>@endforelse
</div>{{ $artworks->links('partials.pagination') }}
<dialog id="image-dialog" class="image-dialog"><button type="button" class="close-dialog" aria-label="Fechar imagem">×</button><img id="large-image" alt=""><p id="image-caption"></p></dialog>
@if($exhibition->status === 'open')
<dialog id="vote-dialog"><button type="button" class="close-dialog" aria-label="Fechar votação">×</button><p class="eyebrow">ESCOLHA DO PÚBLICO</p><h2>Confirme seu olhar.</h2><p>Você está votando em <strong id="vote-title"></strong>.</p><form method="post" action="{{ route('gallery.vote', $exhibition) }}">@csrf<input type="hidden" name="artwork_id" id="vote-artwork"><label for="voter-email">Seu e-mail</label><input id="voter-email" name="email" type="email" value="{{ old('email') }}" maxlength="254" required autocomplete="email"><p class="hint">Um voto por e-mail nesta exposição. O voto não pode ser alterado. Armazenamos somente um identificador protegido derivado do e-mail para evitar repetição.</p><label class="checkbox"><input type="checkbox" name="consent" value="1" required> Entendo as regras e autorizo o uso do meu e-mail para controlar a duplicidade do voto.</label><button class="button full">Confirmar voto →</button></form></dialog>
@endif
@endsection
