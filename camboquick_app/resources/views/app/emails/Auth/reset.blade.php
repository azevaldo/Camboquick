@component('mail::message')
# Olá, {{ $user->name }}
Recebemos um pedido para redefinir sua senha. Se foi você quem solicitou, clique no botão abaixo:
Tens 60 min para redifinir a senha!
@component('mail::button', ['url' => url(route('password.reset', ['token' => $token, 'email' => $user->email], false))])
Redefinir Senha
@endcomponent

Se não foi você quem solicitou isso, ignore este e-mail.

Atenciosamente,
**{{ config('app.name') }}**
Obrigado por usar os nossos serviços!🎉<br>
@endcomponent
