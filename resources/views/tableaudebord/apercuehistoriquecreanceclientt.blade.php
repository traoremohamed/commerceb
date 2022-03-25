<?php

use App\Helpers\Menu;
use Carbon\Carbon;


$logo = Menu::get_logo();

?>
<table width="100%" border="0" cellspacing="0" cellpadding="0" class="encadre"  align="center">
    <tbody>
    <tr>
        <td class="droite" width="20%">
            <?php if(isset($logo->logo_logo)){?>
                <img alt="Logo" height="100" src="{{ asset('/frontend/logo/'. $logo->logo_logo)}}"/>
            <?php } ?>
        </td>
        <td width="67%" class="droite" align="center"><h2 >Historique des créances clients  <br />

            </h2>
            du <?php  if (isset($id)){echo $id;}  ?> au <?php  if (isset($id1)){echo $id1;} ?>

            <?php  //cho $date1; ?></td>
        <td width="13%"><div align="right" ><?php echo date('d/m/Y'); ?> <br />
                <input type="button" name="Submit" value="Imprimer" class="ecran visuel_bouton" onclick="window.print();" />
            </div></td>
    </tr>
    </tbody>
</table>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
        <td height="32">&nbsp;</td>
    </tr>
</table>
<table border="0" width="100%" class="table table-striped table-bordered dTableR encadre" cellspacing="0"
       cellpadding="4">
    <tr class="droite" bgcolor="#EEEEEE">
        <th>N°</th>
        <th>N° Facture</th>
        <th>Client</th>
        <th>Montant ttc facture</th>
        <th>Montant  Payé</th>
        <th>Reste a  Payé</th>
        <th>Date facture validée</th>

    </tr>
    </thead>
    <tbody>

    <?php
    $i = 0;
    $montanttotal = 0;
    $montanttotal1 = 0;
    $montanttotal2 = 0;
    foreach ($recherches as $recherche):
        $montanttotal += $recherche->prix_ttc_fact;
        $montanttotal1 += $recherche->montantpaye;
        $montanttotal2 += $recherche->prix_ttc_fact-$recherche->montantpaye;
        $i++;
        ?>

        <tr>
            <td>{{ $i}}</td>
            <td>{{ $recherche->num_fact}}</td>
            <td>{{ $recherche->nom_cli }} {{ $recherche->prenom_cli }}</td>
            <td>{{ $recherche->prix_ttc_fact}}</td>
            <td align="right"><?php echo number_format($recherche->montantpaye); ?></td>
            <td align="right"><?php echo number_format($recherche->prix_ttc_fact-$recherche->montantpaye); ?></td>
            <td>{{ Carbon::parse($recherche->date_val_fact)->format('d/m/Y')}} </td>
        </tr>

    <?php endforeach; ?>

    <tr>
        <th scope="row"></th>
        <td></td>

        <td colspan="1"><strong> Montant total</strong></td>
        <td align="right"><?php echo number_format($montanttotal); ?> </td>
        <td align="right"><?php echo number_format($montanttotal1); ?> </td>
        <td align="right"><?php echo number_format($montanttotal2); ?> </td>
        <td></td>
    </tr>


</table>

