<!doctype html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Galeria de Arte') · Fundação Bradesco</title>
    <link rel="stylesheet" href="{{ asset('css/gallery.css') }}">
    <script src="{{ asset('js/gallery.js') }}" defer></script>
</head>
<body>
<header class="site-header"><a class="brand" href="{{ route('gallery') }}"><img class="brand-logo" src="{{ asset('images/fundacao-bradesco-white.svg') }}" alt="Fundação Bradesco" width="220" height="34"><span class="brand-label">GALERIA<br>DE ARTE</span></a><nav aria-label="Principal"><a href="{{ route('gallery') }}">Exposições</a>@if(auth()->user()?->is_admin)<a href="{{ route('admin.index') }}">Painel</a><form method="post" action="{{ route('logout') }}">@csrf<button class="text-button">Sair</button></form>@else<a href="{{ route('login') }}">Área do curador ↗</a>@endif</nav></header>
<main id="main">
@if(session('success'))<div class="notice success" role="status">{{ session('success') }}</div>@endif
@if($errors->any())<div class="notice error" role="alert"><strong>Confira as informações:</strong><ul>@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>@endif
@yield('content')
</main>
<footer><a class="brand" href="{{ route('gallery') }}"><img class="brand-logo" src="{{ asset('images/fundacao-bradesco-white.svg') }}" alt="Fundação Bradesco" width="220" height="34"></a><span>Galeria de Arte · Criatividade e educação em exposição.</span><span>Seu olhar valoriza talentos.</span></footer>
</body>
</html>
