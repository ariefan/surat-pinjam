<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Cetak</title>
  <style>
    html,
    body {
      width: 210mm;
      height: 297mm;
      margin: 0;
      padding: 20mm;
      font: 12pt "Tahoma";
    }

    * {
      box-sizing: border-box;
      -moz-box-sizing: border-box;
    }

    .report {
      width: 170mm;
      /* min-height: 297mm; */
      /* padding: 20mm;
        margin: 10mm auto;
        border: 1px #D3D3D3 solid;
        border-radius: 5px;
        background: white;
        box-shadow: 0 0 5px rgba(0, 0, 0, 0.1);   */
    }

    /* .row {
      display: grid;
      grid-template-columns: repeat(12, 1fr);
      grid-gap: 5px;
    } */

    /* .col-1 { grid-column: span 1;}
    .col-2 { grid-column: span 2;}
    .col-3 { grid-column: span 3;}
    .col-4 { grid-column: span 4;}
    .col-5 { grid-column: span 5;}
    .col-6 { grid-column: span 6;}
    .col-7 { grid-column: span 7;}
    .col-8 { grid-column: span 8;}
    .col-9 { grid-column: span 9;}
    .col-10 { grid-column: span 10;}
    .col-11 { grid-column: span 11;}
    .col-12 { grid-column: span 12;} */

    .row {
      display: -webkit-box;
      display: -ms-flexbox;
      display: flex;
      -ms-flex-wrap: wrap;
      flex-wrap: wrap;
      margin-right: -15px;
      margin-left: -15px;
    }

    .col-auto {
      -webkit-box-flex: 0;
      -ms-flex: 0 0 auto;
      flex: 0 0 auto;
      width: auto;
      max-width: none;
    }

    .col-1 {
      -webkit-box-flex: 0;
      -ms-flex: 0 0 8.333333%;
      flex: 0 0 8.333333%;
      max-width: 8.333333%;
    }

    .col-2 {
      -webkit-box-flex: 0;
      -ms-flex: 0 0 16.666667%;
      flex: 0 0 16.666667%;
      max-width: 16.666667%;
    }

    .col-3 {
      -webkit-box-flex: 0;
      -ms-flex: 0 0 25%;
      flex: 0 0 25%;
      max-width: 25%;
    }

    .col-4 {
      -webkit-box-flex: 0;
      -ms-flex: 0 0 33.333333%;
      flex: 0 0 33.333333%;
      max-width: 33.333333%;
    }

    .col-5 {
      -webkit-box-flex: 0;
      -ms-flex: 0 0 41.666667%;
      flex: 0 0 41.666667%;
      max-width: 41.666667%;
    }

    .col-6 {
      -webkit-box-flex: 0;
      -ms-flex: 0 0 50%;
      flex: 0 0 50%;
      max-width: 50%;
    }

    .col-7 {
      -webkit-box-flex: 0;
      -ms-flex: 0 0 58.333333%;
      flex: 0 0 58.333333%;
      max-width: 58.333333%;
    }

    .col-8 {
      -webkit-box-flex: 0;
      -ms-flex: 0 0 66.666667%;
      flex: 0 0 66.666667%;
      max-width: 66.666667%;
    }

    .col-9 {
      -webkit-box-flex: 0;
      -ms-flex: 0 0 75%;
      flex: 0 0 75%;
      max-width: 75%;
    }

    .col-10 {
      -webkit-box-flex: 0;
      -ms-flex: 0 0 83.333333%;
      flex: 0 0 83.333333%;
      max-width: 83.333333%;
    }

    .col-11 {
      -webkit-box-flex: 0;
      -ms-flex: 0 0 91.666667%;
      flex: 0 0 91.666667%;
      max-width: 91.666667%;
    }

    .col-12 {
      -webkit-box-flex: 0;
      -ms-flex: 0 0 100%;
      flex: 0 0 100%;
      max-width: 100%;
    }

    .avoid-break {
      page-break-before: avoid;
    }

    /* @page {
        size: A4;
        margin: 20mm;
    }
    @media print {
        html, body {
            width: 210mm;
            height: 297mm;        
        }
        .avoid-break {
          break-inside: avoid;
        }
        .report {
            margin: 0;
            padding: 0;
            border: initial;
            border-radius: initial;
            width: initial;
            min-height: initial;
            box-shadow: initial;
            background: initial;
            page-break-after: always;
        }
    } */
  </style>
  <?= $this->renderSection('css') ?>
</head>

<style>

</style>

<body>
  <div class="report">
    <?= $this->renderSection('content') ?>
  </div>
</body>

</html>