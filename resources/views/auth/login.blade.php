@extends('layouts.app')
@section('title', 'Acesso à curadoria')
@section('content')
<section class="login-panel"><p class="eyebrow">ÁREA DO CURADOR</p><h1>Cuide de cada<br><em>novo olhar.</em></h1><p>Entre para organizar exposições e acompanhar a votação.</p><form method="post" action="{{ route('login') }}">@csrf<label for="email">E-mail</label><input id="email" name="email" type="email" value="{{ old('email') }}" required autocomplete="username"><label for="password">Senha</label><input id="password" name="password" type="password" required autocomplete="current-password"><button class="button full">Entrar no painel →</button></form></section>
@endsection
