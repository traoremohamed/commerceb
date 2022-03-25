<?php

use App\Helpers\Menu;

$logo = Menu::get_logo();

use Carbon\Carbon;


?>
<table width="100%" border="0" cellspacing="0" cellpadding="0" class="encadre"  align="center">
    <tbody>
    <tr>
        <td class="droite" width="20%">
            <?php if(isset($logo->logo_logo)){?>
                <img alt="Logo" height="100" src="{{ asset('/frontend/logo/'. $logo->logo_logo)}}"/>
            <?php } ?>
        </td>
        <td width="67%" class="droite" align="center"><h2 >Historique des  ventes  a la caisse  <br />

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
        <th>No</th>
        <th>Code vente</th>
        <th>Client</th>
        <th>Montant</th>
        <th>Date vente</th>
    </tr>
    </thead>
    <tbody>

    <?php
    $i = 0;
    $montanttotal = 0;
    foreach ($recherches as $recherche):
        $montanttotal += $recherche->prix_ttc_fact;
        $i++;
        ?>

        <tr>
            <td>{{ $i}}</td>
            <td>{{ $recherche->code_comc}}</td>
            <td>{{ $recherche->nom_cli}} {{ $recherche->prenom_cli}}</td>
            <td align="right"><?php echo number_format($recherche->prix_ttc_fact); ?> </td>
            <td align="right">{{ Carbon::parse($recherche->date_val_bl)->format('d/m/Y') }} </td>
        </tr>

    <?php endforeach; ?>

    <tr>
        <th scope="row"></th>
        <td></td>
        <td colspan="1"><strong> Montant total</strong></td>
        <td><?php echo number_format($montanttotal); ?> </td>
        <td></td>
    </tr>



</table>

