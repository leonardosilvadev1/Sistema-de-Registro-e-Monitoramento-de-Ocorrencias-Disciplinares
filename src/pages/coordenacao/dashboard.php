<?php
session_start();
    if(!isset($_SESSION['email']) || !isset($_SESSION['cargo'])){
        header('Location: ../tela_login.php');
        exit();
    }

include('../../backend/consultas.php');
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="stylesheet" href="../css/painel_coordenador.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <link rel="shortcut icon" href="../../assets/images/Logo Projeto Sem Fundo.png" type="image/x-icon">
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

    <main class="main-content">
        <div class="content-header">
            <h1>Painel de Desempenho Escolar</h1>
            <button class="btn-pdf" onclick="solicitarExportacaoPDF()">
                📄 Exportar PDF do Dashboard
            </button>
        </div>

        <div class="cards-container">
            <div class="indicator-card border-blue">
                <h3>Alunos Matriculados</h3>
                <div class="value"><?php echo $totalAlunos; ?></div>
            </div>
            <div class="indicator-card border-green">
                <h3>Ocorrências Totais</h3>
                <div class="value"><?php echo $totalOcorrencias; ?></div>
            </div>
            <div class="indicator-card border-orange">
                <h3>Ocorrências no Mês</h3>
                <div class="value"><?php echo $ocorrenciasMes; ?></div>
            </div>
            <div class="indicator-card border-purple">
                <h3>Cursos Ativos</h3>
                <div class="value"><?php echo $totalCursos; ?></div>
            </div>
        </div>

        <div class="charts-container">
            <div class="chart-box" id="boxOcorrenciasCurso">
                <h3>Ocorrências por Curso</h3>
                <div class="canvas-wrapper">
                    <canvas id="canvasOcorrenciasCurso" width="400" height="260"></canvas>
                </div>
            </div>
            <div class="chart-box" id="boxPizza">
                <h3>Percentual de Tipos de Ocorrências</h3>
                <div class="canvas-wrapper">
                    <canvas id="canvasPizza" width="260" height="260"></canvas>
                </div>
            </div>
            <div class="chart-box" id="boxAlunosCurso">
                <h3>Distribuição de Alunos por Curso</h3>
                <div class="canvas-wrapper">
                    <canvas id="canvasAlunosCurso" width="400" height="260"></canvas>
                </div>
            </div>
        </div>
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

        menuBtn.addEventListener('click', toggleMenu);
        overlay.addEventListener('click', toggleMenu);
        window.addEventListener('keydown', (e) => {
            if (e.key === 'Escape' && sidebar.classList.contains('active')) {
                toggleMenu();
            }
        });

        function solicitarExportacaoPDF() {
            const confirmar = confirm("Deseja gerar o arquivo de relatório em formato PDF a partir da tela atual do sistema?");
            if (confirmar) {
                window.print();
            }
        }

        // --- PREPARAÇÃO DOS DADOS DO BANCO DE DADOS ---
        const dadosCursoLabels = <?php echo json_encode($cursosLabels); ?>;
        const dadosCursoValores = <?php echo json_encode($cursosDados); ?>;

        const dadosOcorrenciaLabels = <?php echo json_encode($tiposLabels); ?>;
        const dadosOcorrenciaValores = <?php echo json_encode($tiposDados); ?>;

        const dadosAlunosCursoLabels = <?php echo json_encode($alunosCursoLabels); ?>;
        const dadosAlunosCursoValores = <?php echo json_encode($alunosCursoDados); ?>;

        // Paleta de cores diversificada
        const paletaCores = [
            '#2196F3', // Azul
            '#4CAF50', // Verde
            '#FF9800', // Laranja
            '#9C27B0', // Roxo
            '#F44336', // Vermelho
            '#00BCD4', // Ciano
            '#FFC107', // Amarelo
            '#E91E63'  // Rosa
        ];

        // --- FUNÇÃO PARA GERAR LEGENDA ---
        function gerarLegenda(containerId, labels, cores) {
            const container = document.getElementById(containerId);
            if (!container) return;

            let html = '<div class="chart-legend">';
            for(let i = 0; i < labels.length; i++) {
                const cor = cores[i % cores.length];
                html += `<div class="legend-item"><div class="legend-color" style="background-color: ${cor}"></div>${labels[i]}</div>`;
            }
            html += '</div>';
            
            container.insertAdjacentHTML('beforeend', html);
        }

        // --- RENDERIZADOR DE GRÁFICO DE BARRAS ---
        function desenharGraficoBarras(containerId, idCanvas, labels, dados) {
            const canvas = document.getElementById(idCanvas);
            if (!canvas || dados.length === 0) return;
            const ctx = canvas.getContext('2d');

            const largura = canvas.width;
            const altura = canvas.height;
            const margemEsquerda = 50;
            const margemBase = 40;
            const areaUtilLargura = largura - margemEsquerda - 20;
            const areaUtilAltura = altura - margemBase - 20;

            const valorMaximo = Math.max(...dados) || 1;
            const numeroBarras = dados.length;
            const espacamento = 25;
            const larguraBarra = (areaUtilLargura - (espacamento * (numeroBarras + 1))) / numeroBarras;

            ctx.clearRect(0, 0, largura, altura);

            // Linhas de guia
            ctx.strokeStyle = '#f0f0f0';
            ctx.lineWidth = 1;
            for (let i = 0; i <= 4; i++) {
                const y = margemBase + (areaUtilAltura * (i / 4));
                ctx.beginPath();
                ctx.moveTo(margemEsquerda, altura - y);
                ctx.lineTo(largura - 10, altura - y);
                ctx.stroke();
            }

            // Desenhando as Barras
            for (let i = 0; i < numeroBarras; i++) {
                const alturaBarra = (dados[i] / valorMaximo) * (areaUtilAltura - 20);
                const x = margemEsquerda + espacamento + (i * (larguraBarra + espacamento));
                const y = altura - margemBase - alturaBarra;

                ctx.fillStyle = paletaCores[i % paletaCores.length];
                ctx.fillRect(x, y, larguraBarra, alturaBarra);

                // Valores em cima da barra
                ctx.fillStyle = '#333333';
                ctx.font = 'bold 12px sans-serif';
                ctx.textAlign = 'center';
                ctx.fillText(dados[i], x + (larguraBarra / 2), y - 8);

                // Labels do eixo X simplificados
                ctx.fillStyle = '#6e6d6d';
                ctx.font = '11px sans-serif';
                const textoLabel = labels[i].length > 10 ? labels[i].substring(0, 8) + '..' : labels[i];
                ctx.fillText(textoLabel, x + (larguraBarra / 2), altura - margemBase + 18);
            }

            // Linha Base
            ctx.strokeStyle = '#cccccc';
            ctx.lineWidth = 1.5;
            ctx.beginPath();
            ctx.moveTo(margemEsquerda, altura - margemBase);
            ctx.lineTo(largura - 10, altura - margemBase);
            ctx.stroke();

            // Gerar a Legenda
            gerarLegenda(containerId, labels, paletaCores);
        }

        // --- RENDERIZADOR DE GRÁFICO DE PIZZA ---
        function desenharGraficoPizza(containerId, idCanvas, labels, dados) {
            const canvas = document.getElementById(idCanvas);
            if (!canvas || dados.length === 0) return;
            const ctx = canvas.getContext('2d');

            const centroX = canvas.width / 2;
            const centroY = canvas.height / 2;
            const raio = Math.min(centroX, centroY) - 15;

            const total = dados.reduce((acc, valor) => acc + valor, 0);
            let anguloInicial = 0;

            ctx.clearRect(0, 0, canvas.width, canvas.height);

            for (let i = 0; i < dados.length; i++) {
                const anguloFatia = (dados[i] / total) * 2 * Math.PI;
                ctx.beginPath();
                ctx.moveTo(centroX, centroY);
                ctx.arc(centroX, centroY, raio, anguloInicial, anguloInicial + anguloFatia);
                ctx.closePath();

                ctx.fillStyle = paletaCores[i % paletaCores.length];
                ctx.fill();

                ctx.strokeStyle = '#ffffff';
                ctx.lineWidth = 1.5;
                ctx.stroke();

                const anguloMedio = anguloInicial + (anguloFatia / 2);
                const distanciaTextoX = centroX + Math.cos(anguloMedio) * (raio / 1.6);
                const distanciaTextoY = centroY + Math.sin(anguloMedio) * (raio / 1.6);

                const percentualStr = Math.round((dados[i] / total) * 100) + '%';

                if ((dados[i] / total) > 0.05) {
                    ctx.fillStyle = '#ffffff';
                    ctx.font = 'bold 11px sans-serif';
                    ctx.textAlign = 'center';
                    ctx.textBaseline = 'middle';
                    ctx.fillText(percentualStr, distanciaTextoX, distanciaTextoY);
                }

                anguloInicial += anguloFatia;
            }

            // Gerar a Legenda
            gerarLegenda(containerId, labels, paletaCores);
        }

        window.onload = function() {
            desenharGraficoBarras('boxOcorrenciasCurso', 'canvasOcorrenciasCurso', dadosCursoLabels, dadosCursoValores);
            desenharGraficoPizza('boxPizza', 'canvasPizza', dadosOcorrenciaLabels, dadosOcorrenciaValores);
            desenharGraficoBarras('boxAlunosCurso', 'canvasAlunosCurso', dadosAlunosCursoLabels, dadosAlunosCursoValores);
        };
    </script>
</body>
</html>