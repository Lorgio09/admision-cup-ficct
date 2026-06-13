<?php

namespace App\Mail;

use App\Models\Docente; // 👈 1. IMPORTAMOS EL MODELO DOCENTE
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class CredencialesDocenteMail extends Mailable
{
    use Queueable, SerializesModels;

    public Docente $docente; 
    public string $password;

    public function __construct(Docente $docente, string $password)
    {
        $this->docente = $docente;
        $this->password = $password;
    }

    public function build()
    {
        return $this->subject('Bienvenido al Sistema de Admisiones CUP - UAGRM')
                    ->view('emails.credenciales_docente');
    }
}