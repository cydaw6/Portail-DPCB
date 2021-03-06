<?php


session_start();
if (!isset($_SESSION["logged"]) || $_SESSION["permission"] != "2") header("location: index.php"); //Vérifie si une session est en cours sinon renvoi à l'index
require_once("includes/mysql.php");
include('includes/fonctions.php');

?>

<!DOCTYPE html>
<html>

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no" />
  <title>CV - UGE</title>
  <link rel="stylesheet" href="assets/bootstrap/css/bootstrap.min.css" />
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Lato:300,400,700" />
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Alegreya+Sans+SC" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ionicons/2.0.1/css/ionicons.min.css" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/3.5.2/animate.min.css" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/pikaday/1.6.1/css/pikaday.min.css" />
  <script src="http://html2canvas.hertzen.com/dist/html2canvas.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/1.3.5/jspdf.min.js"></script>
  <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
</head>


<body>
  <!-- Import de la nav-->
  <?php include('includes/nav.php'); ?>
  <main class="page cv-page">
    <section class="portfolio-block cv">
      <h2 class="text-center">Statistiques de vos comptes</h2>
      <div class="container-fluid">
        <div class="group" style="max-width: 90%">
          <div class="row">
            <div class="col">
              <div class="skills portfolio-info-card">
                <h2>Statistiques</h2>
                <div class="row">
                  <div class="skills portfolio-info-card" style="width: 100%; margin-bottom: 15px; padding: 25px">
                    <p class="text-uppercase text-left text-dark" style="
                          margin: 0;
                          text-align: left;
                          font-weight: bold;
                          width: 100%;
                          float: left;
                        ">
                      <span style="text-decoration: underline">CRITèRES DE SéLECTION</span>
                    </p>
                    <div class="table-responsive">
                      <table class="table">
                        <thead>
                          <tr></tr>
                        </thead>
                        <tbody>
                          <tr>
                            <?php

                            if (isset($_POST["datedebut"]) && isset($_POST["datefin"])) {
                              $datedebut = $_POST["datedebut"];
                              $datefin = $_POST["datefin"];

                              if (!empty($_POST["datedebut"])) {
                                $query = "SELECT COUNT(*) compteur,libelle FROM `IMPAYE` WHERE date_remise>='$datedebut' GROUP BY libelle"; // select column
                              }

                              if (!empty($_POST["datefin"])) {
                                $query = "SELECT COUNT(*) compteur,libelle FROM `IMPAYE` WHERE date_remise<='$datefin' GROUP BY libelle"; // select column
                              }

                              if (!empty($_POST["datefin"]) && !empty($_POST["datedebut"])) {
                                $query = "SELECT COUNT(*) compteur,libelle FROM `IMPAYE` WHERE date_remise>='$datedebut' AND date_remise<='$datefin' GROUP BY libelle"; // select column
                              }
                            } else {
                              $query = "SELECT COUNT(*) compteur,libelle FROM `IMPAYE` WHERE 1 GROUP BY libelle"; // select column
                            }

                            $aresult = $db->query($query);
                            ?>

                            <form method="post">
                              <td>
                                <label>Date de début :&nbsp;</label><input type="date" name="datedebut" />
                              </td>
                              <td>
                                <label>Date de fin :&nbsp;</label><input type="date" name="datefin" />
                              </td>
                          </tr>
                          <tr></tr>
                          <tr></tr>
                        </tbody>
                      </table>
                    </div>
                    <button class="btn btn-primary" type="submit" style="
                          text-align: center;
                          background: rgba(255, 255, 255, 0);
                          color: rgb(0, 0, 0);
                          box-shadow: 0px 0px 3px;
                          border-style: none;
                        ">
                      Rechercher
                    </button>
                    </form>
                  </div>
                </div>

                <div class="row">
                  <div class="col">
                    <div class="btn-toolbar" style="
                          margin-bottom: 10px;
                          float: right;
                          border-style: none;
                        ">

                      <div class="btn-group" role="group" style="border-width: 0px; border-style: none">
                      </div>
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col">
                    <div id="chart_container">
                      <input id="save-pdf" type="button" value="Télécharger en PDF" disabled />
                      <script type="text/javascript">
                        google.charts.load('current', {
                          'packages': ['corechart']
                        });
                        google.charts.setOnLoadCallback(drawChart);

                        function drawChart() {

                          var data = google.visualization.arrayToDataTable([
                            ['motif', 'compteur'],
                            <?php
                            while ($row = $aresult->fetch()) {
                              echo "['" . strtolower($row["libelle"]) . "', " . $row["compteur"] . "],";
                            }
                            ?>
                          ]);

                          var options = {
                            title: 'Répartition des motifs d\'impayés'
                          };

                          var chart = new google.visualization.PieChart(document.getElementById('piechart'));
                          var btnSave = document.getElementById('save-pdf');

                          google.visualization.events.addListener(chart, 'ready', function() {
                            btnSave.disabled = false;
                          });

                          btnSave.addEventListener('click', function() {
                            var divHeight = $('#chart_container').height();
                            var divWidth = $('#chart_container').width();
                            var ratio = divHeight / divWidth;
                            html2canvas(document.getElementById("chart_container"), {
                              height: divHeight,
                              width: divWidth,
                              onrendered: function(canvas) {
                                var image = canvas.toDataURL("image/jpeg");
                                var doc = new jsPDF(); // using defaults: orientation=portrait, unit=mm, size=A4
                                var width = doc.internal.pageSize.width;
                                var height = doc.internal.pageSize.height;
                                height = ratio * width;
                                doc.addImage(chart.getImageURI(), 'JPEG', 0, 0, width - 20, height - 10);
                                doc.save('Stat_Libelle.pdf'); //Download the rendered PDF.
                              }
                            });
                          }, false);

                          chart.draw(data, options);
                        }
                      </script>

                      <div id="piechart" style="width: 100%; height: 400px;"></div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="hobbies group">
          <div class="heading"></div>
        </div>
      </div>
    </section>
  </main>
  <footer class="page-footer">
    <div class="container">
      <div class="links">
        <a href="#">A propos</a><a href="#">Contactez-nous</a>
      </div>
      <div class="social-icons">
        <a href="#"><i class="icon ion-social-facebook"></i></a><a href="#"><i class="icon ion-social-instagram-outline"></i></a><a href="#"><i class="icon ion-social-twitter"></i></a>
      </div>
    </div>
  </footer>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.8.0/Chart.bundle.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/pikaday/1.6.1/pikaday.min.js"></script>
  <script src="assets/js/script.min.js"></script>
</body>

</html>