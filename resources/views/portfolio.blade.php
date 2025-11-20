<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Engineer Portfolio - Professional Profile</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        :root {
            --primary-color: #0f5874;
            --secondary-color: #114e67;
            --accent-color: #4cd2ff;
            --text-dark: #1a1a1a;
            --text-light: #6b7280;
            --bg-light: #f8fafc;
            --bg-white: #ffffff;
            --shadow-sm: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
            --shadow-md: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
            --shadow-lg: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
            --shadow-xl: 0 20px 25px -5px rgba(0, 0, 0, 0.1);
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
            color: var(--text-dark);
            line-height: 1.6;
            background: var(--bg-light);
        }

        /* Navigation */
        .navbar {
            background: var(--bg-white);
            box-shadow: var(--shadow-sm);
            position: fixed;
            width: 100%;
            top: 0;
            z-index: 1000;
            transition: all 0.3s ease;
        }

        .navbar.scrolled {
            box-shadow: var(--shadow-md);
        }

        .nav-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 1rem 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .logo {
            font-size: 1.5rem;
            font-weight: 800;
            color: var(--primary-color);
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .logo i {
            color: var(--accent-color);
        }

        .nav-links {
            display: flex;
            gap: 2rem;
            list-style: none;
        }

        .nav-links a {
            color: var(--text-dark);
            text-decoration: none;
            font-weight: 500;
            transition: color 0.3s ease;
            position: relative;
        }

        .nav-links a:hover {
            color: var(--primary-color);
        }

        .nav-links a::after {
            content: '';
            position: absolute;
            bottom: -5px;
            left: 0;
            width: 0;
            height: 2px;
            background: var(--accent-color);
            transition: width 0.3s ease;
        }

        .nav-links a:hover::after {
            width: 100%;
        }

        .mobile-menu-toggle {
            display: none;
            background: none;
            border: none;
            font-size: 1.5rem;
            color: var(--primary-color);
            cursor: pointer;
        }

        /* Hero Section */
        .hero {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
            position: relative;
            overflow: hidden;
            padding: 2rem;
            margin-top: 70px;
        }

        .hero::before {
            content: '';
            position: absolute;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(76, 210, 255, 0.1) 0%, transparent 70%);
            animation: pulse 15s ease-in-out infinite;
        }

        @keyframes pulse {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.1); }
        }

        .hero-content {
            max-width: 1200px;
            margin: 0 auto;
            text-align: center;
            position: relative;
            z-index: 1;
        }

        .hero-image {
            width: 200px;
            height: 200px;
            border-radius: 50%;
            border: 5px solid var(--accent-color);
            margin: 0 auto 2rem;
            overflow: hidden;
            box-shadow: 0 0 40px rgba(76, 210, 255, 0.3);
            animation: float 6s ease-in-out infinite;
        }

        @keyframes float {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-20px); }
        }

        .hero-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .hero-title {
            font-size: 3.5rem;
            font-weight: 800;
            color: var(--bg-white);
            margin-bottom: 1rem;
            animation: fadeInUp 0.8s ease-out;
        }

        .hero-subtitle {
            font-size: 1.5rem;
            color: var(--accent-color);
            margin-bottom: 1rem;
            font-weight: 600;
            animation: fadeInUp 0.8s ease-out 0.2s backwards;
        }

        .hero-description {
            font-size: 1.125rem;
            color: rgba(255, 255, 255, 0.9);
            max-width: 600px;
            margin: 0 auto 2rem;
            animation: fadeInUp 0.8s ease-out 0.4s backwards;
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .hero-buttons {
            display: flex;
            gap: 1rem;
            justify-content: center;
            flex-wrap: wrap;
            animation: fadeInUp 0.8s ease-out 0.6s backwards;
        }

        .btn {
            padding: 1rem 2rem;
            border-radius: 50px;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            cursor: pointer;
            border: none;
            font-size: 1rem;
        }

        .btn-primary {
            background: var(--accent-color);
            color: var(--text-dark);
        }

        .btn-primary:hover {
            background: #3bc1ee;
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(76, 210, 255, 0.3);
        }

        .btn-secondary {
            background: transparent;
            color: var(--bg-white);
            border: 2px solid var(--bg-white);
        }

        .btn-secondary:hover {
            background: var(--bg-white);
            color: var(--primary-color);
            transform: translateY(-2px);
        }

        .social-links {
            display: flex;
            gap: 1rem;
            justify-content: center;
            margin-top: 2rem;
        }

        .social-link {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.1);
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--bg-white);
            text-decoration: none;
            transition: all 0.3s ease;
            backdrop-filter: blur(10px);
        }

        .social-link:hover {
            background: var(--accent-color);
            color: var(--text-dark);
            transform: translateY(-5px);
        }

        /* Section Styles */
        section {
            padding: 5rem 2rem;
        }

        .section-title {
            text-align: center;
            font-size: 2.5rem;
            font-weight: 800;
            color: var(--text-dark);
            margin-bottom: 3rem;
            position: relative;
            display: inline-block;
            width: 100%;
        }

        .section-title::after {
            content: '';
            position: absolute;
            bottom: -10px;
            left: 50%;
            transform: translateX(-50%);
            width: 80px;
            height: 4px;
            background: var(--accent-color);
            border-radius: 2px;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
        }

        /* About Section */
        .about-content {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 4rem;
            align-items: center;
        }

        .about-text h3 {
            font-size: 2rem;
            color: var(--primary-color);
            margin-bottom: 1rem;
        }

        .about-text p {
            color: var(--text-light);
            font-size: 1.125rem;
            margin-bottom: 1.5rem;
        }

        .stats {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 2rem;
            margin-top: 2rem;
        }

        .stat-item {
            text-align: center;
            padding: 1.5rem;
            background: var(--bg-white);
            border-radius: 12px;
            box-shadow: var(--shadow-md);
            transition: transform 0.3s ease;
        }

        .stat-item:hover {
            transform: translateY(-5px);
        }

        .stat-number {
            font-size: 2.5rem;
            font-weight: 800;
            color: var(--primary-color);
            display: block;
        }

        .stat-label {
            color: var(--text-light);
            font-weight: 600;
            margin-top: 0.5rem;
        }

        /* Skills Section */
        .skills-bg {
            background: var(--bg-white);
        }

        .skills-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 2rem;
        }

        .skill-card {
            background: var(--bg-light);
            padding: 2rem;
            border-radius: 16px;
            transition: all 0.3s ease;
            border: 2px solid transparent;
        }

        .skill-card:hover {
            border-color: var(--accent-color);
            transform: translateY(-5px);
            box-shadow: var(--shadow-lg);
        }

        .skill-icon {
            width: 60px;
            height: 60px;
            background: linear-gradient(135deg, var(--primary-color), var(--accent-color));
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            color: white;
            margin-bottom: 1rem;
        }

        .skill-card h3 {
            font-size: 1.25rem;
            color: var(--text-dark);
            margin-bottom: 0.5rem;
        }

        .skill-card p {
            color: var(--text-light);
            font-size: 0.9375rem;
        }

        /* Projects Section */
        .projects-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
            gap: 2rem;
        }

        .project-card {
            background: var(--bg-white);
            border-radius: 16px;
            overflow: hidden;
            box-shadow: var(--shadow-md);
            transition: all 0.3s ease;
        }

        .project-card:hover {
            transform: translateY(-10px);
            box-shadow: var(--shadow-xl);
        }

        .project-image {
            width: 100%;
            height: 200px;
            background: linear-gradient(135deg, var(--primary-color), var(--accent-color));
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 4rem;
            color: rgba(255, 255, 255, 0.3);
        }

        .project-content {
            padding: 1.5rem;
        }

        .project-content h3 {
            font-size: 1.375rem;
            color: var(--text-dark);
            margin-bottom: 0.75rem;
        }

        .project-content p {
            color: var(--text-light);
            margin-bottom: 1rem;
        }

        .project-tags {
            display: flex;
            flex-wrap: wrap;
            gap: 0.5rem;
            margin-bottom: 1rem;
        }

        .tag {
            padding: 0.25rem 0.75rem;
            background: var(--bg-light);
            color: var(--primary-color);
            border-radius: 20px;
            font-size: 0.875rem;
            font-weight: 500;
        }

        .project-links {
            display: flex;
            gap: 1rem;
        }

        .project-link {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            color: var(--primary-color);
            text-decoration: none;
            font-weight: 600;
            transition: color 0.3s ease;
        }

        .project-link:hover {
            color: var(--accent-color);
        }

        /* Experience Section */
        .experience-bg {
            background: var(--bg-white);
        }

        .timeline {
            position: relative;
            padding: 2rem 0;
        }

        .timeline::before {
            content: '';
            position: absolute;
            left: 50%;
            transform: translateX(-50%);
            width: 2px;
            height: 100%;
            background: var(--accent-color);
        }

        .timeline-item {
            display: flex;
            margin-bottom: 3rem;
            position: relative;
        }

        .timeline-item:nth-child(odd) {
            flex-direction: row;
        }

        .timeline-item:nth-child(even) {
            flex-direction: row-reverse;
        }

        .timeline-content {
            width: 45%;
            padding: 2rem;
            background: var(--bg-light);
            border-radius: 16px;
            box-shadow: var(--shadow-md);
            position: relative;
        }

        .timeline-dot {
            position: absolute;
            left: 50%;
            transform: translateX(-50%);
            width: 20px;
            height: 20px;
            background: var(--accent-color);
            border-radius: 50%;
            border: 4px solid var(--bg-white);
            z-index: 1;
        }

        .timeline-date {
            color: var(--primary-color);
            font-weight: 700;
            font-size: 0.875rem;
            margin-bottom: 0.5rem;
        }

        .timeline-content h3 {
            font-size: 1.375rem;
            color: var(--text-dark);
            margin-bottom: 0.5rem;
        }

        .timeline-content h4 {
            color: var(--text-light);
            font-weight: 600;
            margin-bottom: 1rem;
        }

        .timeline-content p {
            color: var(--text-light);
        }

        /* Contact Section */
        .contact-container {
            max-width: 800px;
            margin: 0 auto;
        }

        .contact-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 2rem;
            margin-bottom: 3rem;
        }

        .contact-item {
            text-align: center;
            padding: 2rem;
            background: var(--bg-white);
            border-radius: 16px;
            box-shadow: var(--shadow-md);
            transition: all 0.3s ease;
        }

        .contact-item:hover {
            transform: translateY(-5px);
            box-shadow: var(--shadow-lg);
        }

        .contact-icon {
            width: 60px;
            height: 60px;
            background: linear-gradient(135deg, var(--primary-color), var(--accent-color));
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            color: white;
            margin: 0 auto 1rem;
        }

        .contact-item h3 {
            font-size: 1.125rem;
            color: var(--text-dark);
            margin-bottom: 0.5rem;
        }

        .contact-item a {
            color: var(--text-light);
            text-decoration: none;
            transition: color 0.3s ease;
        }

        .contact-item a:hover {
            color: var(--primary-color);
        }

        .contact-form {
            background: var(--bg-white);
            padding: 3rem;
            border-radius: 16px;
            box-shadow: var(--shadow-lg);
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-group label {
            display: block;
            color: var(--text-dark);
            font-weight: 600;
            margin-bottom: 0.5rem;
        }

        .form-group input,
        .form-group textarea {
            width: 100%;
            padding: 1rem;
            border: 2px solid var(--bg-light);
            border-radius: 8px;
            font-family: inherit;
            font-size: 1rem;
            transition: border-color 0.3s ease;
        }

        .form-group input:focus,
        .form-group textarea:focus {
            outline: none;
            border-color: var(--accent-color);
        }

        .form-group textarea {
            resize: vertical;
            min-height: 150px;
        }

        /* Footer */
        footer {
            background: var(--text-dark);
            color: var(--bg-white);
            text-align: center;
            padding: 2rem;
        }

        footer p {
            margin-bottom: 1rem;
        }

        /* Responsive Design */
        @media (max-width: 1024px) {
            .about-content {
                grid-template-columns: 1fr;
            }

            .timeline::before {
                left: 30px;
            }

            .timeline-item {
                flex-direction: row !important;
                padding-left: 60px;
            }

            .timeline-content {
                width: 100%;
            }

            .timeline-dot {
                left: 30px;
            }
        }

        @media (max-width: 768px) {
            .nav-links {
                position: fixed;
                top: 70px;
                left: -100%;
                width: 100%;
                background: var(--bg-white);
                flex-direction: column;
                padding: 2rem;
                box-shadow: var(--shadow-lg);
                transition: left 0.3s ease;
            }

            .nav-links.active {
                left: 0;
            }

            .mobile-menu-toggle {
                display: block;
            }

            .hero-title {
                font-size: 2.5rem;
            }

            .hero-subtitle {
                font-size: 1.25rem;
            }

            .stats {
                grid-template-columns: 1fr;
            }

            .projects-grid {
                grid-template-columns: 1fr;
            }

            .hero-buttons {
                flex-direction: column;
                align-items: center;
            }

            .btn {
                width: 100%;
                max-width: 300px;
                justify-content: center;
            }

            section {
                padding: 3rem 1rem;
            }
        }

        @media (max-width: 480px) {
            .hero-title {
                font-size: 2rem;
            }

            .section-title {
                font-size: 2rem;
            }

            .contact-form {
                padding: 2rem 1.5rem;
            }
        }

        /* Scroll Animation */
        .fade-in {
            opacity: 0;
            transform: translateY(30px);
            transition: opacity 0.6s ease, transform 0.6s ease;
        }

        .fade-in.visible {
            opacity: 1;
            transform: translateY(0);
        }
    </style>
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar" id="navbar">
        <div class="nav-container">
            <a href="#" class="logo">
                <i class="fas fa-code"></i>
                Engineer Portfolio
            </a>
            <ul class="nav-links" id="navLinks">
                <li><a href="#home">Home</a></li>
                <li><a href="#about">About</a></li>
                <li><a href="#skills">Skills</a></li>
                <li><a href="#projects">Projects</a></li>
                <li><a href="#experience">Experience</a></li>
                <li><a href="#contact">Contact</a></li>
            </ul>
            <button class="mobile-menu-toggle" id="mobileMenuToggle">
                <i class="fas fa-bars"></i>
            </button>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="hero" id="home">
        <div class="hero-content">
            <div class="hero-image">
                <img src="https://via.placeholder.com/200" alt="Engineer Profile">
            </div>
            <h1 class="hero-title">John Doe</h1>
            <p class="hero-subtitle">Senior Software Engineer</p>
            <p class="hero-description">
                Passionate about building scalable applications and solving complex problems.
                Specializing in full-stack development with modern technologies.
            </p>
            <div class="hero-buttons">
                <a href="#contact" class="btn btn-primary">
                    <i class="fas fa-envelope"></i>
                    Get In Touch
                </a>
                <a href="#projects" class="btn btn-secondary">
                    <i class="fas fa-folder"></i>
                    View Projects
                </a>
            </div>
            <div class="social-links">
                <a href="https://github.com" target="_blank" class="social-link" aria-label="GitHub">
                    <i class="fab fa-github"></i>
                </a>
                <a href="https://linkedin.com" target="_blank" class="social-link" aria-label="LinkedIn">
                    <i class="fab fa-linkedin-in"></i>
                </a>
                <a href="https://twitter.com" target="_blank" class="social-link" aria-label="Twitter">
                    <i class="fab fa-twitter"></i>
                </a>
                <a href="mailto:email@example.com" class="social-link" aria-label="Email">
                    <i class="fas fa-envelope"></i>
                </a>
            </div>
        </div>
    </section>

    <!-- About Section -->
    <section id="about">
        <div class="container">
            <h2 class="section-title fade-in">About Me</h2>
            <div class="about-content fade-in">
                <div class="about-text">
                    <h3>Hello! I'm a Software Engineer</h3>
                    <p>
                        With over 5 years of experience in software development, I've worked on various projects
                        ranging from enterprise applications to innovative startups. My expertise lies in creating
                        efficient, scalable solutions that drive business success.
                    </p>
                    <p>
                        I'm passionate about clean code, modern architectures, and continuous learning. When I'm not
                        coding, you can find me contributing to open-source projects or exploring new technologies.
                    </p>
                </div>
                <div class="stats">
                    <div class="stat-item fade-in">
                        <span class="stat-number">5+</span>
                        <span class="stat-label">Years Experience</span>
                    </div>
                    <div class="stat-item fade-in">
                        <span class="stat-number">50+</span>
                        <span class="stat-label">Projects Completed</span>
                    </div>
                    <div class="stat-item fade-in">
                        <span class="stat-number">20+</span>
                        <span class="stat-label">Happy Clients</span>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Skills Section -->
    <section id="skills" class="skills-bg">
        <div class="container">
            <h2 class="section-title fade-in">Technical Skills</h2>
            <div class="skills-grid">
                <div class="skill-card fade-in">
                    <div class="skill-icon">
                        <i class="fas fa-code"></i>
                    </div>
                    <h3>Frontend Development</h3>
                    <p>React, Vue.js, JavaScript, TypeScript, HTML5, CSS3, Tailwind CSS</p>
                </div>
                <div class="skill-card fade-in">
                    <div class="skill-icon">
                        <i class="fas fa-server"></i>
                    </div>
                    <h3>Backend Development</h3>
                    <p>Node.js, PHP, Laravel, Python, RESTful APIs, GraphQL</p>
                </div>
                <div class="skill-card fade-in">
                    <div class="skill-icon">
                        <i class="fas fa-database"></i>
                    </div>
                    <h3>Database Management</h3>
                    <p>MySQL, PostgreSQL, MongoDB, Redis, Database Design</p>
                </div>
                <div class="skill-card fade-in">
                    <div class="skill-icon">
                        <i class="fas fa-cloud"></i>
                    </div>
                    <h3>DevOps & Cloud</h3>
                    <p>AWS, Docker, Kubernetes, CI/CD, Git, Linux</p>
                </div>
                <div class="skill-card fade-in">
                    <div class="skill-icon">
                        <i class="fas fa-mobile-alt"></i>
                    </div>
                    <h3>Mobile Development</h3>
                    <p>React Native, Flutter, iOS, Android Development</p>
                </div>
                <div class="skill-card fade-in">
                    <div class="skill-icon">
                        <i class="fas fa-tools"></i>
                    </div>
                    <h3>Tools & Methods</h3>
                    <p>Agile, Scrum, TDD, Jest, VS Code, Jira, Figma</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Projects Section -->
    <section id="projects">
        <div class="container">
            <h2 class="section-title fade-in">Featured Projects</h2>
            <div class="projects-grid">
                <div class="project-card fade-in">
                    <div class="project-image">
                        <i class="fas fa-shopping-cart"></i>
                    </div>
                    <div class="project-content">
                        <h3>E-Commerce Platform</h3>
                        <p>A full-featured e-commerce platform with real-time inventory management, payment processing, and order tracking.</p>
                        <div class="project-tags">
                            <span class="tag">React</span>
                            <span class="tag">Node.js</span>
                            <span class="tag">MongoDB</span>
                        </div>
                        <div class="project-links">
                            <a href="#" class="project-link">
                                <i class="fas fa-external-link-alt"></i>
                                Live Demo
                            </a>
                            <a href="#" class="project-link">
                                <i class="fab fa-github"></i>
                                Source Code
                            </a>
                        </div>
                    </div>
                </div>

                <div class="project-card fade-in">
                    <div class="project-image">
                        <i class="fas fa-tasks"></i>
                    </div>
                    <div class="project-content">
                        <h3>Project Management Tool</h3>
                        <p>A collaborative project management application with real-time updates, task tracking, and team collaboration features.</p>
                        <div class="project-tags">
                            <span class="tag">Vue.js</span>
                            <span class="tag">Laravel</span>
                            <span class="tag">MySQL</span>
                        </div>
                        <div class="project-links">
                            <a href="#" class="project-link">
                                <i class="fas fa-external-link-alt"></i>
                                Live Demo
                            </a>
                            <a href="#" class="project-link">
                                <i class="fab fa-github"></i>
                                Source Code
                            </a>
                        </div>
                    </div>
                </div>

                <div class="project-card fade-in">
                    <div class="project-image">
                        <i class="fas fa-chart-line"></i>
                    </div>
                    <div class="project-content">
                        <h3>Analytics Dashboard</h3>
                        <p>Real-time analytics dashboard with data visualization, custom reports, and performance metrics.</p>
                        <div class="project-tags">
                            <span class="tag">React</span>
                            <span class="tag">Python</span>
                            <span class="tag">PostgreSQL</span>
                        </div>
                        <div class="project-links">
                            <a href="#" class="project-link">
                                <i class="fas fa-external-link-alt"></i>
                                Live Demo
                            </a>
                            <a href="#" class="project-link">
                                <i class="fab fa-github"></i>
                                Source Code
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Experience Section -->
    <section id="experience" class="experience-bg">
        <div class="container">
            <h2 class="section-title fade-in">Work Experience</h2>
            <div class="timeline">
                <div class="timeline-item fade-in">
                    <div class="timeline-content">
                        <span class="timeline-date">2021 - Present</span>
                        <h3>Senior Software Engineer</h3>
                        <h4>Tech Solutions Inc.</h4>
                        <p>Leading development of enterprise applications, mentoring junior developers, and implementing best practices in software architecture.</p>
                    </div>
                    <div class="timeline-dot"></div>
                </div>

                <div class="timeline-item fade-in">
                    <div class="timeline-content">
                        <span class="timeline-date">2019 - 2021</span>
                        <h3>Full Stack Developer</h3>
                        <h4>Digital Innovations Ltd.</h4>
                        <p>Developed and maintained multiple web applications using modern frameworks and technologies. Collaborated with cross-functional teams.</p>
                    </div>
                    <div class="timeline-dot"></div>
                </div>

                <div class="timeline-item fade-in">
                    <div class="timeline-content">
                        <span class="timeline-date">2018 - 2019</span>
                        <h3>Junior Developer</h3>
                        <h4>StartUp Ventures</h4>
                        <p>Started career working on various client projects, learning industry best practices and modern development workflows.</p>
                    </div>
                    <div class="timeline-dot"></div>
                </div>
            </div>
        </div>
    </section>

    <!-- Contact Section -->
    <section id="contact">
        <div class="container contact-container">
            <h2 class="section-title fade-in">Get In Touch</h2>
            <div class="contact-grid">
                <div class="contact-item fade-in">
                    <div class="contact-icon">
                        <i class="fas fa-envelope"></i>
                    </div>
                    <h3>Email</h3>
                    <a href="mailto:john.doe@example.com">john.doe@example.com</a>
                </div>
                <div class="contact-item fade-in">
                    <div class="contact-icon">
                        <i class="fas fa-phone"></i>
                    </div>
                    <h3>Phone</h3>
                    <a href="tel:+1234567890">+1 (234) 567-890</a>
                </div>
                <div class="contact-item fade-in">
                    <div class="contact-icon">
                        <i class="fas fa-map-marker-alt"></i>
                    </div>
                    <h3>Location</h3>
                    <p>San Francisco, CA</p>
                </div>
            </div>

            <form class="contact-form fade-in">
                <div class="form-group">
                    <label for="name">Name</label>
                    <input type="text" id="name" name="name" required>
                </div>
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" required>
                </div>
                <div class="form-group">
                    <label for="message">Message</label>
                    <textarea id="message" name="message" required></textarea>
                </div>
                <button type="submit" class="btn btn-primary" style="width: 100%;">
                    <i class="fas fa-paper-plane"></i>
                    Send Message
                </button>
            </form>
        </div>
    </section>

    <!-- Footer -->
    <footer>
        <div class="container">
            <p>&copy; 2024 John Doe. All rights reserved.</p>
            <div class="social-links">
                <a href="https://github.com" target="_blank" class="social-link" aria-label="GitHub">
                    <i class="fab fa-github"></i>
                </a>
                <a href="https://linkedin.com" target="_blank" class="social-link" aria-label="LinkedIn">
                    <i class="fab fa-linkedin-in"></i>
                </a>
                <a href="https://twitter.com" target="_blank" class="social-link" aria-label="Twitter">
                    <i class="fab fa-twitter"></i>
                </a>
            </div>
        </div>
    </footer>

    <script>
        // Mobile Menu Toggle
        const mobileMenuToggle = document.getElementById('mobileMenuToggle');
        const navLinks = document.getElementById('navLinks');

        mobileMenuToggle.addEventListener('click', () => {
            navLinks.classList.toggle('active');
            const icon = mobileMenuToggle.querySelector('i');
            icon.classList.toggle('fa-bars');
            icon.classList.toggle('fa-times');
        });

        // Close mobile menu when clicking a link
        document.querySelectorAll('.nav-links a').forEach(link => {
            link.addEventListener('click', () => {
                navLinks.classList.remove('active');
                const icon = mobileMenuToggle.querySelector('i');
                icon.classList.remove('fa-times');
                icon.classList.add('fa-bars');
            });
        });

        // Navbar scroll effect
        window.addEventListener('scroll', () => {
            const navbar = document.getElementById('navbar');
            if (window.scrollY > 100) {
                navbar.classList.add('scrolled');
            } else {
                navbar.classList.remove('scrolled');
            }
        });

        // Smooth scroll for anchor links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    const offset = 80;
                    const targetPosition = target.offsetTop - offset;
                    window.scrollTo({
                        top: targetPosition,
                        behavior: 'smooth'
                    });
                }
            });
        });

        // Scroll animation
        const observerOptions = {
            threshold: 0.1,
            rootMargin: '0px 0px -50px 0px'
        };

        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('visible');
                }
            });
        }, observerOptions);

        document.querySelectorAll('.fade-in').forEach(el => {
            observer.observe(el);
        });

        // Form submission
        document.querySelector('.contact-form').addEventListener('submit', (e) => {
            e.preventDefault();
            alert('Thank you for your message! I will get back to you soon.');
            e.target.reset();
        });
    </script>
</body>
</html>
