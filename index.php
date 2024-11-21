<?php
// Configuración de la base de datos
$db_host = 'localhost';
$db_user = 'root';
$db_pass = 'root';
$db_name = 'conexion';

// Crear conexión
$conn = new mysqli($db_host, $db_user, $db_pass, $db_name);

// Verificar conexión
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Configurar charset
$conn->set_charset("utf8mb4");

// Obtener los likes para cada noticia
$likes_query = "SELECT news_id, COUNT(*) as likes FROM news_likes GROUP BY news_id";
$likes_result = $conn->query($likes_query);
$likes_data = [];

if ($likes_result) {
    while ($row = $likes_result->fetch_assoc()) {
        $likes_data[$row['news_id']] = $row['likes'];
    }
}

// Convertir el array de likes a JSON para usarlo en JavaScript
$likes_json = json_encode($likes_data);
?>

<!DOCTYPE html>

<html lang="es">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Colegio Inmaculada Concepción</title>

    <style>

        :root {

            --primary-color: #2c3e50;

            --secondary-color: #3498db;

            --accent-color: #e74c3c;

            --light-bg: #f5f6fa;

            --white: #ffffff;

        }



        * {

            margin: 0;

            padding: 0;

            box-sizing: border-box;

            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;

        }



        body {

            background-color: var(--light-bg);

        }



        overlay {
    display: none; /* Oculto inicialmente */
    position: fixed;
    top: 0;
    left: 0;
    width: 100vw;
    height: 100vh;
    background-color: rgba(0, 0, 0, 0.5); /* Fondo semitransparente */
    z-index: 99;
}
    
    
    .like-container {
        display: flex;
        align-items: center;
        gap: 5px;
        margin-top: 10px;
    }

    .like-button {
        background: none;
        border: none;
        padding: 5px;
        cursor: pointer;
        display: flex;
        align-items: center;
        transition: transform 0.2s;
    }

    .like-button:hover {
        transform: scale(1.1);
    }

    .like-button svg {
        width: 24px;
        height: 24px;
        fill: #ccc;
        stroke: #666;
        stroke-width: 2;
        transition: all 0.3s;
    }

    .like-button.liked svg {
        fill: #e74c3c;
        stroke: #e74c3c;
        animation: likeAnimation 0.3s ease-in-out;
    }

    .like-count {
        font-size: 0.9rem;
        color: #666;
        min-width: 30px;
    }

    @keyframes likeAnimation {
        0% { transform: scale(1); }
        50% { transform: scale(1.2); }
        100% { transform: scale(1); }
    }


        .navbar {

            background-color: var(--white);

            padding: 1rem 2rem;

            display: flex;

            justify-content: space-between;

            align-items: center;

            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);

            position: fixed;

            width: 100%;

            top: 0;

            z-index: 10;

        }



        .menu-icon {

            display: none;

            cursor: pointer;

        }



        .menu-icon div {

            width: 25px;

            height: 3px;

            background-color: var(--primary-color);

            margin: 5px 0;

            transition: all 0.3s ease;

        }



        .side-menu {
    transform: translateX(-100%);
    transition: transform 0.3s ease;
    /* Asegúrate de que estos valores sean correctos */
    position: fixed;
    top: 0;
    left: 0;
    width: 250px;
    height: 100%;
    background-color: #333; /* Ajusta el color */
    z-index: 100;
        }



        .side-menu a {

            display: block;

            color: var(--white);

            text-decoration: none;

            padding: 15px 20px;

            margin: 5px 0;

            border-radius: 5px;

            transition: background-color 0.3s ease;

        }



        .side-menu a:hover {

            background-color: var(--secondary-color);

        }



        .search-menu {

            background-color: var(--white);

            padding: 2rem;

            border-radius: 10px;

            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);

            margin: 100px auto 30px;

            max-width: 800px;

            text-align: center;

        }



        .search-menu input[type="text"],

        .search-menu input[type="date"] {

            width: calc(50% - 10px);

            padding: 12px 20px;

            margin: 20px 5px;

            border: 2px solid var(--light-bg);

            border-radius: 25px;

            font-size: 16px;

            transition: border-color 0.3s ease;

        }



        .search-menu input[type="text"]:focus,

        .search-menu input[type="date"]:focus {

            border-color: var(--secondary-color);

            outline: none;

        }



        .search-menu span {

            display: inline-block;

            background-color: var(--light-bg);

            padding: 8px 15px;

            margin: 5px;

            border-radius: 20px;

            cursor: pointer;

            transition: background-color 0.3s ease;

        }



        .search-menu span:hover,

        .search-menu span.active {

            background-color: var(--secondary-color);

            color: var(--white);

        }



        h1 {
    color: var(--primary-color);
    text-align: center;
    margin: 0; /* Elimina cualquier margen superior o inferior */
    font-size: 2.5rem;
            
        }

        .image-slider {
    margin-top: 0; /* Elimina cualquier margen superior */
            
        }


        .slider-container {

            display: flex;

            transition: transform 0.5s ease;

            height: 100%;

        }

    


        .slide {

            min-width: 100%;

            height: 100%;

            background-size: cover;

            background-position: center;

        }



        .slider-controls {

            position: absolute;

            bottom: 20px;

            left: 50%;

            transform: translateX(-50%);

            display: flex;

            gap: 10px;

        }



        .slider-dot {

            width: 12px;

            height: 12px;

            border-radius: 50%;

            background-color: rgba(255, 255, 255, 0.5);

            cursor: pointer;

            transition: background-color 0.3s ease;

        }



        .slider-dot.active {

            background-color: var(--white);

        }



        .news-grid {

            display: grid;

            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));

            gap: 20px;

            padding: 20px;

            max-width: 1200px;

            margin: 0 auto;

        }



        .news-card 

    {

      display: flex;

      width: 100%;

      max-width: 800px;

      background: var(--white);

      border-radius: 10px;

      padding: 20px;

      box-shadow: 0 2px 5px rgba(0,0,0,0.1);

      flex-wrap: wrap;

        }



        .news-card:hover {

            transform: translateY(-5px);

        }



       .news-image {

      flex-shrink: 0;

      width: 100%;

      height: auto;

      max-width: 300px;

      object-fit: cover;

      border-radius: 10px;

      margin-right: 20px;

       }



        .news-content {

            padding: 20px;

        }



        .news-date {

            color: var(--secondary-color);

            font-size: 0.9rem;

        }



        .news-title {

            margin: 10px 0;

            color: var(--primary-color);

        }



        .news-description {

            color: #666;

            font-size: 0.9rem;

            line-height: 1.5;

        }

    
    .download-button {
    display: inline-block;
    margin-top: 10px;
    padding: 8px 15px;
    background-color: var(--accent-color);
    color: var(--white);
    text-decoration: none;
    border-radius: 5px;
    font-size: 0.9rem;
    transition: background-color 0.3s ease;
}

    .download-button:hover {
    background-color: #c0392b; /* Color más oscuro en hover */
}


        @media (max-width: 768px) {

            .menu-icon {

                display: block;

            }



            .side-menu {

                transform: translateX(-100%);

            }



            .side-menu.active {

                transform: translateX(0);

            }



            .search-menu {

                margin: 80px 20px 20px;

            }



            .search-menu input[type="text"],

            .search-menu input[type="date"] {

                width: 100%;

                margin: 10px 0;

            }



            .news-grid {

                grid-template-columns: 1fr;

                padding: 10px;

            }

        }



        .search-icon {

            cursor: pointer;

            font-size: 20px;

            color: var(--primary-color);

        }

    </style>

</head>

<body>

    <div class="overlay"></div>



    <div class="side-menu">

     <ul>
      <li><a href="index.html">Página Principal</a></li>
      <li><a href="sobre-nosotros.html">Sobre Nosotros</a></li>
      <li><a href="Recursos-utilizados.html">Recursos utilizados</a></li>
      <li><a href="Anuncios.html">Anuncios</a></li>
      <li><a href="matricula.html">Maticula escolar</a></li>
    </ul>

    </div>



    <div class="navbar">

        <div class="menu-icon">

            <div></div>

            <div></div>

            <div></div>

        </div>

        <h1></h1>

        <div class="search-icon"></div>

    </div>



    <div class="search-menu">

        <input type="text" placeholder="Buscar">

        <input type="date" id="search-date">

        <div class="categories">

            <span class="active">Todas las categorías</span>

            <span>Ajedrez</span>

            <span>Fútbol</span>

            <span>Taekwondo</span>

            <span>Basketball</span>

            <span>Certamen</span>

            <span>Atletismo</span>

            <span>Cultural</span>

        </div>

    </div>



    <h1>Colegio Inmaculada Concepción</h1>



    <div class="image-slider">

        <div class="slider-container">

            <div class="slide" style="background-image: url('/api/placeholder/1200/400')"></div>

            <div class="slide" style="background-image: url('/api/placeholder/1200/400')"></div>

            <div class="slide" style="background-image: url('/api/placeholder/1200/400')"></div>

        </div>

        <div class="slider-controls">

            <div class="slider-dot active"></div>

            <div class="slider-dot"></div>

            <div class="slider-dot"></div>

        </div>

    </div>



    <div class="news-grid">

        <!-- Las tarjetas de noticias se generarán dinámicamente con JavaScript -->

    </div>



    <script>

        // Manejo del menú lateral
const menuIcon = document.querySelector('.menu-icon');
const sideMenu = document.querySelector('.side-menu');
const overlay = document.querySelector('.overlay');
const menuLinks = document.querySelectorAll('.side-menu a');

// Función para abrir/cerrar el menú lateral
function toggleMenu() {
    const isOpen = sideMenu.style.transform === 'translateX(0px)';
    sideMenu.style.transform = isOpen ? 'translateX(-100%)' : 'translateX(0)';
    overlay.style.display = isOpen ? 'none' : 'block';
}

// Abrir o cerrar el menú al hacer clic en el icono del menú
menuIcon.addEventListener('click', toggleMenu);

// Cerrar el menú al hacer clic en el overlay
overlay.addEventListener('click', toggleMenu);

// Cerrar el menú al hacer clic en cualquier enlace dentro del menú lateral
menuLinks.forEach(link => {
    link.addEventListener('click', () => {
        sideMenu.style.transform = 'translateX(-100%)';
        overlay.style.display = 'none';
    });
});


        // Manejo del slider de imágenes

        const sliderContainer = document.querySelector('.slider-container');

        const slides = document.querySelectorAll('.slide');

        const dots = document.querySelectorAll('.slider-dot');

        let currentSlide = 0;



        function updateSlider() {

            sliderContainer.style.transform = `translateX(-${currentSlide * 100}%)`;

            dots.forEach((dot, index) => {

                dot.classList.toggle('active', index === currentSlide);

            });

        }



        dots.forEach((dot, index) => {

            dot.addEventListener('click', () => {

                currentSlide = index;

                updateSlider();

            });

        });



        // Cambio automático de slides

        setInterval(() => {

            currentSlide = (currentSlide + 1) % slides.length;

            updateSlider();

        }, 5000);



        // Manejo de categorías

        const categories = document.querySelectorAll('.categories span');

        categories.forEach(category => {

            category.addEventListener('click', () => {

                categories.forEach(c => c.classList.remove('active'));

                category.classList.add('active');

                filterNews(category.textContent);
                
            });

        });



        // Obtener los likes de PHP
const likesData = <?php echo $likes_json; ?>;

        // Datos actualizados de noticias

        const newsData = [

            {

                id: 1,
                image: 'Ajedrez.jpg',

                date: '2024-08-16',

                title: 'Torneo de Ajedrez 2024',

                description: 'Final del torneo de ajedrez en honor a nuestra señora de la asuncion. Participantes destacados: Maveth Yoshua y Angel Gabriel, obteniendo primer y segundo lugar respectivamente.',

                category: 'Ajedrez',
                
                likes: likesData[1] || 0  
            },

            {

                id: 2,
                image: 'Futbol_femenino.jpg',

                date: '2024-08-08',

                title: 'Campeonato de futbol femenino',

                description: 'Felicitamos al equipo Sub-Campeón de la liga de fútbol-sala femenino. El equipo de Séptimo grado "C" logró ganar el partido con mucho esfuerzo y dedicación.',

                category: 'Fútbol'
                
                likes: likesData[1] || 0  

            },

            {

                id: 3,
                image: 'Certamen.jpg',

                date: '2024-08-29',

                title: 'Aprendiendo para crear, innovando para transformar',

                description: 'Ganadores del primer lugar a nivel departamental en el Certamen del Mejor Estudiante de Educación Primaria. Felicitaciones a Wesley Jareth iglesias y Cristina Nazareth Montalvan Gradiz.',

                category: 'Certamen'
                
                likes: likesData[1] || 0  

            },

            {

                id: 4,
                image: 'Taekwondo.jpg',

                date: '2024-06-15',

                title: 'Seguimos cosechando exitos deportivos',

                description: 'Por obtener medalla de plata a nivel nacional en el torneo de taekwondo felicitamos al estudiante Angel Fabricio Martinez Sarantes.',

                category: 'Taekwondo'
                
                likes: likesData[1] || 0  

            },

            {

                id: 5,
                image: 'Atletismo.jpg',

                date: '2024-05-05',

                title: 'Felicitamos a nuestro estudiante',

                description: 'Por obtener el primer lugar a nivel departamental en salto alto. Felicitaciones a Jorge Josue Caceres.',

                category: 'Atletismo'
                
                likes: likesData[1] || 0  

            },

            {

                id: 6,
                image: 'Judo.jpg',

                date: '2024-06-21',

                title: 'Judo en los juegos escolares',

                description: 'En los juegos escolares se realizó la disciplina del judo. Marvin Jose Morales Almendarez obtuvo el tercer lugar en la etapa nacional.',

                category: 'Taekwondo'
                
                likes: likesData[1] || 0  

            },

            {

                id: 7,
                image: 'Baskquet.jpg',

                date: '2024-05-17',

                title: 'Torneo de basketball',

                description: 'Felicitamos a los estudiantes de Quinto año por ser campeones a nivel departamental en el torneo de basket.',

                category: 'Basketball'
                
                likes: likesData[1] || 0  

            },

            {

                id: 8,
                image: 'Liga-del-conociento-vial.jpg',

                date: '2024-08-28',

                title: 'Etapa nacional del conocimiento vial',

                description: 'Felicitaciones a los estudiantes de tercer año y cuarto año por su destacada participación en la etapa nacional en la liga del conocimiento vial.',

                category: 'Certamen'
                
                likes: likesData[1] || 0  

            },

            {

                id: 9,
                image: 'certamen(3).jpg',

                date: '2024-08-28',

                title: 'Aprendiendo para crear, innovando para transformar (secundaria)',

                description: 'Felicitaciones a Valeria Naylea Perez Enriquez y Juan Francisco Muller Lopez, ganadores del primer lugar a nivel departamental en el certamen "Mejor estudiante de secundaria".',

                category: 'Certamen'
                
                likes: likesData[1] || 0  

            },

            {

                id: 10,
                image: 'certamen(4).jpg',

                date: '2024-08-22',

                title: 'Aprendo y evoluciono con las matematicas',

                description: 'Felicitamos a Luis Octavio Medina Sanchez por obtener el primer lugar a nivel departamental en el certamen.',

                category: 'Certamen'
                
                likes: likesData[1] || 0  

            },

            {

                id: 11,
                image: 'certamen(5).jpg',

                date: '2024-08-21',

                title: 'Certamen mejor docente de educacion secundaria 2024',

                description: 'Felicitamos a Christian Massiel Amador por obtener el tercer lugar a nivel nacional en el certamen "Mejor docente de educación secundaria".',

                category: 'Certamen'
                
                likes: likesData[1] || 0  

            },

            {

                id: 12,
                image: 'futbol_masculino.jpg',

                date: '2024-06-16',

                title: 'Ganadores nunca se rinden',

                description: 'Felicitamos a nuestra selección masculina por representarnos a nivel nacional con destacada participación.',

                category: 'Fútbol'
                
                likes: likesData[1] || 0  

            },

            {

                id: 13,
                image: 'Ajedrez(2).jpg',

                date: '2024-06-06',

                title: 'Ajedrez juvenil, etapa nacional',

                description: 'Felicitamos a Luis Heberto Suarez, Maveth Yoshua Lainez y Eduardo Jose Flores por haber representado a nueva segovia y nuestro colegio en la etapa nacional.',

                category: 'Ajedrez'
                
                likes: likesData[1] || 0  

            },

            {

                id: 14,
                image: 'Cultural.jpg',

                date: '2024-10-09',

                title: 'Nivel municipal de los festivales culturales',

                description: 'Felicitamos al grupo "Los heraldos del camino" por obtener el primer lugar en la etapa municipal del festival cultural.',

                category: 'Cultural'
                
               likes: likesData[1] || 0   

            }

        ];

        
    
        // Reemplaza todo el código relacionado con likes por este:

// Función modificada para manejar likes
async function handleLike(newsId, button) {
    if (button.classList.contains('liked')) {
        return; // Si ya dio like, no hacer nada
    }

    try {
        const response = await fetch('like_handler.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: `news_id=${newsId}`
        });
        
        const data = await response.json();
        
        if (data.success) {

/ Función modificada para crear tarjetas de noticias
function createNewsCard(news) {
    const hasLiked = localStorage.getItem(`liked_${news.id}`) === 'true';
    const likeButtonClass = hasLiked ? 'like-button liked' : 'like-button';
    
    return `
        <div class="news-card" data-category="${news.category}" data-news-id="${news.id}">
            <img class="news-image" src="${news.image}" alt="${news.title}">
            <div class="news-content">
                <div class="news-date">${news.date}</div>
                <h3 class="news-title">${news.title}</h3>
                <p class="news-description">${news.description}</p>
                <a href="${news.image}" download="${news.title}" class="download-button">Descargar Imagen</a>
                <div class="like-container">
                    <button class="${likeButtonClass}" onclick="handleLike(${news.id}, this)">
                        <svg viewBox="0 0 23 24">
                            <path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/>
                        </svg>
                    </button>
                    <span class="like-count">${news.likes}</span>
                </div>
            </div>
        </div>
    `;
}

function handleLike(button) {
    const container = button.closest('.like-container')
    const countSpan = container.querySelector('.like-count');
    countSpan.textContent = data.likes;
    const currentLikes = parseInt(countSpan.textContent);
    
    if (!button.classList.contains('liked')) {
        // Agregar like
        countSpan.textContent = currentLikes + 1;
        button.classList.add('liked');
    } else {
        // Quitar like
        countSpan.textContent = currentLikes - 1;
        button.classList.remove('liked');
    }
}

// Función para filtrar y ordenar noticias por fecha
function filterNews(category) {
    const newsGrid = document.querySelector('.news-grid');
    const filteredNews = category === 'Todas las categorías'
        ? newsData
        : newsData.filter(news => news.category === category);
    
    // Ordenar por fecha descendente
    filteredNews.sort((a, b) => new Date(b.date) - new Date(a.date));
    
    newsGrid.innerHTML = filteredNews.map(createNewsCard).join('');
}

// Inicializar las noticias en la carga de la página
filterNews('Todas las categorías');

// Búsqueda por fecha
const dateInput = document.querySelector('#search-date');
dateInput.addEventListener('change', (e) => {
    const selectedDate = e.target.value;
    const filteredNews = newsData
        .filter(news => news.date === selectedDate)
        .sort((a, b) => new Date(b.date) - new Date(a.date));
    const newsGrid = document.querySelector('.news-grid');
    newsGrid.innerHTML = filteredNews.map(createNewsCard).join('');
});

// Búsqueda por texto
const searchInput = document.querySelector('.search-menu input[type="text"]');
searchInput.addEventListener('input', (e) => {
    const searchText = e.target.value.toLowerCase();
    const activeCategory = document.querySelector('.categories .active').textContent;

    const filteredNews = newsData
        .filter(news => 
            (activeCategory === 'Todas las categorías' || news.category === activeCategory) &&
            (news.title.toLowerCase().includes(searchText) ||
            news.description.toLowerCase().includes(searchText))
        )
        .sort((a, b) => new Date(b.date) - new Date(a.date));

    const newsGrid = document.querySelector('.news-grid');
    newsGrid.innerHTML = filteredNews.map(createNewsCard).join('');
});
    </script>

</body>

</html
 