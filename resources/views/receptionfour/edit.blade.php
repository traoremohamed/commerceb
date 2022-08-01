@extends('layouts.backLayout.designadmin')
@section('content')

    @php($Module='Achat')
    @php($titre='Liste des receptions fournisseurs')
    @php($soustitre='Modifier reception fournisseur')
    @php($titreRoute='receptionfour')
    <?php $disabled = ""; if ($flagValide == true) $disabled = 'disabled="disabled"'; ?>


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

                <!--begin::Entry-->
                @if ($message = Session::get('success'))
                    <div class="alert alert-success">
                        {{ $message }}
                    </div>
                @endif
                @if ($message = Session::get('echec'))
                    <div class="alert alert-danger">
                        {{ $message }}
                    </div>
                @endif
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
                            <h3 class="card-label">{{$soustitre}} / N° reception : {{$Receptionfour->num_br}}</h3>
                        </div>
                    </div>
                    {!! Form::open(  ['route' => ['receptionfouredit',\App\Helpers\Crypt::UrlCrypt($Receptionfour->num_br)], 'method' => 'post' ] ) !!}
                    @csrf
                    <div class="card-body">
                        <div class="form-group row">
                            <div class="col-lg-3">
                                <label>Fournisseur :</label>
                                {!! Form::text('', $Receptionfour->lib_fourn, array('placeholder' => 'Date de création ','class' => 'form-control','disabled' => 'disabled')) !!}
                                <span class="form-text text-muted">  </span>
                            </div>
                            <div class="col-lg-2">
                                <label>Agence :</label>
                                {!! Form::text('', $Receptionfour->lib_agce, array('placeholder' => 'Date de création ','class' => 'form-control','disabled' => 'disabled')) !!}

                                <span class="form-text text-muted">  </span>
                            </div>
                            <div class="col-lg-2">
                                <label>Date de création :</label>
                                {!! Form::text('', $Receptionfour->date_cre_br, array('placeholder' => 'Date de création ','class' => 'form-control','disabled' => 'disabled')) !!}
                                <span class="form-text text-muted">  </span>
                            </div>
                            <div class="col-lg-2">
                                <label>Date de validation :</label>
                                {!! Form::text( '',$Receptionfour->date_val_br, ['placeholder' => 'Date de validation','class' => 'form-control','disabled' => 'disabled' ]) !!}
                                <span class="form-text text-muted">  </span>
                            </div>
                            <div class="col-lg-2">
                                <label>Montant total :</label>
                                {!! Form::text('', $Receptionfour->prix_ttc_br, array('placeholder' => 'Montant total ','class' => 'form-control','disabled' => 'disabled' )) !!}
                                <span class="form-text text-muted">  </span>
                            </div>
                            <div class="col-lg-1">
                                <label>Statut :</label>
                                <?php if($flagValide == true and $flagAnnule == false){ ?>
                                <span class="label label-lg font-weight-bold label-success label-inline"> Validé</span>
                                <?php  }elseif($flagValide == false and $flagAnnule == false){?>
                                <span class="label label-lg font-weight-bold label-default label-inline">En cours</span>
                                <?php } elseif ( $flagAnnule == true) { ?>
                                <span class="label label-lg font-weight-bold label-danger label-inline">Annulé</span>
                                <?php }  ?>
                                <span class="form-text text-muted">  </span>
                            </div>
                        </div>
                    </div>

                    <div class="card-footer">
                        <div class="row">
                            <div class="col-lg-12 text-right">
                                @can('role-create')
                                    <a class="btn btn-sm btn-secondary" href="{{ route('receptionfour') }}"> Retour</a>
                                <a class="btn btn-sm btn-info"
                                   onclick="NewWindow('{{ route('receptionfouretat',\App\Helpers\Crypt::UrlCrypt($Receptionfour->num_br))}}','',screen.width,screen.height,'yes','center',1);">
                                    Aper&ccedil;u</a>
                                    <?php if($flagValide != true and $flagAnnule != true) { ?>
                                    <button type="submit" name="action" value="Annuler"
                                            class="btn btn-sm btn-danger">Annuler
                                    </button>
                                    <button onclick='javascript:if (!confirm("Voulez-vous Faire l entree en stock ?")) return false;' type="submit" name="action" value="Valider" class="btn btn-sm btn-success">
                                        Valider
                                    </button>
                                    <?php } ?>
                                @endcan
                            </div>
                        </div>
                    </div>
                    {!! Form::close() !!}


                </div>
                <br>
                <div class="card card-custom" style="width: 100%">
                    <div class="card-body">
                        {!! Form::open(  ['route' => ['receptionfouredit',\App\Helpers\Crypt::UrlCrypt($Receptionfour->num_br)], 'method' => 'post' ] ) !!}

                        <table class="table table-bordered table-hover table-checkable"
                               style="margin-top: 13px !important">
                            <thead>
                            <tr>
                                <th>Code</th>
                                <th>Produits</th>
                                <th>Quantité</th>
                                <th>Montant ht</th>
                                <th>Montant tva</th>
                                <th>Montant ttc</th>
                                <th>Total ht</th>
                                <th>Total tva</th>
                                <th>Total ttc</th>
                                <th>Prix ht de vente unitaire</th>
                                <th>Prix ttc de vente unitaire</th>
                                <th>Taux de marque</th>
                                <th>Action</th>
                            </tr>

                            </thead>
                            <tbody>
                            @foreach ($ligneReceptionfour as $key => $res)
                                <tr>
                                    <td>
                                        {{ $res->code_prod }}
                                        <input type="hidden" class="form-control" name="num_prod/{{ $res->code_prod }}" value="{{$res->num_prod}}"/>
                                    </td>
                                    <td>{{ $res->lib_prod }}</td>
                                    <td align="center">
                                        <?php if($flagValide != true) { ?>
                                            <input type="text" class="form-control" name="qte_lbr/{{ $res->code_prod }}" value="{{$res->qte_lbr}}"/>
                                        <?php }else{ ?>
                                            {{ $res->qte_lbr }}
                                        <?php } ?></td>
                                    <td align="right">
                                        <?php if($flagValide != true) { ?>
                                            <input type="text" class="form-control" name="prix_ht_lbr/{{ $res->code_prod }}" value="{{$res->prix_ht_lbr}}"/>
                                        <?php }else{ ?>
                                            {{ number_format($res->prix_ht_lbr,'0',',','.') }}
                                        <?php } ?>
                                        </td>
                                    <td align="right">{{ number_format($res->prix_tva_lbr,'0',',','.')}}</td>
                                    <td align="right">{{ number_format($res->prix_ttc_lbr,'0',',','.')}}</td>
                                    <td align="right">{{ number_format($res->tot_ht_lbr,'0',',','.')}}</td>
                                    <td align="right">{{ number_format($res->tot_tva_lbr,'0',',','.')}}</td>
                                    <td align="right">{{ number_format($res->tot_ttc_lbr,'0',',','.')}}</td>
                                    <td align="right">
                                        <input type="text" class="form-control" name="prix_vente/{{ $res->code_prod }}" value="{{$res->prix_ht}}"/>
                                    </td>
                                    <td align="right">{{ number_format($res->prix_ttc,'0',',','.')}}</td>
                                    <td align="right">{{ $res->taux_marque }}</td>
                                    <td align="center">
                                        <?php if($flagValide != true) { ?>
                                      <button type="submit" name="action" value="Modifier" class="btn btn-success btn-xs btn-clean btn-icon">
                                                <i class="la la-file"></i>
                                            </button>
                                        <!-- <a href="{{ route($titreRoute.'delete',\App\Helpers\Crypt::UrlCrypt($res->num_lbr)) }}"
                                           class="btn btn-danger btn-xs btn-clean btn-icon"
                                           title="Suprimer"> <i class="la la-trash"></i> </a>-->
                                    <?php } ?>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                        {!! Form::close() !!}
                    </div>
                </div>

                <!--end::Card-->
            </div>


            <!--end::Container-->
        </div>
        <!--end::Entry-->
    </div>


@endsection
