<?php

use App\Helpers\Menu;

use Carbon\Carbon;

$logo = Menu::get_logo();

?>
<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>BON DE LIVRAISON </title>
    <style>
        @page {
            size: portrait;
        }
        body {
            family: Arial, Helvetica ;
            font-size: 15px;
        }

    </style>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.4.1/dist/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.4.1/dist/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>
</head>

<body>
<div style="padding: 30px">


    <table width="100%" border="0" cellpadding="" cellspacing="">
        <tbody>

        <tr>
            <td> <br/></td>
        </tr>
        <tr style="border: 12px solid black;">

            <div class="col-lg-12" style="padding: 5px; width: 100%; border-bottom: 1px solid black;border-top: 1px solid black;border-left: 1px solid black;border-right: 1px solid black;">
                <div class="row">
                    <div class="col-lg-2">


                        <img alt="Logo" sizes="70px" src="{{ asset('/UBCIIMAGE.png')}}"/>



                    </div>



                </div>
            </div>



        </tr>
        <tr>
            <td> <br/></td>
        </tr>
        <tr>
            <td> N° BL: {{$Result->num_bl}} <br>Date : {{ Carbon::parse($Result->date_cre_bl)->format('d/m/Y') }} </td>
            <td><strong style="font-size: 40px; align-content: center"></strong></td>
            <td></td>
        </tr>
        <tr>
            <td> <br/></td>
        </tr>

        <tr>
            <td></td>
            <td nowrap="nowrap"></td>
        </tr>
        <tr>
            <td> <br/></td>
        </tr>
        <tr>
            <td></td>
            <td></td>
            <td></td>
            <td>{{$Result->nom_cli .' ' . $Result->prenom_cli}}</td>
        </tr>
        <tr>
            <td></td>
            <td></td>
            <td></td>
            <td>N°contribuable : {{ $Result->cpte_contr_cli }}</td>
        </tr>
        <tr>
            <td></td>
            <td></td>
            <td></td>
            <td>contact : {{ $Result->tel_cli }} / {{ $Result->tel_cli }}</td>
        </tr>




        </tbody>
    </table>
    <!--<div align="right">
        <input type="button" name="Submit" value="Imprimer" class="ecran visuel_bouton" onclick="window.print();" />
    </div>-->
    <p>&nbsp;</p>
    <table width="100%" border="1" cellpadding="2" cellspacing="0" >
        <thead>
        <tr style="background-color: azure">
            <th nowrap="nowrap">Code</th>
            <th nowrap="nowrap">Produits</th>
            <th nowrap="nowrap">Qté</th>
        </tr>

        </thead>

        @foreach ($ligneResult as $key => $res)
        <tr>
            <td nowrap="nowrap">{{ $res->code_prod }}</td>
            <td nowrap="nowrap">{{ $res->lib_prod }}</td>
            <td align="center">{{ $res->qte_lbl }}</td>
        </tr>
        @endforeach


    </table>

</div>
</body>
</html>


