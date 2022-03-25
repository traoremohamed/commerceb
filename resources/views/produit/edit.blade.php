<?php
use Carbon\Carbon;

?>

@extends('layouts.backLayout.designadmin')
@section('content')

    @php($Module='Parametre')
    @php($titre='Liste des produits')
    @php($soustitre='Modifier un produit')
    @php($titreRoute='produit')


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
                                <a class="text-muted">{{$titre}}</a>
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


                @if ($errors->any())

                    <div class="alert alert-custom alert-danger fade show" role="alert">
                        <div class="alert-text">
                            <strong>Echec :</strong> Veuillez renseigner les informations du formulaire !
                        </div>
                        <div class="alert-close">
                            <button type="button" class="btn-sx close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true"><i class="ki ki-close"></i></span>
                            </button>
                        </div>
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
                            <h3 class="card-label">{{$soustitre}} / Code produit : {{$produit->code_prod}}</h3>
                        </div>
                    </div>
                    {!! Form::open(  ['route' => [$titreRoute.'.update', $produit->num_prod], 'method' => 'patch' ] ) !!}

                    @csrf
                    <div class="card-body">
                        <div class="form-group row">
                            <div class="col-lg-4">
                                <label>Libellé * :</label>
                                {!! Form::text('lib_prod',  $produit->lib_prod, ['placeholder' => 'Libellé ','class' => 'form-control','required' => 'required']) !!}
                                <span class="form-text text-muted">  </span>
                            </div>
                            <div class="col-lg-2">
                                <label>Prix de revient:</label>
                                {!! Form::text('prix_revient_prod',  $produit->prix_revient_prod, ['placeholder' => '0 ','class' => 'form-control','disabled' => 'disabled']) !!}
                                <span class="form-text text-muted">  </span>
                            </div>
                            <div class="col-lg-2">
                                <label>Taux de marque:</label>
                                {!! Form::text('taux_marque',  $produit->prix_revient_prod/$produit->prix_ttc, ['placeholder' => '0 ','class' => 'form-control','disabled' => 'disabled']) !!}
                                <span class="form-text text-muted">  </span>
                            </div>
                            <div class="col-lg-4">
                                <label>Sous famille * :</label>
                                <select name="num_sousfam" id="num_sousfam" required="required" class="form-control">
                                    <?php echo $TSfamList; ?>
                                </select>
                                <span class="form-text text-muted">  </span>
                            </div>

                        </div>


                        <div class="form-group row">
                            <div class="col-lg-4">
                                <label>Code barre :</label>
                                {!! Form::number('code_barre_prod',  $produit->code_barre_prod, array('placeholder' => 'Code barre','class' => 'form-control')) !!}
                                <span class="form-text text-muted">  </span>
                            </div>
                            <div class="col-lg-4">
                                <label>Montant ttc :</label>
                                {!! Form::number('prix_ttc', $produit->prix_ttc, array('placeholder' => 'Montant ttc','class' => 'form-control')) !!}
                                <span class="form-text text-muted">  </span>
                            </div>
                            <div class="col-lg-2">
                                <label>TVA * :</label>
                                <select name="flag_tva_prod" class="form-control  ">
                                    <option value=1 @if($produit->flag_tva_prod == 1 ) selected @endif>OUI</option>
                                    <option value=0  @if($produit->flag_tva_prod == 0) selected @endif>NON</option>
                                </select>
                            </div>
                            <div class="col-lg-2">
                                <label>Statut :</label>
                                <select name="flag_prod" class="form-control  ">
                                    <option value=true @if($produit->flag_prod == true ) selected @endif>Actif</option>
                                    <option value=false  @if($produit->flag_prod == false) selected @endif>Inactif</option>
                                </select>


                            </div>

                        </div>


                    </div>
                    <div class="card-footer">
                        <div class="row">
                            <div class="col-lg-6">
                                <a class="btn btn-sm btn-secondary" href="{{ route($titreRoute.'.index') }}"> Retour</a>
                            </div>
                            <div class="col-lg-6 text-right">
                                <button type="submit" class="btn btn-sm btn-primary">Enregistrer</button>
                            </div>
                        </div>
                    </div>

                    </form>
                </div>
            <br>

                <div class="card card-custom" style="width: 100%">
                    <div class="card-header">
                        <div class="card-title">
       											<span class="card-icon">
       												<i class="flaticon2-favourite text-primary"></i>Mouvement de stock (ENTREE / SORTIE)
       											</span>
                            <h3 class="card-label"></h3>

                            <h4 class="card-label"></h4>
                        </div>

                    </div>

                <div class="card-body">
                    <!--begin::Accordion-->
                    <div class="accordion accordion-toggle-arrow" id="accordionExample4">

                            <div class="card">
                                <div class="card-header" id="headingOne4">
                                    <div class="card-title" data-toggle="collapse" data-target="#collapseOne4">
                                        <i class="flaticon2-layers-1"></i>Mouvement de stock (ENTREE / SORTIE)</div>
                                </div>
                                <div id="collapseOne4" class="collapse" data-parent="#accordionExample4">

                                        <div class="card-body">
                                            @if (count($recherches)==0)
                                            <div class="alert alert-custom alert-warning fade show" role="alert">
                                                <div class="alert-text">
                                                    <strong>Echec :</strong> Il n'y a pas eu de mouvement sur ce produit
                                                </div>
                                                <div class="alert-close">
                                                    <button type="button" class="btn-sx close" data-dismiss="alert" aria-label="Close">
                                                        <span aria-hidden="true"><i class="ki ki-close"></i></span>
                                                    </button>
                                                </div>
                                            </div>
                                            @endif
                                            <!--begin: Datatable-->
                                            <table class="table table-bordered table-hover table-checkable" id="kt_datatable"
                                                   style="margin-top: 13px !important">
                                                <thead>
                                                <tr>
                                                    <th>No</th>
                                                    <th>Code produit</th>
                                                    <th>Nom produit</th>
                                                    <th>Quantite</th>
                                                    <th>Mouvement</th>
                                                    <th>Date</th>
                                                </tr>
                                                </thead>
                                                <tbody>
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
                                                        <td>{{ Carbon::parse($recherche->date_mvstck)->format('d-m-Y')}}</td>
                                                    </tr>

                                                <?php endforeach; ?>

                                                </tbody>
                                            </table>
                                            <!--end: Datatable-->
                                        </div>

                                </div>
                            </div>

                    </div>
                    <!--end::Accordion-->

                </div>
                </div>
                <!--end::Card-->
            </div>
            <!--end::Container-->
        </div>
        <!--end::Entry-->
    </div>
    {!! Form::close() !!}

@endsection
