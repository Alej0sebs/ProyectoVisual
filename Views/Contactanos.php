<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8" />
  <title>Contáctanos - FISEI</title>

  <link rel="stylesheet" href="css/nav.css" />

  <style>
    :root {
      --rojo-uta: #a50000;
      --rojo-uta-oscuro: #7a0000;
      --rojo-uta-claro: #fbeaea;
    }

    * {
      box-sizing: border-box;
    }

    body {
      margin: 0;
      font-family: 'Segoe UI', Arial, sans-serif;
      background: linear-gradient(180deg, #ffe9e9 0%, #ffdada 100%);
      min-height: 100vh;
      display: flex;
      flex-direction: column;
    }

    header img {
      width: 100%;
      display: block;
    }

    main {
      flex: 1;
      padding: 2rem 1rem;
    }

    .container {
      max-width: 1100px;
      margin: auto;
    }

    .titulo {
      text-align: center;
      margin-bottom: 2rem;
    }

    .titulo h1 {
      color: var(--rojo-uta);
      margin: 0;
      font-size: 2.2rem;
    }

    .titulo p {
      color: #666;
      font-size: 0.95rem;
    }

    .badge {
      display: inline-block;
      background: var(--rojo-uta-claro);
      color: var(--rojo-uta);
      padding: 0.3rem 0.9rem;
      border-radius: 999px;
      font-size: 0.85rem;
      margin-top: 0.5rem;
    }

    .grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
      gap: 1.2rem;
    }

    .card {
      background: #fff;
      padding: 1rem 1.2rem;
      border-radius: 14px;
      border: 1px solid rgba(165, 0, 0, 0.15);
      box-shadow: 0 8px 25px rgba(0, 0, 0, 0.06);
    }

    .nombre {
      font-weight: 700;
      color: #333;
      font-size: 0.95rem;
    }

    .rol {
      font-size: 0.85rem;
      color: #777;
      margin-top: 4px;
    }

    .badge-miembro {
      display: inline-block;
      margin-top: 0.5rem;
      background: var(--rojo-uta);
      color: white;
      padding: 0.2rem 0.7rem;
      border-radius: 999px;
      font-size: 0.78rem;
    }

    footer {
      background: var(--rojo-uta);
      color: white;
      text-align: center;
      padding: 0.9rem;
      font-size: 0.95rem;
      margin-top: auto;
    }
  </style>
</head>

<body>
  <header>
    <img src="imagenes/descarga.png" alt="UTA" />
  </header>

  <?php include 'nav.php'; ?>

  <main>
    <div class="container">
      <div class="titulo">
        <h1>Equipo de Desarrollo</h1>
        <p>Sistema de Registro de Estudiantes</p>
        <span class="badge">UTA · FISEI · Ingeniería en Software</span>
      </div>

      <div class="grid">

        <div class="card">
          <div class="nombre">Sarco Sailema Viviana Maribel</div>
          <div class="rol">Estudiante FISEI</div>
          <span class="badge-miembro">Documentación</span>
        </div>

        <div class="card">
          <div class="nombre">Mariño Lescano Jaim Adiel</div>
          <div class="rol">Estudiante FISEI</div>
          <span class="badge-miembro">Back-end</span>
        </div>

        <div class="card">
          <div class="nombre">Bejarano Masabanda Carlos Fabian</div>
          <div class="rol">Estudiante FISEI</div>
          <span class="badge-miembro">Base de Datos</span>
        </div>

        <div class="card">
          <div class="nombre">Reyes Martinez Alex Jonathan</div>
          <div class="rol">Estudiante FISEI</div>
          <span class="badge-miembro">Interfaz</span>
        </div>

        <div class="card">
          <div class="nombre">Guatemal Avilez Bryan Stalin</div>
          <div class="rol">Estudiante FISEI</div>
          <span class="badge-miembro">Soporte</span>
        </div>

        <div class="card">
          <div class="nombre">Guachi Aucapiña Alex Fabricio</div>
          <div class="rol">Estudiante FISEI</div>
          <span class="badge-miembro">Análisis</span>
        </div>

        <div class="card">
          <div class="nombre">Rivera Claudio Alejandro Sebastian</div>
          <div class="rol">Estudiante FISEI</div>
          <span class="badge-miembro">Coordinador</span>
        </div>

      </div>
    </div>
  </main>

  <footer>
    <p>© 2025 Universidad Técnica de Ambato · FISEI</p>
  </footer>
</body>
</html>
