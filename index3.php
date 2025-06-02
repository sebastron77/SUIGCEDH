<link rel="icon" href="medios/favicon16.ico" type="image/ico">
<?php
ob_start();
require_once('includes/load.php');
if ($session->isUserLoggedIn(true)) {
    redirect('home.php', false);
}
?>

<!-- Fuente limpia y moderna -->
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet">

<style>
    body {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
        font-family: 'Inter', sans-serif;
        height: 100vh;
        display: flex;
        align-items: center;
        justify-content: center;
        background: url("medios/banner pagina_page-0007.jpg") no-repeat center center/cover;
        position: relative;
        overflow: hidden;
    }

    /* Capa oscura */
    body::before {
        content: "";
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        /* background: rgba(0, 0, 0, 0.5); */
        /* backdrop-filter: blur(3px); */
        z-index: 0;
    }

    form {
        position: relative;
        z-index: 1;
        background: #ffffff;
        border-radius: 18px;
        padding: 45px 35px;
        width: 100%;
        max-width: 400px;
        box-shadow: 0 12px 45px rgba(0, 0, 0, 0.25);
        animation: slideUp 0.8s ease-out;
        transform: translateY(0);
        transition: transform 0.4s ease, box-shadow 0.3s ease;
    }

    form:hover {
        transform: translateY(-5px);
        box-shadow: 0 16px 60px rgba(0, 0, 0, 0.3);
    }

    @keyframes slideUp {
        from {
            opacity: 0;
            transform: translateY(40px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .logo-titulo {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 12px;
        margin-bottom: 25px;
    }

    .logo-titulo img {
        width: 50px;
    }

    .logo-titulo span {
        font-size: 26px;
        font-weight: 600;
        color: #370494;
    }

    h1 {
        text-align: center;
        font-size: 20px;
        margin-bottom: 18px;
        color: #222;
    }

    fieldset {
        border: none;
        display: flex;
        flex-direction: column;
        margin-bottom: 18px;
    }

    label {
        margin-bottom: 6px;
        font-size: 14px;
        color: #333;
    }

    input {
        padding: 12px;
        border-radius: 10px;
        border: 1px solid #ccc;
        font-size: 14px;
        transition: border 0.3s, box-shadow 0.3s;
    }

    input:focus {
        outline: none;
        border: 1px solid #370494;
        box-shadow: 0 0 5px rgba(55, 4, 148, 0.5);
    }

    button {
        background-color: #370494;
        color: white;
        border: none;
        border-radius: 10px;
        padding: 12px;
        font-size: 16px;
        font-weight: 600;
        cursor: pointer;
        transition: background 0.3s, transform 0.2s;
    }

    button:hover {
        background-color: #2d0378;
        transform: translateY(-2px);
    }

    .input-wrap {
        position: relative;
        margin-bottom: 30px;
    }

    .input-wrap input {
        width: 100%;
        padding: 12px;
        border: 2px solid #ccc;
        border-radius: 6px;
        font-size: 16px;
        background: #fff;
        transition: all 0.2s ease;
    }

    .input-wrap input:focus {
        border-color: #370494;
        animation: pop 0.25s ease;
    }

    .input-wrap label {
        position: absolute;
        left: 12px;
        top: 12px;
        background: #fff;
        padding: 0 4px;
        font-size: 14px;
        color: #666;
        transition: 0.2s ease all;
        pointer-events: none;
    }

    .input-wrap input:focus+label,
    .input-wrap input:not(:placeholder-shown)+label {
        top: -10px;
        font-size: 12px;
        color: #370494;
    }

    @keyframes pop {
        0% {
            transform: scale(1);
        }

        50% {
            transform: scale(1.03);
        }

        100% {
            transform: scale(1);
        }
    }

    @keyframes shake {

        0%,
        100% {
            transform: translateX(0);
        }

        20%,
        60% {
            transform: translateX(-6px);
        }

        40%,
        80% {
            transform: translateX(6px);
        }
    }

    .shake {
        animation: shake 0.4s;
        border-color: red !important;
    }
</style>

<?php echo display_msg($msg); ?>

<form id="loginForm">
    <div class="logo-titulo">
        <img src="medios/moremorado.png" width="60" alt="CEDH" />
        <span>SUIGCEDH</span>
    </div>
    <div class="input-wrap">
        <input type="text" name="username" id="username" required placeholder=" ">
        <label for="username">Usuario</label>
    </div>

    <div class="input-wrap">
        <input type="password" name="password" id="password" required placeholder=" ">
        <label for="password">Contraseña</label>
    </div>

    <button type="submit">Entrar</button>
</form>


<script>
    const form = document.getElementById('loginForm');

    form.addEventListener('submit', function(e) {
        let hasError = false;
        form.querySelectorAll('input').forEach(input => {
            if (!input.value.trim()) {
                input.classList.add('shake');
                hasError = true;
                setTimeout(() => input.classList.remove('shake'), 400);
            }
        });

        if (hasError) {
            e.preventDefault(); // evita enviar el formulario si hay errores
        }
    });
</script>