@extends('app.layouts.app')

@section('content')
 
 
<div class="container">
 
<div class="container mt-4 p-4 mb-4" style="background-color: white; border-radius: 8px;">
 


    <div class="row">
        <div class="col-12 text-center mb-4">
            <h2>Minha Conta</h2>
        </div>

        {{-- Formulário de edição de nome --}}
     

       
        
        <div class="col-md-12 mb-4">
            <h3>Informações do usuario</h3>
            <h4>Papel : {{Auth::user()->getRoleNames()->first()}}</h4>
             
            <form id="send-verification" method="post" action="{{ route('verification.send') }}">
                @csrf
            </form>
            <form method="post" action="{{ route('profile.update') }}">
                @csrf
                @method('patch')
        
                <div class="row">
                    <div class="col-md-12">
                        <label for="name" class="form-label">Nome</label>
                        <input type="text" class="form-control" id="name" name="name" 
                               value="{{ old('name', $user->name) }}" required autofocus autocomplete="name" minlength="4">
                        @error('name')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
        
                    <div class="col-md-6">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="email" name="email" 
                               value="{{ old('email', $user->email) }}" required autocomplete="username">
                        @error('email')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
        
                     <!-- Campo de Sexo -->
            <div class="mb-3">
                <label for="sexo" class="form-label">Sexo</label>
                <div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" id="sexo_masculino" name="sexo" value="masculino" 
                               {{ old('sexo', $user->sexo) == 'Masculino' ? 'checked' : '' }} required>
                        <label class="form-check-label" for="sexo_masculino">Masculino</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" id="sexo_feminino" name="sexo" value="Feminino" 
                               {{ old('sexo', $user->sexo) == 'Feminino' ? 'checked' : '' }}>
                        <label class="form-check-label" for="sexo_feminino">Feminino</label>
                    </div>
                </div>
                @error('sexo')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>



                
                    
                </div>
        
                
                <!-- Botão -->
        <div class="d-flex justify-content-between">
            <button type="submit" class="btn mt-3" style="background-color: rgb(255, 167, 36); color: white;">Atualizar</button>
            <!-- Traduzido: Salvar -->
            @if (session('status') === 'profile-updated')
            <span class="text-success">{{ __('Salvo.') }}</span>
            <!-- Traduzido: Salvo -->
        @endif
        </div>
            </form>
        </div>
        
<hr>
<style>
    .form-control:focus {
        border-color:  rgb(255, 167, 36);
        box-shadow: 0 0 5px  rgb(255, 167, 36);
    }
</style>

<div class="col-md-12 mb-4">
    <h3>Atualização de Passowrd</h3>
    <form method="post" action="{{ route('password.update') }}">
        @csrf
        @method('put')

        <div class="row">
            <!-- Senha Atual -->
            <div class="col-md-4">
                <label for="current_password" class="form-label">Senha Atual</label>
                <input type="password" class="form-control" id="current_password" name="current_password" autocomplete="current-password" required>
                @error('current_password', 'updatePassword')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>

            <!-- Nova Senha -->
            <div class="col-md-4">
                <label for="password" class="form-label">Nova Senha</label>
                <input type="password" class="form-control" id="password" name="password" autocomplete="new-password" required>
                @error('password', 'updatePassword')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>

            <!-- Confirmação de Senha -->
            <div class="col-md-4">
                <label for="password_confirmation" class="form-label">Confirmar Nova Senha</label>
                <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" autocomplete="new-password" required>
                @error('password_confirmation', 'updatePassword')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <div class="d-flex justify-content-between mt-3">
            <button type="submit" class="btn" style="background-color: rgb(255, 167, 36); color: white;">Atualizar Senha</button>

            @if (session('status') === 'password-updated')
                <span class="text-success">Senha atualizada com sucesso!</span>
            @endif
        </div>
    </form>
</div>

    </div>
</div>
        
@endsection

