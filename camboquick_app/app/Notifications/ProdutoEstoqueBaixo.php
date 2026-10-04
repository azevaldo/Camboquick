<?php

// app/Notifications/ProdutoEstoqueBaixo.php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\DatabaseMessage;

class ProdutoEstoqueBaixo extends Notification implements ShouldQueue
{
    use Queueable;

    protected $produto;

    public function __construct($produto)
    {
        $this->produto = $produto;
    }

    public function via($notifiable)
    {
        return ['database']; // você pode adicionar 'mail' se quiser também
    }

    public function toDatabase($notifiable)
    {
        return [
            'mensagem' => "O produto '{$this->produto->nome}' atingiu o estoque mínimo.",
            'produto_id' => $this->produto->id,
        ];
    }
}
