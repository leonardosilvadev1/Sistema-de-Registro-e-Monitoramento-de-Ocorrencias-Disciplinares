<?php
session_start();
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Painel Admin</title>
    <link rel="shortcut icon" href="../../assets/images/Logo Projeto Sem Fundo.png" type="image/x-icon">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            background-color: #ffffff;
            color: #333333;
            line-height: 1.6;
        }

        #menuBtn {
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 1000;
            width: 50px;
            height: 50px;
            background-color: rgb(4, 168, 4);
            color: rgb(255, 255, 255);
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-size: 24px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            transition: background-color 0.3s;
        }

        #menuBtn:hover {
            background-color: rgb(4, 117, 4);
        }

        #sidebar {
            position: fixed;
            top: 0;
            right: 0;
            width: 280px;
            height: 100%;
            background-color: #ffffff;
            z-index: 900;
            box-shadow: -2px 0 10px rgba(0,0,0,0.1);
            transform: translateX(100%);
            transition: transform 0.3s ease-in-out;
            display: flex;
            flex-direction: column;
        }

        #sidebar.active {
            transform: translateX(0);
        }

        #overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            display: none;
            z-index: 800;
        }

        #overlay.active {
            display: block;
        }

        .sidebar-header {
            padding: 20px;
            border-bottom: 1px solid #ffffff;
            font-weight: bold;
            font-size: 20px;
            color: rgb(4, 168, 4);
        }

        .sidebar-nav {
            flex-grow: 1;
            padding: 20px 0;
        }

        .sidebar-nav a {
            display: block;
            padding: 15px 25px;
            color: #555555;
            text-decoration: none;
            transition: background-color 0.2s;
        }

        .sidebar-nav a:hover {
            background-color: #f8f9fa;
            color: rgb(4, 117, 4);
        }

        .sidebar-footer {
            padding: 20px;
            border-top: 1px solid #eeeeee;
        }

        .logout-btn {
            width: 100%;
            padding: 10px;
            background-color: transparent;
            border: 1px solid #dc3545;
            color: #dc3545;
            border-radius: 5px;
            cursor: pointer;
            transition: all 0.2s;
        }

        .logout-btn:hover {
            background-color: #dc3545;
            color: white;
        }

        /*Responsividade*/
        @media (max-width: 768px) {
            .home img {
                height: 80px;
                width: 80px;
            }
            #menuBtn {
                top: 15px;
                right: 15px;
                width: 45px;
                height: 45px;
                font-size: 22px;
            }

            #sidebar {
                width: 100%;
            }
        }

        @media (max-width: 500px) {
            .home img {
                height: 40px;
                width: 40px;
            }
            #menuBtn {
                top: 10px;
                right: 10px;
                width: 40px;
                height: 40px;
                font-size: 20px;
            }
        }
    </style>
</head>
<body>
    <div style="background-color: green;">
        <div class="home" style="display: flex; justify-content: center; align-items: center;">
            <img src="../../assets/images/Logo Projeto Sem Fundo.png" style="height: 100px; width: 100px;" alt="Logo">
            <h2 style="color: white; margin-left: 10px;">SIS-ROM</h2>
        </div>

        <button id="menuBtn">☰</button>

        <div id="overlay"></div>
        <aside id="sidebar">
            <div class="sidebar-header">
                Menu Principal
            </div>
        
            <nav class="sidebar-nav">
                <a href="painel_admin.php">🏠 Início</a>
                <a href="#">👥 Gerenciamento de Funcionários</a>
                <a href="#">📊 Dashboard</a>
                <a href="#">📝 Ocorrências</a>
                <a href="#">🎓 Alunos</a>
            </nav>

            <div class="sidebar-footer">
                <a href="../../backend/logout.php"><button class="logout-btn">Sair</button></a>
            </div>
        </aside>
    </div>
    

    <main>
        <h3 style="text-align: center; color: rgb(4, 168, 4); padding: 12px;">Bem-vindo ao Painel Administrativo, <?php echo $_SESSION['email']; ?>! 👋</h3>
        <p style="text-align: center; color: rgb(71, 71, 71);">Veja o que está acontecendo hoje na EEEP Manoel Mano!</p>
    </main>

    <script>
        const menuBtn = document.getElementById('menuBtn');
        const sidebar = document.getElementById('sidebar');
        const overlay = document.getElementById('overlay');

        function toggleMenu() {
            sidebar.classList.toggle('active');
            overlay.classList.toggle('active');
            
            if (sidebar.classList.contains('active')) {
                menuBtn.textContent = '✕';
                menuBtn.style.backgroundColor = '#dc3545'; 
                menuBtn.style.color = 'rgb(255, 255, 255)';
            } else {
                menuBtn.textContent = '☰';
                menuBtn.style.backgroundColor = 'rgb(4, 168, 4)';
                menuBtn.style.color = 'rgb(255, 255, 255)';
            }
        }

        // Abrir/Fechar ao clicar no botão
        menuBtn.addEventListener('click', toggleMenu);
        overlay.addEventListener('click', toggleMenu);
        window.addEventListener('keydown', (e) => {
            if (e.key === 'Escape' && sidebar.classList.contains('active')) {
                toggleMenu();
            }
        });
    </script>
</body>
</html>