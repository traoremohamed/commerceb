<?php

use App\Helpers\Menu;

use Carbon\Carbon;

$logo = Menu::get_logo();

?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
	<!--<title>Facture client</title>-->
	<style>
body {
	family: Arial, Helvetica ;
	font-size: 15px;
}
</style>

</head>

<body>
<table style="padding-bottom: 200px">

</table>
<div class="container">

    <div class="row" >
        <div class="col-lg-6">
            <h2>Facture N°: {{$Result->code_fact}} </h2>
            <h2>{{$Result->nom_cli}} {{$Result->prenom_cli}}</h2>
        </div>
        <div class="col-lg-6" style="text-align: right">
        </div>
    </div>
    <div style="text-align: right">
        <h3>Date: {{ Carbon::parse($Result->date_cre_fact)->format('d-m-Y') }}</h3>
    </div>
</div>
<table width="100%" border="0" cellpadding="0" cellspacing="0">
  <!--<tbody>
  <tr style="text-align: center; position: center"></tr>
    <tr>
      <td width="44%" rowspan="7" valign="top">
      <p>-->
          <?php if(isset($logo->logo_logo)){?>
              <!--<img alt="Logo" height="100" src="{{ asset('/frontend/logo/'. $logo->logo_logo)}}"/>-->
          <?php } ?>
      <!--</p>
      <p>&nbsp;</p></td>
      <td width="13%">&nbsp;</td>
      <td width="13%"><strong>Date </strong></td>
      <td width="2%" align="center">:</td>
      <td width="28%">{{ Carbon::parse($Result->date_cre_fact)->format('d-m-Y') }}</td>
    </tr>
    <tr>
      <td><strong>Facture N°</strong></td>
      <td nowrap="">:</td>
      <td nowrap="nowrap">{{$Result->code_fact}}</td>
      <td></td>
    </tr>
    <tr>
      <td rowspan="5">&nbsp;</td>
      <td nowrap="nowrap"><p>&nbsp;</p>
        <p>&nbsp;</p></td>
      <td align="center">&nbsp;</td>
      <td>&nbsp;</td>
    </tr>-->
    <!--<tr>
      <td nowrap="nowrap"><strong>Code Client </strong></td>
      <td align="center">:</td>
      <td>{{ $Result->code_cli }} </td>
    </tr>-->
    <!--<tr>
      <td><strong>Client </strong></td>
      <td align="center">:</td>
      <td> {{$Result->nom_cli .' ' . $Result->prenom_cli}}</td>
    </tr>
    <tr>
      <td><strong>Contact</strong></td>
      <td align="center">:</td>
      <td nowrap="nowrap">{{$Result->tel_cli .' / ' . $Result->cel_cli}}</span></td>
    </tr>
    <tr>
      <td><strong>Adresse</strong></td>
      <td height="19" align="center">:</td>
      <td>{{$Result->adresse_geo_cli}}</td>
    </tr>
  </tbody>-->
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
        <th nowrap="nowrap">Remise %</th>
        <th nowrap="nowrap">Mtt U ht </th>
        <th nowrap="nowrap">Mtt U ttc </th>
        <th nowrap="nowrap">Total ttc</th>
    </tr>

    </thead>

    @foreach ($ligneResult as $key => $res)
        <tr>
            <td nowrap="nowrap">{{ $res->code_prod }}</td>
            <td>{{ $res->lib_prod }}</td>
            <td align="center">{{ $res->qte_lfact }}</td>
            <td align="center">{{ $res->remise_lfact }} %</td>
            <td align="right">{{ number_format($res->prix_ht_lfact,'0',',','.') }}</td>
            <td align="right">{{ number_format($res->prix_ttc_lfact,'0',',','.') }}</td>
            <td align="right">{{ number_format($res->tot_ttc_lfact,'0',',','.')}}</td>
        </tr>
    @endforeach
        <tr>
          <td height="41" colspan="7">&nbsp;</td>
        </tr>
        <tr>
          <td colspan="5" rowspan="4">&nbsp;</td>
          <td align="right">Sous total :</td>
          <td align="right"> {{number_format($Result->prix_ht_fact,'0',',','.')}} </td>
        </tr>
        <tr>
            <?php
            if ($Result->prix_tva_fact == 0){ ?>
            <td align="right" nowrap="nowrap">Tva 18% <b style="color: firebrick">TVA NON FACTUREE</b> :</td>
            <td align="right">
                <?php       $montt = $Result->prix_ht_fact*0.18;
                echo number_format($montt,'0',',','.');
                }else{?>
            <td align="right" nowrap="nowrap">Tva 18% :</td>
            <td align="right">
                <?php
                echo number_format($Result->prix_tva_fact ,'0',',','.');
                }
                ?>
            </td>
        </tr>
        <tr>
          <td align="right">&nbsp;</td>
          <td align="right">&nbsp;</td>
        </tr>
        <tr>
          <td align="right" nowrap="nowrap">Total TTC XOF: </td>
          <td align="right"> {{number_format($Result->prix_ttc_fact,'0',',','.')}} </td>
        </tr>

</table>


</body>
</html>


