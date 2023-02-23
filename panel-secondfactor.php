<?php
use App\Controllers\UserController;
    include './templates/header.php';

    if (!$userController->isUserLoggedIn()) {
        header('Location: login.php');
    }

    //Segundo factor
    $user = $userController->getUser();

    $hasTwoFactorActive = true;

    if($user['two_factor_key'] === null){
        $hasTwoFactorActive = false;
        $g = new \Sonata\GoogleAuthenticator\GoogleAuthenticator();
        $secret = $g->generateSecret();
        $qrCode = \Sonata\GoogleAuthenticator\GoogleQrUrl::generate($user['name'], $secret, "Kuroki");
    }

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">

</head>
<body>

    <?php include './templates/nav.php' ?>

    <?php if (!$hasTwoFactorActive): ?>
        <div class="container mt-5">
        <h3>Activar Doble Autenticación</h3><hr />
        <p>1. Para activar el segundo factor de autenticación, instale Twilo Authy Aunthenticator en su telefono y escanee el Codigo QR.</p>
        <img src="<?=$qrCode ?>" alt="CodigoQR">
        <img src="authy.png" alt="" width="180px">

        <p class="mt-4">2. Escriba el codigo generado por Twilo Authy Aunthenticator y presione activar doble factor</p>
        <div class="row">
        <div class="col-md-4">
            <form id="activate-second-factor">
                    <div class="form-group">
                        <label for="code">Codigo</label>
                        <input type="text" class="form-control" id="code">
                    </div>
                    <button type="submit" class="btn btn-primary">Activar Doble Factor</button>
            </form>
            <div class="alert alert-damger mt-4 d-none" id="error-message"></div>
        </div>
    </div>
    </div>

    <?php else: ?>
        <div class="container mt-5">
            <h3>Desactivar Doble Autenticación</h3><hr />
            <button type="button" class="btn btn-primary" id="desactivate-second-factor">Desactivar Doble Factor</button>
        </div>
    <?php endif; ?>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/axios/1.3.3/axios.min.js"></script>
    <?php if (!$hasTwoFactorActive): ?>
    <script>
        document.getElementById('activate-second-factor').onsubmit = (e) => {
            e.preventDefault();

            const errorMessage = document.getElementById('error-message');
            errorMessage.classList.add('d-none');
            const code = document.getElementById('code').value;
            const secret = '<?= $secret ?>';


            if (!code || !secret){
                return;
            }

            axios.post('api/activatesecondfactor.php', { code: code, secret: secret })
                .then(res => {
                    console.log(res);
                    window.location = 'panel-secondfactor.php';

                })
                .catch(err => {
                    errorMessage.innerText = err.response.data;
                    errorMessage.classList.remove('d-none');
                });
        }
    </script>
    <?php else: ?>
        <script>
            document.getElementById('desactivate-second-factor').onclick = (e) =>{
                e.preventDefault();
                axios.post('api/desactivatesecondfactor.php')
                    .then(res => {
                        window.location = 'panel-secondfactor.php';
                    });
            }
        </script>
    <?php endif; ?>
    

</body>
</html>