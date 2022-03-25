<?php

use App\Helpers\Menu;

$logo = Menu::get_logo();

?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
        <title>Commande fournisseur</title>
	<style>
body {
	family: Arial, Helvetica ;
	font-size: 15px;
}
</style>
</head>

<body>
<table width="100%" border="0" cellpadding="0" cellspacing="0">
  <tbody>
    <tr>
      <td width="44%" rowspan="7" valign="top">
      <p>
          <?php if(isset($logo->logo_logo)){?>
              <img alt="Logo" height="100" src="{{ asset('/frontend/logo/'. $logo->logo_logo)}}"/>
          <?php } ?>
      </p>
      <p>&nbsp;</p></td>
      <td width="13%">&nbsp;</td>
      <td width="13%"><strong>Date </strong></td>
      <td width="2%" align="center">:</td>
      <td width="28%">{{ $Result->created_at }}</td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td nowrap="nowrap"><strong>Bon de commande N°</strong></td>
      <td align="center">:</td>
      <td> <strong>{{$Result->num_comf}} </strong></td>
    </tr>
    <tr>
      <td rowspan="5">&nbsp;</td>
      <td nowrap="nowrap"><p>&nbsp;</p>
        <p>&nbsp;</p></td>
      <td align="center">&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td nowrap="nowrap"><strong>Code Fournisseur </strong></td>
      <td align="center">:</td>
      <td>{{ $Result->num_fourn }} </td>
    </tr>
    <tr>
      <td><strong>Client </strong></td>
      <td align="center">:</td>
      <td> {{$Result->lib_fourn }}</td>
    </tr>
    <tr>
      <td><strong>Contact</strong></td>
      <td align="center">:</td>
      <td nowrap="nowrap">{{$Result->tel_fourn .' / ' . $Result->cel_fourn}}</span></td>
    </tr>
    <tr>
      <td><strong>Adresse</strong></td>
      <td height="19" align="center">:</td>
      <td>{{$Result->adr_fourn}}</td>
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
        <th nowrap="nowrap">Mtt U ht </th>
        <th nowrap="nowrap">Mtt U tva </th>
        <th nowrap="nowrap">Mtt U ttc </th>
        <th nowrap="nowrap">Total ht</th>
        <th nowrap="nowrap">Total tva</th>
        <th nowrap="nowrap">Total ttc</th>
    </tr>

    </thead>
    <?php
    $i=0;
    $tothtlcomfour=0;
    $tottvalcomfour=0;
    $totttclcomfour=0;
    foreach ($ligneResult as $key => $res):
    $tothtlcomfour+= $res->tot_ht_lcomfour;
    $tottvalcomfour+= $res->tot_tva_lcomfour;
    $totttclcomfour+= $res->tot_ttc_lcomfour;
    $i++;

    ?>

        <tr>
            <td nowrap="nowrap">{{ $res->code_prod }}</td>
            <td>{{ $res->lib_prod }}</td>
            <td align="center">{{ $res->qte_lcomfour }} </td>
            <td align="right">{{ number_format($res->prix_ht_lcomfour,'0',',','.') }}</td>
            <td align="right">{{ number_format($res->prix_tva_lcomfour,'0',',','.') }}</td>
            <td align="right">{{ number_format($res->prix_ttc_lcomfour,'0',',','.') }}</td>
            <td align="right">{{ number_format($res->tot_ht_lcomfour,'0',',','.')}}</td>
            <td align="right">{{ number_format($res->tot_tva_lcomfour,'0',',','.')}}</td>
            <td align="right">{{ number_format($res->tot_ttc_lcomfour,'0',',','.')}}</td>
        </tr>
    <?php endforeach; ?>
        <tr>
          <td height="41" colspan="7">&nbsp;</td>
        </tr>
        <tr>
          <td colspan="7" rowspan="4">&nbsp;</td>
          <td align="right">Sous total :</td>
          <td align="right"> {{number_format($tothtlcomfour,'0',',','.')}} </td>
        </tr>
        <tr>
          <td align="right">Tva : </td>
          <td align="right"> {{number_format($tottvalcomfour,'0',',','.')}} </td>
        </tr>
        <tr>
          <td align="right">&nbsp;</td>
          <td align="right">&nbsp;</td>
        </tr>
        <tr>
          <td align="right" nowrap="nowrap">Total TTC XOF: </td>
          <td align="right"> {{number_format($totttclcomfour,'0',',','.')}} </td>
        </tr>

</table>


</body>
</html>


