<?php
require "sql.php";
require "session.php";
require "configuracao.class.php";
require "fullTbread.class.php";
require "videos.class.php";

// error_reporting(E_ALL);
// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);

$eventPage = $_SESSION[NOME_SESSAO]['pagina'];
$page = "//dtec.tbr.com.br/";
$configuracao = new Configuracao($_SESSION[NOME_SESSAO]['tbread'], $_SESSION[NOME_SESSAO]['dataevento']);

?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Gastrão 2026</title>
    <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate" />
    <meta http-equiv="Pragma" content="no-cache" />
    <meta http-equiv="Expires" content="0" />

    <meta name="description" content="Gastrão 2026">
    <meta name="Keywords" content="Gatrão, 2026, Cirurgia, Endoscopia, Transplante, Oncologia ">
    <meta name="author" content="TBR Produções" />
    <link rel="icon" href="/icon.png" type="image/x-icon" />
    <link rel="canonical" href="https://gastrao.tbr.com.br" />
    <meta name="robots" content="index, follow" />

    <meta property="og:title" content="Gastrão 2026" />
    <meta property="og:description" content="Gastrão 2026" />

    <meta property="og:locale" content="pt_BR">
    <meta property="og:type" content="website" />
    <meta property="og:url" content="https://gastrao.tbr.com.br" />
    <meta property="og:image" content="/banner.png">
    <meta property="og:site_name" content="Gastrão 2026">

    <meta name="twitter:card" content="summary_large_image" />
    <meta name="twitter:title" content="Gastrão 2026" />
    <meta name="twitter:description" content="Gastrão 2026" />
    <meta name="twitter:image" content="/banner.png" />

    <link rel="stylesheet" href="<?= $_SESSION[NOME_SESSAO]['bootstrapcss'] ?>">
    <link rel="stylesheet" href="<?= $_SESSION[NOME_SESSAO]['sweetalertcss'] ?>">
    <link rel="stylesheet" href="<?= $_SESSION[NOME_SESSAO]['intltelinputcss'] ?>">
    <style>
        body {
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        footer {
            margin-top: auto;
        }

        .bg-purple {
            background-color: #002D71 !important;
        }

        .text-principal {
            color: #002D71 !important;
        }

        .border-principal {
            border-color: #002D71 !important;
        }

        .text-green {
            color: #002D71 !important;
        }

        iframe {
            border: #002D71 3px solid !important;
        }

        .img40 {
            height: 10vw;
            max-height: 40px;
        }

        .img50 {
            height: 10vw;
            max-height: 50px;
        }
    </style>
</head>

<body>
    <header>
        <nav class="navbar navbar-expand-xl bg-light shadow fixed-top" data-bs-theme="light">
            <div class="container">
                <a class="navbar-brand" href="<?= $page ?>">
                    <img src="logo.png" alt="Logo do site" class="img50" />
                </a>
                <button class="navbar-toggler border-secondary" type="button" data-bs-toggle="collapse"
                    data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
                    aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
                        <li class="nav-item ms-lg-3">
                            <a href="<?= $page ?>" class="nav-link fw-bold text-dark">Site principal</a>
                        </li>
                        <li class="nav-item ms-lg-3" v-if="isAuthenticated">
                            <a href="javascript:sair()"
                                class="btn btn-secondary bg-gradient fw-semibold rounded-pill px-3 my-3 my-lg-0">Sair</a>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
    </header>

    <main class="mt-5">
        <section id="enable" class="d-none">
            <section class="container pt-5">
                <p class="text-center fw-bold text-principal fs-2 mb-0">
                    35th Digestive and Therapeutic Endoscopy Course
                </p>
                <p class="text-center small">
                    The course will be live stream from 24th to 26th of May 2026
                </p>
            </section>
            <div class="container-fluid py-3 d-none" id="vivo-1">
                <div class="row">
                    <div class="col-xl-9 mx-auto">
                        <div class="row mb-3">
                            <div class="col-12 col-xl-9 mb-3 mx-auto">
                                <div class="row">
                                    <div class="col ratio ratio-16x9 p-0 rounded" id="video-space">
                                        <!-- vídeo -->
                                        <iframe id="activeVideo" class="rounded" src="" allowfullscreen></iframe>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 col-xl-3">
                                <div class="row ">
                                    <div class="col-12 ">
                                        <div class="row">
                                            <div class="col-12 " id="chat-title">
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-12" id="chat-size">
                                                <iframe id="activeChat" class="rounded bg-white" width="100%"
                                                    height="100%" src="" frameborder="0"></iframe>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="container-fluid py-3 d-none" id="vivo-0">
                <div class="row">
                    <div class="col-12 pt-5">
                        <p class="py-3 text-center">
                            <?= date($_SESSION[NOME_SESSAO]['dataevento']) >= date('Y-m-d') ? "See the event schedule <a href='https://gastrao.org.br/evento/gastrao2026/programacao/lista' target='_blank' rel='noopener noreferrer' class='text-decoration-none text-principal fw-semibold'>here</a>." : "The event was live broadcasted on 24, 25 and 26th of May 2026" ?>
                        </p>
                    </div>
                </div>
            </div>
        </section>

        <section id="disable" class="d-none">
            <div class="container-fluid py-3 mt-5">
                <div class="row">
                    <div class="col-12 py-3">
                        <p class="text-center pt-5">
                            To acces the live broadcast you need to be subscribed to the course
                        </p>
                    </div>
                </div>
            </div>
        </section>
    </main>

    <footer class="bg-dark text-white fw-semibold">
        <div class="container py-3">
            <div class="row justify-content-between align-items-center">
                <div class="col-md-auto mb-3 mb-md-0">
                    &copy; 2026 Digestive and Therapeutic Endoscopy Course - All rights reserved.
                </div>
                <div class="col-md-auto">
                    Developed by <a href="https://tbr.com.br" class="text-decoration-none fw-bold text-white"
                        target="_blank">TBR Produções</a>
                </div>
            </div>
        </div>
    </footer>

    <script>
        (() => {
            const dataUser = JSON.parse(sessionStorage.getItem('userData'));
            if (dataUser.isAuthenticated !== 'true') {
                window.location.href = "<?= $page ?>";
            }
        })();
    </script>

    <script src="<?= $_SESSION[NOME_SESSAO]['bootstrapjs'] ?>"></script>
    <script src="<?= $_SESSION[NOME_SESSAO]['sweetalertjs'] ?>"></script>
    <script src="<?= $_SESSION[NOME_SESSAO]['intltelinputjs'] ?>"></script>
    <script src="https://www.gstatic.com/firebasejs/8.3.2/firebase-app.js"></script>
    <script src="https://www.gstatic.com/firebasejs/8.3.2/firebase-auth.js"></script>
    <script src="https://www.gstatic.com/firebasejs/8.3.2/firebase-database.js"></script>
    <script src="<?= $_SESSION[NOME_SESSAO]['jquery'] ?>"></script>
    <script src="<?= $_SESSION[NOME_SESSAO]['mask'] ?>"></script>
    <script src="live.js?v=<?= time() ?>"></script>
</body>

</html>