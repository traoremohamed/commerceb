<?php
use Carbon\Carbon;
?>

@extends('layouts.backLayout.designadmin')

@section('content')


    @php($Module='Tableau de bord')
    @php($titre='')
    @php($soustitre=' historique des bons de receptions ')
    @php($titreRoute='receptionfour')

    <div class="content d-flex flex-column flex-column-fluid" id="kt_content">
        <!--begin::Subheader-->
        <div class="subheader py-2 py-lg-4 subheader-solid" id="kt_subheader">
            <div class="container-fluid d-flex align-items-center justify-content-between flex-wrap flex-sm-nowrap">
                <!--begin::Info-->
                <div class="d-flex align-items-center flex-wrap mr-1">
                    <!--begin::Page Heading-->
                    <div class="d-flex align-items-baseline flex-wrap mr-5">
                        <!--begin::Page Title-->
                        <h5 class="text-dark font-weight-bold my-1 mr-5">{{$soustitre}}</h5>
                        <!--end::Page Title-->
                        <!--begin::Breadcrumb-->
                        <ul class="breadcrumb breadcrumb-transparent breadcrumb-dot font-weight-bold p-0 my-2 font-size-sm">
                            <li class="breadcrumb-item">
                                <a class="text-muted">{{$Module}}</a>
                            </li>
                            <li class="breadcrumb-item">
                                <a href="{{ route('historiquebonreception') }}" class="text-muted">{{$titre}}</a>
                            </li>
                            <li class="breadcrumb-item">
                                <a class="text-muted">{{$soustitre}}</a>
                            </li>
                        </ul>
                        <!--end::Breadcrumb-->
                    </div>
                    <!--end::Page Heading-->
                </div>
                <!--end::Info-->

            </div>
        </div>


        <!--begin::Entry-->
        <div class="d-flex flex-column-fluid">
            <!--begin::Container-->
            <div class="container-fluid">


                @if ($message = Session::get('errors'))
                    <div class="alert alert-danger">
                        {{ $message }}
                    </div>
            @endif


            <!--end::Notice-->
                <!--begin::Card-->
                <div class="card card-custom" style="width: 100%">
                    <div class="card-header">
                        <div class="card-title">
											<span class="card-icon">
												<i class="flaticon2-favourite text-primary"></i>
											</span>
                            <h3 class="card-label">{{$soustitre}}</h3>
                        </div>
                    </div>


                    <div class="card-body">

                        <form action="{{ route('historiquebonreception')}}" method="post"
                              enctype="multipart/form-data">
                            {{ csrf_field() }}
                            <div align="right">
                                <button type="submit" class="btn btn-sm btn-primary">Rechercher</button>
                                <?php if (!empty($recherches)){ ?>

                                <?php
                                /*if (isset($date1)) {$date1=$date1;}else{$date1=0;}
                                if (isset($date2)) {$date2=$date2;}else{$date2=0;}
                                if (isset($com1)) {$com1=$com1;}else{$com1=0;}*/

                              //  dd($com1);die();
                                ?>

                                <a class="btn btn-secondary" href="/apercuehistoriqueventeindirecte/{{$date1}}/{{$date2}}/{{$TClientList1}}/{{$statut}}" target="_blank"

                                >&nbsp;&nbsp; Aper&ccedil;u &nbsp;&nbsp; </a>


                                <?php } ?>



                            </div>
                            <div class="alert alert-custom alert-default" role="alert">
                                <div class="row col-lg-12">
                                    <div class="col-lg-8">
                                        <label> Client</label>
                                        <select class="form-control form-control-sm" name="prod" id="kt_select2_3">
                                            <?php echo $TClientList; ?>
                                        </select>

                                    </div>
                                    <div class="col-lg-4">
                                        <label> Statut</label>
                                        <select class="form-control form-control-sm" name="statut" id="kt_select2_2">
                                            <option value=""></option>
                                            <option value="true">Validé</option>
                                            <option value="false">Non validé</option>
                                        </select>

                                    </div>
                                    <div class="col-lg-6">
                                        Periode du : <input type="date" name="date1" class="form-control"/>

                                    </div>
                                    <div class="col-lg-6">

                                        Au : <input type="date" name="date2" class="form-control"/>
                                    </div>

                                </div>
                            </div>
                        </form>





	   <?php if (!empty($recherches)){ ?>
                        <div class="row">

                            <div class="col-lg-12">

                                <table class="table table-bordered "
                                       style="margin-top: 13px !important">
                                    <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>N° bon reception</th>
                                        <th>Fournisseur</th>
                                        <th>Montant payé</th>
                                        <th>Statut</th>
                                        <th>Date creation bon reception</th>
                                        <th>Action</th>
                                    </tr>
                                    </thead>
                                    <tbody>

                                    <?php
                                    $i = 0;
                                    $montanttotal = 0;
                                    $montanttotal1 =0;
                                    foreach ($recherches as $recherche):
                                        $montanttotal += $recherche->mont_br;
                                        $i++;
                                        ?>

                                        <tr onclick="trclick();">

                                            <td>{{ $i}}</td>
                                            <td>{{ $recherche->num_br}}</td>
                                            <td>{{ $recherche->lib_fourn}}</td>
                                            <td align="right"><?php echo number_format($recherche->mont_br); ?> </td>
                                            <td><?php if ($recherche->annule_br == false and $recherche->flag_br == true) { ?>
                                                    <span class="label label-lg font-weight-bold label-success label-inline"> Validé</span>
                                                <?php }elseif ($recherche->annule_br == false and $recherche->flag_br == false){ ?>
                                                    <span class="label label-lg font-weight-bold label-warning label-inline"> Non validé</span>
                                                <?php }else{ ?>
                                                    <span class="label label-lg font-weight-bold label-danger label-inline">Rejeté</span>
                                                <?php } ?></td>
                                            <td>{{ Carbon::parse($recherche->date_cre_br)->format('d/m/Y')}} </td>
                                            <td><a class="btn btn-primary" href="{{ route($titreRoute.'edit',\App\Helpers\Crypt::UrlCrypt($recherche->num_br)) }}" target="_blank">Voir+</a></td>

                                        </tr>

                                    <?php endforeach; ?>
                                    <tr>
                                        <td height="41" colspan="7">&nbsp;</td>
                                    </tr>
                                    <!--<tr>
                                        <td colspan="1" nowrap="nowrap" rowspan="3"></td>
                                        <td></td>
                                        <td></td>
                                        <td>Montant total facture</td>
                                        <td colspan="1"><strong> <?php echo number_format($montanttotal); ?></strong></td>
                                        <td></td>
                                    </tr>-->
                                    <!--<tr>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td colspan="1"><strong> Montant total payés</strong></td>
                                        <td><?php echo number_format($montanttotal1); ?> </td>
                                        <td></td>
                                    </tr>-->
                                   <!-- <tr>
                                        <td></td>
                                        <td></td>
                                        <td colspan="1"><strong> Montant total restant</strong></td>
                                        <td><?php echo number_format($montanttotal - $montanttotal1); ?> </td>
                                        <td></td>
                                    </tr>-->

                                    </tbody>
                                </table>

                            </div>


                        </div>
                        <?php } ?>









                    </div>
                </div>
            </div>
        </div>
    </div>

<script language="JavaScript" type="text/javascript">
    function trclick(){
        //var = id;
        return '/detailbonreception/10';
</script>

@endsection
