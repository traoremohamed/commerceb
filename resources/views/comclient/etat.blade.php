<?php

use App\Helpers\Menu;

use Carbon\Carbon;

$logo = Menu::get_logo();

?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
        <title>FACTURE PROFORMA</title>
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
        <td>FACTURE PROFORMA N°: {{$Result->code_comc}} <br>Date : {{ Carbon::parse($Result->date_cre_comc)->format('d/m/Y') }} </td>
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


