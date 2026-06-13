<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Credenciales de Acceso</title>
</head>
<body style="font-family: Arial, sans-serif; color: #333; line-height: 1.6; padding: 20px;">
    <div style="max-width: 600px; margin: 0 auto; border: 1px solid #ddd; border-radius: 8px; overflow: hidden;">
        <div style="background-color: #1a56db; color: white; padding: 20px; text-align: center;">
            <h2 style="margin: 0;">Universidad Autónoma Gabriel René Moreno</h2>
            <p style="margin: 5px 0 0 0; font-size: 14px;">Sistema Integrado de Admisiones CUP</p>
        </div>
        
        <div style="padding: 20px;">
            <p>Estimado/a <strong>{{ $docente->nombre }}</strong>,</p>
            <p>Usted ha sido registrado(a) exitosamente como docente en nuestra plataforma académica.</p>
            
            <div style="background-color: #f3f4f6; padding: 15px; border-radius: 5px; margin: 20px 0;">
                <p style="margin-top: 0;"><strong>Sus credenciales de acceso son:</strong></p>
                <ul style="margin-bottom: 0;">
                    <li><strong>Usuario (Correo):</strong> {{ $docente->user->email }}</li>
                    <li><strong>Contraseña temporal:</strong> {{ $password }}</li>
                </ul>
            </div>
            
            <p>Por motivos de seguridad, le recomendamos cambiar su contraseña al iniciar sesión por primera vez en el sistema.</p>
            <br>
            <p>Atentamente,<br><strong>Jefatura de Registros y Admisiones</strong></p>
        </div>
    </div>
</body>
</html>