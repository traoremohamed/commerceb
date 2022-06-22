<?php

use App\Helpers\Menu;

$logo = Menu::get_logo();

use Carbon\Carbon;

?>

<!doctype html>
<html>
<head>
<meta charset="utf-8">
	<title>Facture client</title>
	<style>
body {
	family: Arial, Helvetica ;
	font-size: 15px;
}
</style>
</head>

<body>
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
        <td>N° BL: {{$Result->num_bl}}</td>
        <td><strong style="font-size: 40px; align-content: center"></strong></td>
        <td></td>
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
        <td>Date : {{ Carbon::parse($Result->date_cre_bl)->format('d/m/Y') }}</td>
    </tr>




    </tbody>
</table>
<!--<div align="right">
    <input type="button" name="Submit" value="Imprimer" class="ecran visuel_bouton" onclick="window.print();" />
</div>-->
<p>&nbsp;</p>
<table width="100%" border="1" cellpadding="2" cellspacing="0" >
    <thead>
    <tr>
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


</body>
</html>


