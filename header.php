<!DOCTYPE html>
<html>
  <head>
      <style>
          body {
              margin-top: 10px;
              font-family: sans-serif;
              background-color: #888888;
          }

          #inputform {
              text-align: center;
              margin-left: auto;
              margin-right: auto;
              max-width: 80%;
              padding: 10px;
              background-color: #ffffff;
              border-radius: 5px;
          }

          .blue {
              padding: 5px;
              font-size: 1em;
              background-color: dodgerblue;
              color: white;
              border: 1px solid blue;
              border-radius: 5px;
          }

          .red {
              padding: 5px;
              font-size: 1em;
              background-color: red;
              color: white;
              border: 1px solid darkred;
              border-radius: 5px;
          }

          .green {
              padding: 5px;
              font-size: 1em;
              background-color: darkseagreen;
              color: white;
              border: 1px solid darkgreen;
              border-radius: 5px;
          }

          form > p {
              font-size: 1em;
              color: #666666;
          }

          input[type=text], input[type=number], input[type=submit], select {
              text-align: center;
              display: block;
              margin-top: 5px;
              margin-bottom: 10px;
              margin-left: auto;
              margin-right: auto;
              font-size: 1.2em;
          }

          form input[type=checkbox] {
              margin-left: 10px;
              display: inline;
          }

          .lefty {
              text-align: left;
              width: 100%;
              padding-left: 33%;
          }

          form textarea {
              display: block;
              width: 70%;
              margin-left: auto;
              margin-right: auto;
              min-height: auto;
              margin-top: 10px;
              margin-bottom: 10px;
          }

          td {
              padding: 5px;
          }

          td input[type=submit] {
              font-size: 0.8em;
          }

          tr:nth-child(even) {
              background-color: #f2f2f2;
          }

          td i {
              padding-left: 5px;
              padding-right: 5px;
              font-size: 1.1em;
          }

          .locationlist {
              padding: 15px;
              border-radius: 10px;
              margin-left: auto;
              margin-right: auto;
              margin-top: 15px;
              margin-bottom: 15px;
              background-color: #ffffff;
          }

          #footer {
              margin-top: 10px;
              text-align: center;
          }

          #footer p {
              color: #ffffff;
          }

          #footer a {
              color: #333333;
              text-decoration: none;
              font-weight: bolder;
          }

          .locationlist tr:first-child td {
              font-weight: bold;
          }

          .locationlist td {
              text-align: center;
              padding: 6px;
          }

          #logo {
              background-image: url("kki_small.png");
              background-size: contain;
              background-repeat: no-repeat;
              background-position: center;
              width: 30%;
              margin-left: auto;
              margin-right: auto;
              height: 100px;
          }

          body > a {
              /* link for logo */
              text-decoration: none;
          }

          h2 {
              text-align: center;
              color: white;
          }

          h2 a {
              text-decoration: none;
              color: #ffffff;
          }

          form table {
              margin-left: auto;
              margin-right: auto;
          }

          #buttons {
              margin-left: auto;
              margin-right: auto;
              margin-top: 10px;
              text-align: center;
          }

          .tooltip {
              position: relative;
              display: inline-block;
              border-bottom: 1px dotted black;
          }

          .tooltip .tooltiptext {
              visibility: hidden;
              width: 120px;
              background-color: black;
              color: #fff;
              text-align: center;
              border-radius: 6px;
              padding: 5px 0;
              position: absolute;
              z-index: 1;
              top: 150%;
              left: 50%;
              margin-left: -60px;
          }

          .tooltip .tooltiptext::after {
              content: "";
              position: absolute;
              bottom: 100%;
              left: 50%;
              margin-left: -5px;
              border-width: 5px;
              border-style: solid;
              border-color: transparent transparent black transparent;
          }

          .tooltip:hover .tooltiptext {
              visibility: visible;
              font-family: sans-serif;
              font-size: 0.8em;
          }

          #message {
              position: absolute;
              top: 110px;
              left: 25%;
              width: 50%;
              text-align: center;
          }

          .success {
              padding: 10px;
              background-color: darkseagreen;
              border: 1px solid darkgreen;
              border-radius: 5px;
          }

          .fail {
              padding: 10px;
              background-color: indianred;
              border: 1px solid darkred;
              border-radius: 5px;
          }

      </style>
    <meta charset="utf-8">
    <script src="https://kit.fontawesome.com/c9b9f55395.js" crossorigin="anonymous"></script>
    <title>Kneipen- und Kulturinterface</title>
  </head>
  <body>
  <a href="index.php"><div id="logo">&nbsp;</div></a>
  <h2><a href="index.php">Kneipen- und Kulturinterface</a></h2>
