<div align="center">

  ![Banner](https://images.unsplash.com/photo-1504674900247-0877df9cc836?auto=format&fit=crop&w=1920&q=80)

  # Mideliz

  ### Sistema de Reservas de Eventos Gastronómicos

  Plataforma web para la gestión de reservas de eventos con experiencia gastronómica auténtica. Sistema completo con panel de administración, autenticación de usuarios y diseño responsive.

  [![PHP](https://img.shields.io/badge/PHP-8.0+-777BB4?style=flat-square&logo=php&logoColor=white)](https://php.net)
  [![MySQL](https://img.shields.io/badge/MySQL-8.0+-4479A1?style=flat-square&logo=mysql&logoColor=white)](https://mysql.com)
  [![TailwindCSS](https://img.shields.io/badge/TailwindCSS-3.x-38B2AC?style=flat-square&logo=tailwind-css&logoColor=white)](https://tailwindcss.com)
  [![GSAP](https://img.shields.io/badge/GSAP-3.x-00CE96?style=flat-square&logo=greensock&logoColor=white)](https://greensock.com)
  [![License](https://img.shields.io/badge/License-MIT-green?style=flat-square)](LICENSE)

</div>

---

## 📑 Tabla de Contenido

- [Acerca del Proyecto](#acerca-del-proyecto)
- [Características](#características)
- [Tecnologías](#tecnologías)
- [Estructura del Proyecto](#estructura-del-proyecto)
- [Instalación](#instalación)
- [Variables de Entorno](#variables-de-entorno)
- [Scripts Disponibles](#scripts-disponibles)
- [Arquitectura](#arquitectura)
- [Rendimiento](#rendimiento)
- [Accesibilidad](#accesibilidad)
- [Responsive Design](#responsive-design)
- [Roadmap](#roadmap)
- [Contribuciones](#contribuciones)
- [Licencia](#licencia)
- [Autor](#autor)
- [Agradecimientos](#agradecimientos)

## 📖 Acerca del Proyecto

**Mideliz** es un sistema completo de reservas de eventos gastronómicos diseñado para facilitar la gestión de reservas y ofrecer a los usuarios una experiencia intuitiva al explorar menús y realizar reservas para sus eventos especiales.

### 🎯 Qué resuelve

- **Gestión centralizada**: Permite a los administradores gestionar reservas, usuarios y contactos desde un panel intuitivo.
- **Experiencia de usuario**: Ofrece a los clientes una navegación fluida para explorar menús y realizar reservas.
- **Automatización**: Simplifica el proceso de reserva y confirmación mediante un sistema estructurado.

### 👥 A quién está dirigido

- Restaurantes y servicios de catering que necesitan gestionar reservas de eventos.
- Empresas de organización de eventos gastronómicos.
- Usuarios finales que buscan reservar servicios para eventos especiales.

### 🎯 Objetivos del Proyecto

- Proporcionar una solución web moderna y accesible para gestión de reservas.
- Implementar mejores prácticas de seguridad en autenticación y manejo de datos.
- Ofrecer una experiencia de usuario optimizada con animaciones fluidas y diseño responsive.
- Crear un código base mantenible y escalable.

---

## ✨ Características

- 📱 **Responsive Design**: Adaptado perfectamente a dispositivos móviles, tablets y desktop.
- 🎨 **Animaciones Fluidas**: Transiciones suaves utilizando GSAP para una experiencia premium.
- ♿ **Accesibilidad**: Estructura semántica y navegación accesible.
- ⚡ **Alto Rendimiento**: Optimizado para cargas rápidas y navegación fluida.
- 🔍 **SEO Friendly**: Meta tags y estructura optimizada para motores de búsqueda.
- 🧩 **Componentes Reutilizables**: Código modular y fácil de mantener.
- 🔐 **Seguridad**: Protección CSRF, sanitización de inputs y validaciones.
- 🎯 **Panel de Administración**: Dashboard completo para gestión de reservas y usuarios.
- 📧 **Sistema de Contacto**: Formulario de contacto funcional con validaciones.
- 👤 **Gestión de Perfiles**: Usuarios pueden editar su información personal.
- 🍽️ **Menú Interactivo**: Carrusel de platos con autoplay y gestos táctiles.

---

## 🛠 Tecnologías

### Frontend

| Tecnología | Versión | Descripción |
|------------|---------|-------------|
| HTML5 | - | Estructura semántica |
| CSS3 | - | Estilos personalizados |
| JavaScript | ES6+ | Lógica del cliente |
| TailwindCSS | 3.x | Framework CSS utility-first |
| GSAP | 3.12.2 | Animaciones de alto rendimiento |
| Font Awesome | 6.0.0 | Iconos vectoriales |

### Backend

| Tecnología | Versión | Descripción |
|------------|---------|-------------|
| PHP | 8.0+ | Lógica del servidor |
| MySQL | 8.0+ | Base de datos relacional |
| PDO | - | Abstracción de base de datos |

### Herramientas

| Herramienta | Descripción |
|-------------|-------------|
| XAMPP | Servidor local de desarrollo |
| Git | Control de versiones |
| VS Code | Editor de código |

### Deployment

| Plataforma | Descripción |
|------------|-------------|
| Vercel | Servidor de producción |
| Tailwind CDN | Distribución de assets |

---

## 📁 Estructura del Proyecto

```
midfi/
├── admin/                      # Panel de administración
│   ├── contacts.php           # Gestión de contactos
│   ├── dashboard.php          # Dashboard principal
│   ├── index.php              # Punto de entrada admin
│   ├── login.php              # Login administrador
│   ├── logout.php             # Cerrar sesión admin
│   └── users.php              # Gestión de usuarios
├── config/                     # Configuración
│   └── config.php             # Configuración DB y funciones helper
├── public/                     # Archivos públicos
│   ├── about.php              # Página sobre nosotros
│   ├── assets/                # Assets estáticos
│   │   ├── css/
│   │   │   └── styles.css     # Estilos personalizados
│   │   ├── images/            # Imágenes del proyecto
│   │   └── js/
│   │       └── tailwind.config.js
│   ├── contact.php            # Formulario de contacto
│   ├── index.php              # Página principal
│   ├── login.php              # Login usuarios
│   ├── logout.php             # Cerrar sesión usuarios
│   ├── menu.php               # Menú de platos
│   ├── profile.php            # Perfil de usuario
│   ├── register.php           # Registro de usuarios
│   └── reservation.php        # Formulario de reservas
├── .vscode/                    # Configuración VS Code
├── README.md                   # Documentación del proyecto
└── .gitignore                  # Archivos ignorados por Git
```

---

## 🚀 Instalación

Sigue estos pasos para configurar el proyecto en tu entorno local:

### 1. Clonar el repositorio

```bash
git clone https://github.com/juanes-aa/midfi.git
cd midfi
```

### 2. Configurar el servidor local

Asegúrate de tener **XAMPP** (o similar) instalado y ejecutando:

- Apache Server
- MySQL Database

### 3. Configurar la base de datos

1. Accede a phpMyAdmin: `http://localhost/phpmyadmin`
2. Crea una nueva base de datos llamada `reservas_eventos`
3. Importa el archivo SQL (si está disponible) o crea las tablas manualmente

### 4. Configurar conexión

Edita el archivo `config/config.php` si tus credenciales son diferentes:

```php
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', ''); 
define('DB_NAME', 'reservas_eventos');
```

### 5. Iniciar el proyecto

```bash
# Si usas XAMPP, coloca la carpeta en:
C:/xampp/htdocs/midfi

# Accede al proyecto desde tu navegador:
http://localhost/midfi/midfi/public
```

---

## 🔐 Variables de Entorno

El proyecto utiliza configuración directa en PHP. Modifica `config/config.php` según tu entorno:

```php
// Configuración de la base de datos
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', ''); 
define('DB_NAME', 'reservas_eventos');

// Zona horaria
date_default_timezone_set('America/Mexico_City');
```

---

## 📜 Scripts Disponibles

Este proyecto es una aplicación PHP tradicional, no utiliza npm scripts. Para ejecutar:

```bash
# Iniciar servidor Apache (XAMPP)
# Desde el panel de control de XAMPP, inicia Apache y MySQL

# Acceder a la aplicación
http://localhost/midfi/midfi/public
```

---

## 🏗 Arquitectura

El proyecto sigue una arquitectura **MVC simplificada** con separación de responsabilidades:

### Organización del Código

- **Config Layer**: `config/config.php` - Conexión a BD y funciones helper globales
- **Public Layer**: `public/` - Vistas y controladores de la aplicación pública
- **Admin Layer**: `admin/` - Vistas y controladores del panel administrativo
- **Assets Layer**: `public/assets/` - Recursos estáticos (CSS, JS, imágenes)

### Patrones Implementados

- **DRY (Don't Repeat Yourself)**: Funciones helper reutilizables en `config.php`
- **Separation of Concerns**: Lógica de negocio separada de las vistas
- **Security First**: Validaciones y sanitización en cada punto de entrada

---

## ⚡ Rendimiento

Optimizaciones implementadas para garantizar una experiencia fluida:

### Lazy Loading
- Imágenes cargadas bajo demanda
- Scripts de animación inicializados cuando el DOM está listo

### Code Splitting
- CSS y JS separados por funcionalidad
- Librerías externas cargadas vía CDN optimizado

### Optimización de Imágenes
- Formatos WebP donde es compatible
- Imágenes comprimidas para producción

### Minificación
- CSS optimizado con TailwindCSS
- JavaScript minificado en producción

### Caching
- Headers de cache configurados para assets estáticos
- Sesiones PHP optimizadas

---

## ♿ Accesibilidad

El proyecto implementa estándares WCAG 2.1:

- **Navegación por teclado**: Todos los elementos interactivos son accesibles vía teclado
- **ARIA Labels**: Etiquetas descriptivas en elementos interactivos
- **Contraste**: Relación de contraste WCAG AA en todos los elementos
- **Semántica HTML**: Uso correcto de elementos semánticos (`nav`, `main`, `footer`, etc.)
- **Focus Visible**: Indicadores claros de foco en elementos interactivos

---

## 📱 Responsive Design

El diseño es completamente responsive con breakpoints optimizados:

| Dispositivo | Breakpoint | Características |
|-------------|------------|-----------------|
| Mobile | < 768px | Menú hamburguesa, carrusel 1 slide, touch gestures |
| Tablet | 768px - 1024px | Carrusel 2 slides, navegación desktop |
| Desktop | > 1024px | Navegación completa, hover effects |

### Mobile-First Approach
- Estilos base para móviles
- Media queries para tablets y desktop
- Optimización de touch targets (> 44px)

---

## 🗺 Roadmap

### Versión 2.0 - Próximas Mejores

- [ ] Sistema de pagos integrado (Stripe/PayPal)
- [ ] Notificaciones por email (PHPMailer)
- [ ] Calendario interactivo de disponibilidad
- [ ] Sistema de reseñas y valoraciones
- [ ] Multi-idioma (ES/EN)
- [ ] API REST para integraciones
- [ ] Dashboard de analytics para administradores
- [ ] Sistema de newsletters
- [ ] Integración con redes sociales
- [ ] PWA (Progressive Web App)

### Versión 1.5 - Mejoras Corto Plazo

- [ ] Paginación en el panel admin
- [ ] Exportación de datos a CSV/PDF
- [ ] Búsqueda avanzada de reservas
- [ ] Modo oscuro
- [ ] Optimización de imágenes automática

---

## 🤝 Contribuciones

Las contribuciones son bienvenidas. Si deseas contribuir:

1. Fork el proyecto
2. Crea una rama para tu feature (`git checkout -b feature/AmazingFeature`)
3. Commit tus cambios (`git commit -m 'Add some AmazingFeature'`)
4. Push a la rama (`git push origin feature/AmazingFeature`)
5. Abre un Pull Request

### Guía de Estilo

- Sigue el estilo de código existente
- Comenta funciones complejas
- Mantén los commits descriptivos
- Prueba tus cambios antes de hacer PR

---

## 📄 Licencia

Este proyecto está bajo la Licencia MIT. Para más información, ver el archivo [LICENSE](LICENSE).

```
MIT License

Copyright (c) 2026 [TU_NOMBRE]

Permission is hereby granted, free of charge, to any person obtaining a copy
of this software and associated documentation files (the "Software"), to deal
in the Software without restriction, including without limitation the rights
to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
copies of the Software, and to permit persons to whom the Software is
furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all
copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
SOFTWARE.
```

---

## 👨‍💻 Autor

**Juan Esteban López Moreno**

- 📧 Email: j8716184m@gmail.com
- 🔗 GitHub: https://github.com/Juanes-aa
- 💼 LinkedIn: https://www.linkedin.com/in/juan-esteban-l%C3%B3pez-moreno-799770382/
- 🌐 Portfolio: https://portafolio-eta-olive.vercel.app/

---

## 🙏 Agradecimientos

- A la comunidad de desarrollo web por los recursos y documentación
- A los creadores de las librerías utilizadas (TailwindCSS, GSAP, Font Awesome)
- A todos los que contribuyen al software open source

---

## 📊 Estadísticas del Repositorio

<!-- GitHub Stats -->
<div align="center">
  <img src="https://github-readme-stats.vercel.app/api?username=juanes-aa&repo=midfi&show_icons=true&theme=radical" alt="GitHub Stats">
  
  <!-- Top Languages -->
  <img src="https://github-readme-stats.vercel.app/api/top-langs/?username=juanes-aa&repo=midfi&layout=compact&theme=radical" alt="Top Languages">
  
  <!-- Streak -->
  <img src="https://github-readme-streak-stats.herokuapp.com/?user=juanes-aa&theme=radical" alt="GitHub Streak">
</div>

<!-- Visitors -->
<div align="center">
  <img src="https://visitor-badge.laobi.icu/badge?page_id=juanes-aa.midfi" alt="Visitors">
</div>

---

<div align="center">

**⭐ Si te gusta este proyecto, dale una estrella! ⭐**

Made with ❤️ by Juan Esteban López Moreno

</div>
