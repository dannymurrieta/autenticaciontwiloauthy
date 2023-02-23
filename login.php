<?php
    include './templates/header.php';

    if ($userController->isUserLoggedIn()) {
        header('Location: panel.php');
    }

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css"
        integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">

</head>

<body>

    <?php include './templates/nav.php' ?>

    <div class="container mt-5">
        <div class="row justify-content-md-center">
            <div class="col col-md-6">
                <h3>Log In</h3><hr />
                <form id="login-form">
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" class="form-control" id="email">
                    </div>
                    <div class="form-group">
                        <label for="password">Password</label>
                        <input type="password" class="form-control" id="password">
                    </div>
                    <button type="submit" class="btn btn-primary">Log in</button>
                </form>
                <div class="alert alert-damger mt-4 d-none" id="error-message"></div>
            </div>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/axios/1.3.3/axios.min.js"></script>
    <script>
        document.getElementById('login-form').onsubmit = (e) => {
            e.preventDefault();

            const errorMessage = document.getElementById('error-message');
            errorMessage.classList.add('d-none');
            const email = document.getElementById('email').value;
            const password = document.getElementById('password').value;


            if (!email || !password){
                return;
            }

            axios.post('api/login.php', { email: email, password: password })
                .then(res => {
                    if(res.data.secondfactor){
                        window.location = 'login-secondfactor.php';
                    }else{
                        window.location = 'panel.php';
                    }

                })
                .catch(err => {
                    errorMessage.innerText = err.response.data;
                    errorMessage.classList.remove('d-none');
                });
        }
    </script>
</body>

</html>