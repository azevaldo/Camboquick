<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Support\Facades\Log;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * The list of the inputs that are never flashed to the session on validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     */
    public function register(): void
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }

    public function render($request, Throwable $exception)
    {
        // Loga o erro para análise posterior
        Log::error("Erro capturado pelo Handler: " . $exception->getMessage());

        // Lista de padrões de erros que podem gerar loop
        $errosCriticos = [
            'Trying to get property',
            'Call to a member function',
            'Undefined variable',
            'Undefined index',
            'SQLSTATE['
        ];

        foreach ($errosCriticos as $critico){

            if (str_contains($exception->getMessage(), $critico)) {
                // Se for requisição AJAX/JSON, retorna resposta JSON
                if ($request->expectsJson()) {
                    return response()->json([
                        'error' => 'Erro crítico. Contate o suporte.',
                        'codigo' => now()->timestamp
                    ], 500);
                }

                // Se não for AJAX, redireciona para rota segura
                return redirect()
                    ->route('erro.critico') // Troque pela rota segura do seu sistema
                    ->with('error', 'Erro crítico. Contate o suporte. Código: ' . now()->timestamp);
            }
        }

        // Caso não seja crítico, continua o fluxo padrão
        return parent::render($request, $exception);
    }
}
