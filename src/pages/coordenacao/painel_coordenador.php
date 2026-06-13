<?php
session_start();
if (!isset($_SESSION['email']) || !isset($_SESSION['cargo'])) {
    header('Location: ../tela_login.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Painel Coordenador</title>
    <link rel="shortcut icon" href="../../assets/images/Logo Projeto Sem Fundo.png" type="image/x-icon">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <link rel="stylesheet" href="../css/painel_coordenador.css">
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
                <a href="painel_coordenador.php">🏠 Início</a>
                <a href="dashboard.php">📊 Dashboard</a>
                <a href="ocorrencias.php">📝 Ocorrências</a>
                <a href="alunos.php">🎓 Alunos</a>
            </nav>

            <div class="sidebar-footer">
                <a href="../../backend/logout.php"><button class="logout-btn">Sair</button></a>
            </div>
        </aside>
    </div>

    <main>
        <h3 style="text-align: center; color: rgb(4, 168, 4); padding: 12px;">Bem-vindo ao Painel da Coordenação, <?php echo $_SESSION['email']; ?>! 👋</h3>
        <p style="text-align: center; color: rgb(71, 71, 71);">Veja o que está acontecendo hoje na EEEP Manoel Mano!</p>
    </main>

    <div class="carrossel">
        <div class="carrossel-container" id="meuCarrossel">
            <div class="carrossel-track" id="track">
                <div class="carrossel-slide">
                    <img src="../../assets/images/EEEPMM-01.jpg" alt="Img 1">
                    <div class="slide-caption">EEEP Manoel Mano</div>
                </div>
                <div class="carrossel-slide">
                    <img src="../../assets/images/EEEPMM-02.jpeg" alt="Img 2">
                    <div class="slide-caption">EEEP Manoel Mano</div>
                </div>
                <div class="carrossel-slide">
                    <img src="../../assets/images/EEEPMM-03.jpg" alt="Img 3">
                    <div class="slide-caption">EEEP Manoel Mano</div>
                </div>
                <div class="carrossel-slide">
                    <img src="#" alt="Img 4">
                    <div class="slide-caption">Img 4</div>
                </div>
            </div>

            <button class="nav-btn prev-btn" id="btnAnterior">&#10094;</button>
            <button class="nav-btn next-btn" id="btnProximo">&#10095;</button>

            <div class="carrossel-indicadores" id="indicadores"></div>
        </div>
    </div>

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

        //Slide
        document.addEventListener('DOMContentLoaded', () => {
            const track = document.getElementById('track');
            const slides = Array.from(track.children);
            const btnProximo = document.getElementById('btnProximo');
            const btnAnterior = document.getElementById('btnAnterior');
            const containerIndicadores = document.getElementById('indicadores');

            let indiceAtual = 0;
            const tempoDeTransicao = 8000; // 8 segundos
            let intervaloAutomatico;

            slides.forEach((_, index) => {
                const botao = document.createElement('button');
                botao.classList.add('indicator');
                if (index === 0) botao.classList.add('active');

                botao.addEventListener('click', () => {
                    moverParaSlide(index);
                    reiniciarTemporizador();
                });

                containerIndicadores.appendChild(botao);
            });

            const indicadores = Array.from(containerIndicadores.children);

            // Função principal para mover os slides
            function moverParaSlide(index) {
                track.style.transform = `translateX(-${index * 100}%)`;

                indicadores.forEach(ind => ind.classList.remove('active'));
                indicadores[index].classList.add('active');

                indiceAtual = index;
            }

            // Função para ir para o próximo slide
            function proximoSlide() {
                let proximoIndice = indiceAtual + 1;
                if (proximoIndice >= slides.length) {
                    proximoIndice = 0;
                }
                moverParaSlide(proximoIndice);
            }

            // Função para ir para o slide anterior
            function slideAnterior() {
                let indiceAnterior = indiceAtual - 1;
                if (indiceAnterior < 0) {
                    indiceAnterior = slides.length - 1;
                }
                moverParaSlide(indiceAnterior);
            }

            // Configura a passagem automática a cada 8 segundos
            function iniciarTemporizador() {
                intervaloAutomatico = setInterval(proximoSlide, tempoDeTransicao);
            }

            // Reinicia o tempo se o usuário clicar nos botões manualmente
            function reiniciarTemporizador() {
                clearInterval(intervaloAutomatico);
                iniciarTemporizador();
            }

            // Adiciona os eventos de clique aos botões
            btnProximo.addEventListener('click', () => {
                proximoSlide();
                reiniciarTemporizador();
            });

            btnAnterior.addEventListener('click', () => {
                slideAnterior();
                reiniciarTemporizador();
            });

            //Pausa quando o mouse estiver sobre o carrossel
            const container = document.getElementById('meuCarrossel');
            container.addEventListener('mouseenter', () => clearInterval(intervaloAutomatico));
            container.addEventListener('mouseleave', iniciarTemporizador);

            // Inicia o carrossel assim que a página carrega
            iniciarTemporizador();
        });
    </script>
</body>

</html>