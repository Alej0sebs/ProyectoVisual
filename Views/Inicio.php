<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Universidad Técnica de Ambato</title>
  <link rel="stylesheet" href="css/nav.css">
  <link rel="stylesheet" href="css/inicio.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
</head>

<body>
  <header>
    <div class="logo-container">
      <img src="imagenes/descarga.png" alt="Logo Universidad Técnica de Ambato" width="90%">
    </div>
  </header>

  <?php include 'nav.php'; ?>


  <main>
    <!-- Hero Section -->
    <section class="hero-section">
      <div class="hero-content">
        <h1 class="hero-title">Bienvenidos a la Universidad Técnica de Ambato</h1>
        <p class="hero-subtitle">Excelencia académica, innovación y compromiso con el desarrollo del Ecuador</p>
        <div class="hero-stats">
          <div class="stat-item">
            <i class="bi bi-calendar-event"></i>
            <div class="stat-number">55+</div>
            <div class="stat-label">Años de Historia</div>
          </div>
          <div class="stat-item">
            <i class="bi bi-people"></i>
            <div class="stat-number">15,000+</div>
            <div class="stat-label">Estudiantes</div>
          </div>
          <div class="stat-item">
            <i class="bi bi-award"></i>
            <div class="stat-number">100+</div>
            <div class="stat-label">Carreras</div>
          </div>
        </div>
      </div>
    </section>

    <!-- Misión y Visión Cards -->
    <section class="cards-section">
      <div class="card-modern">
        <div class="card-icon mission-icon">
          <i class="bi bi-bullseye"></i>
        </div>
        <h2>Misión</h2>
        <p>Formar profesionales líderes competentes, con visión humanista y pensamiento crítico a través de la docencia, la investigación y la vinculación, que apliquen, promuevan y difundan el conocimiento respondiendo a las necesidades del país.</p>
      </div>

      <div class="card-modern">
        <div class="card-icon vision-icon">
          <i class="bi bi-eye"></i>
        </div>
        <h2>Visión</h2>
        <p>La Universidad Técnica de Ambato por sus niveles de excelencia se constituirá como un centro de formación superior con liderazgo y proyección nacional e internacional.</p>
      </div>
    </section>

    <!-- Historia Section -->
    <section class="history-section">
      <div class="section-header">
        <i class="bi bi-book-half"></i>
        <h2>Nuestra Historia</h2>
      </div>
      <div class="timeline">
        <div class="timeline-item">
          <div class="timeline-marker"></div>
          <div class="timeline-content">
            <h3>1969 - Fundación</h3>
            <p>La Universidad Técnica de Ambato fue creada el 18 de abril de 1969 según aprobación del Congreso Nacional, respondiendo a la necesidad de formación profesional de la región central del Ecuador.</p>
          </div>
        </div>
        <div class="timeline-item">
          <div class="timeline-marker"></div>
          <div class="timeline-content">
            <h3>Instituto Superior</h3>
            <p>La Universidad Técnica de Ambato tiene su antecedente académico en un Instituto Superior fundado años atrás, que sentó las bases para el desarrollo de la educación superior en la provincia de Tungurahua.</p>
          </div>
        </div>
        <div class="timeline-item">
          <div class="timeline-marker"></div>
          <div class="timeline-content">
            <h3>Motivación Cultural</h3>
            <p>Entre las razones de motivación cultural para la creación de una universidad en Ambato se encuentra el espíritu emprendedor y progresista de su pueblo, que demandaba acceso a educación superior de calidad.</p>
          </div>
        </div>
      </div>
      <p class="citation"><i class="bi bi-quote"></i> Tomado del libro Creación de la Universidad Técnica de Ambato (Dr. Pedro Reino).</p>
    </section>

    <!-- Objetivos Section -->
    <section class="objectives-section">
      <div class="section-header">
        <i class="bi bi-trophy"></i>
        <h2>Objetivos Institucionales</h2>
      </div>
      <div class="objectives-grid">
        <div class="objective-card">
          <div class="objective-icon">
            <i class="bi bi-mortarboard"></i>
          </div>
          <h3>Formación Profesional</h3>
          <p>Formar y especializar profesionales competentes que aporten al desarrollo social y económico del país con sólidos valores éticos y compromiso ciudadano.</p>
        </div>
        <div class="objective-card">
          <div class="objective-icon">
            <i class="bi bi-lightbulb"></i>
          </div>
          <h3>Investigación</h3>
          <p>Fortalecer la investigación en la universidad para contribuir al desarrollo sostenible del país, generando conocimiento científico y tecnológico innovador.</p>
        </div>
        <div class="objective-card">
          <div class="objective-icon">
            <i class="bi bi-diagram-3"></i>
          </div>
          <h3>Vinculación</h3>
          <p>Vincular la labor universitaria con los sectores económicos, políticos, sociales y culturales del país mediante proyectos de impacto comunitario.</p>
        </div>
        <div class="objective-card">
          <div class="objective-icon">
            <i class="bi bi-graph-up-arrow"></i>
          </div>
          <h3>Calidad</h3>
          <p>Promover la calidad del desempeño institucional en base al modelo organizacional por procesos, garantizando la excelencia académica y administrativa.</p>
        </div>
      </div>
    </section>

    <!-- Himno Section -->
    <section class="himno-section">
      <div class="section-header">
        <i class="bi bi-music-note-beamed"></i>
        <h2>Himno Universitario</h2>
      </div>
      <div class="himno-content">
        <div class="himno-part">
          <h3><i class="bi bi-mic"></i> Coro</h3>
          <p class="himno-text">
            Alma Mater, grandiosa en la ciencia<br>
            en la técnica, el arte, el honor!<br>
            De esta tierra ambateña, conciencia<br>
            de hidalguía para el Ecuador.
          </p>
        </div>

        <div class="himno-stanzas">
          <div class="stanza">
            <h4>I</h4>
            <p>No prodigas poder ni riquezas que convierten al hombre en tirano, sino enseñas la ciencia y nobleza de ser digna, ser noble, ser grande.</p>
          </div>
          <div class="stanza">
            <h4>II</h4>
            <p>Son tus aulas abiertos paisajes, en que anida la paz solidaria, y en las mentes robustos celajes de la ciencia de amor necesaria.</p>
          </div>
          <div class="stanza">
            <h4>III</h4>
            <p>Tu destino es crecer en el tiempo: es sembrar la simiente fecunda, de hombres libres que eleven el pensamiento para hacer esta Patria profunda.</p>
          </div>
        </div>
      </div>
    </section>
  </main>

  <footer>
    <p>© 2025 Universidad Técnica de Ambato · FISEI</p>
  </footer>
</body>

</html>