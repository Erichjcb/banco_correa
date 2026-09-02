<?php

class BancoController {

    private $modelo;

    public function __construct() {
        $this->modelo = new UsuarioModel();
    }

        public function login() {

        $user = isset($_GET['u']) ? $_GET['u'] : '';
        $pass = isset($_GET['p']) ? $_GET['p'] : '';

        if ($user == '' || $pass == '') {
            echo "ERROR: Debe ingresar usuario y contraseña.";
            return;
        }

        $usuarioLogueado = $this->modelo->verificarLogin($user, $pass);

        if ($usuarioLogueado) {

            echo "LOGIN EXITOSO.<br>";
            echo "Bienvenido, " . $usuarioLogueado['usuario'] . "<br>";
            echo "Saldo actual: S/ " . number_format($usuarioLogueado['SALDO'], 2);

        } else {

            echo "ERROR: Credenciales incorrectas.";
        }
    }


    public function retiro() {

        $montoRetiro = isset($_GET['monto']) ? $_GET['monto'] : 0;

        if ($montoRetiro <= 0) {

            echo "ERROR: El monto debe ser mayor que 0.";
            return;
        }

      
        $idUsuario = 2;


        $usuario = $this->modelo->obtenerUsuario($idUsuario);

        if (!$usuario) {

            echo "ERROR: Usuario no encontrado.";
            return;
        }

        $saldoActual = $usuario['SALDO'];


        if ($montoRetiro <= $saldoActual) {

            $nuevoSaldo = $saldoActual - $montoRetiro;

            $actualizado = $this->modelo->actualizarSaldo(
                $idUsuario,
                $nuevoSaldo
            );

            if ($actualizado) {

                echo "RETIRO APROBADO.<br>";
                echo "Has retirado: S/ " . number_format($montoRetiro, 2) . "<br>";
                echo "Saldo anterior: S/ " . number_format($saldoActual, 2) . "<br>";
                echo "Tu nuevo saldo es: S/ " . number_format($nuevoSaldo, 2);

            } else {

                echo "ERROR: No se pudo actualizar el saldo.";
            }

        } else {

            echo "ERROR: Fondos insuficientes.<br>";
            echo "Saldo disponible: S/ " . number_format($saldoActual, 2) . "<br>";
            echo "Monto solicitado: S/ " . number_format($montoRetiro, 2);
        }
    }
}
?>
