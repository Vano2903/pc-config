<?php
session_start();
?>

<html>

<head>
  <title>Chi siamo</title>
  <link rel="stylesheet" href="./css/default.css">

  <style type="text/css">
    .box {
      width: 100%;
      display: inline-flex;
      flex-wrap: nowrap;
      justify-content: space-evenly;
      background-color: black;
      font-weight: bold;
    }

    li {
      display: inline;
      margin: 6%;
    }

    ul {
      width: 100%;
      margin-top: 6%;
    }

    img {
      width: 12%;
      height: 12%;
    }

    a {
      text-decoration: none;
    }

    .menu {
      color: red;
    }

    .menu:hover {
      color: orangered;
    }

    #selezionato {
      color: orange;
    }

    .p {
      text-align: center;
    }
  </style>
</head>

<body>
  <div class="box">
    <img src="immagini/LOGO.png">
    <?php
      include "session.php";
      include "navbar.php";
    ?>
  </div>
  <img src="immagini/kepp-calm.jpg" id="destra">
  <img src="immagini/kepp-calm.jpg" id="sinistra">
  <div class="p">
    <h1>Chi siamo</h1>
    <p>Siamo una nuova azienda, nata nel 2023 che punta a facilitare l'approccio al PC da parte delle persone nuove nel
      settore.</p>
    <h2>Il nostro obbiettivo</h2>
    <p>Il nostro obbettivo è quello di rendere facile e a portata di tutti la scelta e di conseguenza l'acquisto di un
      pc personalizzato vista la grande convenienza e maggiore performance nei confronti di pc prefabbricati.</p>
    <h2>Dove siamo</h2>
    <iframe
      src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d2787.2023413231964!2d9.722888515758621!3d45.686911226575624!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x47815082540ee1e1%3A0x85a589ac0c48a38e!2sVia%20G.%20Ambiveri%2C%2024%2C%2024068%20Seriate%20BG!5e0!3m2!1sit!2sit!4v1675874190502!5m2!1sit!2sit"
      width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy"
      referrerpolicy="no-referrer-when-downgrade"></iframe>
    <h3>Contattaci<h3>
        <p>Num telefono:</p>
        <p>3423060859</p>
        <p>Email</p>
        <p>riccardo.minghini02@majorana.org</p>
  </div>
</body>

</html>