<?php

use App\Helpers\Menu;

$logo = Menu::get_logo();

?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
        <title>FACTURE PROFORMA</title>
	<style>
body {
	family: Arial, Helvetica ;
	font-size: 15px;
}
</style>
</head>

<body>
<div style="padding: 30px">


<table width="100%" border="0" cellpadding="0" cellspacing="0">
  <tbody>
    <tr style="border: 12px solid black;">
        <td style="padding: 30px;border-bottom: 1px solid black;border-top: 1px solid black;border-left: 1px solid black;">
            <?php if(isset($logo->logo_logo)){?>
                <img alt="Logo" height="100" src="{{ asset('/frontend/logo/'. $logo->logo_logo)}}"/>
            <?php } ?>

        </td>

        <td nowrap="nowrap" style="text-align: right;padding: 30px;border-bottom: 1px solid black;border-top: 1px solid black;border-right: 1px solid black;">
            <?php if(isset($logo->logo_logo)){?>
                <strong STYLE="font-size: 80px"><?php echo $logo->mot_cle;?></strong>
            <?php } ?>
        </td>
    </tr>
    <tr>
        <td></td>
        <td><strong style="font-size: 40px; align-content: center">FACTURE PROFORMA</strong></td>
        <td></td>
    </tr>

    <tr>
      <td>Abidjan le </td>
        <td nowrap="nowrap">{{ $Result->date_cre_comc }}</td>
    </tr>
    <tr>
        <td>PROFORMA N°</td>
        <td>{{$Result->code_comc}}</td>
    </tr>

    <tr style="border: 1px solid black">
      <td width="75px">&nbsp;</td>
      <td width="25px" align="right"> {{$Result->nom_cli .' ' . $Result->prenom_cli}}</td>
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
        <th nowrap="nowrap">Designation</th>
        <th nowrap="nowrap">Qté</th>
        <th nowrap="nowrap">Mtt U ht </th>
        <th nowrap="nowrap">Remise %</th>
        <th nowrap="nowrap">Total ht</th>
    </tr>

    </thead>
<?php
    $i=0;
    $tothtlcomfour=0;
    $tottvalcomfour=0;
    $totttclcomfour=0;
    foreach ($ligneResult as $key => $res):
    $tothtlcomfour+= $res->tot_ht_lcomc;
    $tottvalcomfour+= $res->tot_tva_lcomc;
    $totttclcomfour+= $res->tot_ttc_lcomc;
    $i++;

    ?>
        <tr>
            <td width="50%">{{ $res->lib_prod }}</td>
            <td align="center" width="12%">{{ $res->qte_lcomc }}</td>
            <td align="right" width="13%">{{ number_format($res->prix_ht_lcomc,'0',',','.') }}</td>
            <td align="center" width="12%">{{ $res->remise_lcomc }} %</td>
            <td align="right" width="13%">{{ number_format($res->tot_ht_lcomc,'0',',','.')}}</td>
        </tr>
    <?php endforeach; ?>
        <tr>
          <td height="41" colspan="5">&nbsp;</td>
        </tr>
        <tr>
          <td colspan="3" rowspan="4">&nbsp;</td>
          <td align="right">Sous total :</td>
          <td align="right"> {{number_format($tothtlcomfour,'0',',','.')}} </td>
        </tr>
        <tr>
            <?php
            if ($tottvalcomfour == 0){ ?>
            <!--<td align="right" nowrap="nowrap">Tva 18% <b style="color: firebrick">TVA NON FACTUREE</b> :</td>
            <td align="right">-->
                <?php      // $montt = $tothtlcomfour*0.18;
                //echo number_format($montt,'0',',','.');
                }else{?>
            <td align="right" nowrap="nowrap">Tva 18% :</td>
            <td align="right">
                <?php
                echo number_format($tottvalcomfour ,'0',',','.');
                }
                ?></td>
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

</div>
</body>
</html>


