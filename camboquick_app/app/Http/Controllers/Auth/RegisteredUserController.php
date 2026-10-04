<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Papel;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Role;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    public function gerarSlug($para){
        // Gerar um slug único
   $slugBase = Str::slug($para);
   $slug = $slugBase;
   $contador = 1;
   while (User::where('slug', $slug)->exists()) {
       $slug = $slugBase . '-' . $contador;
       $contador++;
   }
   return $slug;
   }
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:'.User::class],
            'sexo'=>['required','string'],
            'papel'=>['required'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $slug=$this->gerarSlug(  $request->name);
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'sexo'=>$request->sexo,
            'slug'=>$slug,
            'password' => Hash::make($request->password),
        ]);

        $user->syncRoles($request->papel);
        event(new Registered($user));

        

        return redirect()->back()->with('success','Usuario Cadastrado Com Sucesso');
    }
}
