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
        <td width="67%" class="droite" align="center"><h2 >Historique des factures  <br />

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
    <thead>
    <tr class="droite" bgcolor="#EEEEEE">
        <th>No</th>
        <th>Produit</th>
        <th>Client</th>
        <th>Quantite</th>
        <th>Montant</th>
        <th>Date vente</th>
    </tr>
    </thead>
    <tbody>

    <?php
    $i = 0;
    $montanttotal = 0;
    $quantite = 0;
    foreach ($recherches as $recherche):
        $montanttotal += $recherche->tot_ttc_lcomc;
        $quantite += $recherche->qte_lcomc;
        $i++;
        ?>

        <tr>
            <td>{{ $i}}</td>
            <td>{{ $recherche->lib_prod}}</td>
            <td>{{ $recherche->lib_agce}} </td>
            <td><?php echo number_format($recherche->qte_lcomc); ?> </td>
            <td><?php echo number_format($recherche->tot_ttc_lcomc); ?> </td>
            <td>{{ Carbon::parse($recherche->created_at)->format('d/m/Y')}} </td>

        </tr>

    <?php endforeach; ?>

    <tr>
        <th scope="row"></th>
        <td></td>
        <td></td>
        <td colspan="1"><strong> Quantite total</strong></td>
        <td><?php echo number_format($quantite); ?> </td>
        <td></td>
    </tr>
    <tr>
        <th scope="row"></th>
        <td></td>
        <td></td>
        <td colspan="1"><strong> Montant total</strong></td>
        <td><?php echo number_format($montanttotal); ?> FCFA</td>
        <td></td>
    </tr>
    <tr>
        <th scope="row"></th>
        <td></td>
        <td></td>
        <td colspan="1"><strong> Montant ritourne</strong></td>
        <td><?php echo number_format( ($montanttotal*(3/100))); ?> FCFA</td>
        <td></td>
    </tr>




</table>

