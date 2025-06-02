  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@500&display=swap" rel="stylesheet">
<style>
    * {
        box-sizing: border-box;
        padding: 0;
        margin: 0;
        font-size: 15px;
    }

    body {
        display: flex;
        align-items: center;
        justify-content: center;
        font-family: "Montserrat", sans-serif;
        color: #000;
        background-image: url("medios/banner pagina_page-0007.jpg");
        height: 100%;
        width: 100%;
        background-size: cover;
        background-repeat: no-repeat;
        background-position: center center;
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
    }

    form {
        font-family: "Montserrat", sans-serif;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        padding: 30px 20px 35px 20px;
        border-radius: 10px;
        display: flex;
        flex-direction: column;
        gap: 2rem;
        background: #FFF;
        width: 20%;
        height: 30%;
    }

    input {
        font-family: "Montserrat", sans-serif;
        padding: 0.5rem 1em;
        border: 0;
        background-color: transparent;
        color: #000;
        font-size: 1.2rem;
        width: 100%;
    }

    input:focus {
        outline: 0;
    }

    fieldset {
        font-family: "Montserrat", sans-serif;
        padding: 0;
        border: 2px solid #fff;
        border-radius: 4px;
    }
    fieldset:focus-within {
        border-color:rgb(129, 129, 129);
    }
    legend{
        font-family: "Montserrat", sans-serif;
        margin-left: 0.75em;
        padding-inline: 0.5em;
        font-size: 1.25em;
    }
    
</style>
<form action="">
    <fieldset>
        <legend>Usuario</legend>
        <input type="text">
    </fieldset>
    <fieldset>
        <legend>Contraseña</legend>
        <input type="password" name="" id="">
    </fieldset>
</form>