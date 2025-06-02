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

    .input-effect {
        position: relative;
        margin-bottom: 25px;
    }

    .input-effect input {
        border: none;
        border-bottom: 2px solid #ccc;
        padding: 10px;
        width: 100%;
        background: transparent;
        color: #000;
        font-size: 16px;
        outline: none;
        transition: border-color 0.4s ease;
    }

    .input-effect span.focus-border {
        position: absolute;
        bottom: 0;
        left: 50%;
        width: 0;
        height: 2px;
        background-color: #370494;
        transition: 0.4s;
        transform: translateX(-50%);
    }

    .input-effect input:focus~span.focus-border {
        width: 100%;
    }

    @keyframes shake {
        0% {
            transform: translateX(0);
        }

        25% {
            transform: translateX(-5px);
        }

        50% {
            transform: translateX(5px);
        }

        75% {
            transform: translateX(-5px);
        }

        100% {
            transform: translateX(0);
        }
    }

    .shake {
        animation: shake 0.3s;
    }
</style>

<?php echo display_msg($msg); ?>

<form id="loginForm">
    <div class="input-effect">
        <span class="focus-border">Usuario</span>
        <input type="text" name="username" required placeholder="Usuario">
    </div>

    <div class="input-effect">
        <input type="password" name="password" id="password" required placeholder=" ">
        <label for="password">Contraseña</label>
    </div>

    <button type="submit">Entrar</button>
</form>


<script>
    // JS que verifica campos vacíos y aplica vibración
    document.querySelector("form").addEventListener("submit", function(e) {
        let inputs = this.querySelectorAll("input");
        let valid = true;

        inputs.forEach(input => {
            if (input.value.trim() === "") {
                input.classList.add("shake");
                valid = false;
                setTimeout(() => input.classList.remove("shake"), 300);
            }
        });

        if (!valid) e.preventDefault();
    });
</script>