<?php

use App\Helpers\Menu;

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
        <td width="67%" class="droite" align="center"><h2 >Etat des Mouvements de stock (ENTREE / SORTIE) <br />

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
        <th>Code produit</th>
        <th>Nom produit</th>
        <th>Quantite</th>
        <th>Mouvement</th>
    </tr>

    <?php
    $i = 0;
    foreach ($recherches as $recherche):
        $i++;
        ?>

        <tr>
            <td>{{ $i}}</td>
            <td>{{ $recherche->code_prod}}</td>
            <td>{{ $recherche->lib_prod}}</td>
            <td>{{ $recherche->qte_mvstck}}</td>
            <td>
                <?php if($recherche->sens_mvstck == 'S '){ ?>
                    <label class="badge badge-primary">SORTIE</label>
                <?php }else{ ?>
                    <label class="badge badge-warning">ENTREE</label>
                <?php } ?>
            </td>
        </tr>

    <?php endforeach; ?>



</table>

