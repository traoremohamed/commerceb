@extends('layouts.backLayout.designadmin')
@section('content')

    @php($Module='Vente')
    @php($titre='Liste des factures')
    @php($soustitre='Modifier facture')
    @php($titreRoute='facture')
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
                            <h3 class="card-label">{{$soustitre}} / N° Facture : {{$Result->code_fact}} / N° Recept.
                                : {{$Result->num_bl}} / N° commande : {{$Result->code_comc}}</h3>
                        </div>
                    </div>
                    {!! Form::open(  ['route' => ['factureedit',\App\Helpers\Crypt::UrlCrypt($Result->num_fact)], 'method' => 'post' ] ) !!}
                    @csrf
                    <div class="card-body">
                        <div class="form-group row">
                            <div class="col-lg-3">
                                <label>Client :</label>
                                {!! Form::text('', $Result->code_cli .' : '. $Result->nom_cli .' ' . $Result->prenom_cli, array('placeholder' => 'Date de création ','class' => 'form-control','disabled' => 'disabled')) !!}
                                <span class="form-text text-muted">  </span>
                            </div>
                            <div class="col-lg-2">
                                <label>Agence :</label>
                                {!! Form::text('', $Result->lib_agce, array('placeholder' => 'Date de création ','class' => 'form-control','disabled' => 'disabled')) !!}

                                <span class="form-text text-muted">  </span>
                            </div>
                            <div class="col-lg-2">
                                <label>Date de création :</label>
                                {!! Form::text('', $Result->date_cre_fact, array('placeholder' => 'Date de création ','class' => 'form-control','disabled' => 'disabled')) !!}
                                <span class="form-text text-muted">  </span>
                            </div>
                            <div class="col-lg-2">
                                <label>Date de validation :</label>
                                {!! Form::text( '',$Result->date_val_fact, ['placeholder' => 'Date de validation','class' => 'form-control','disabled' => 'disabled' ]) !!}
                                <span class="form-text text-muted">  </span>
                            </div>
                            <div class="col-lg-2">
                                <label>Montant total :</label>
                                {!! Form::text('', $Result->prix_ttc_fact, array('placeholder' => 'Montant total ','class' => 'form-control','disabled' => 'disabled' )) !!}
                                <span class="form-text text-muted">  </span>
                            </div>
                            <div class="col-lg-1">
                                <label>Statut :</label>
                                <?php if($flagValide == true and $flagAnnule == false and $flagsolde == false){ ?>
                                <span class="label label-lg font-weight-bold label-success label-inline"> Validé</span>
                                <?php } elseif($flagValide == true and $flagAnnule == false and $flagsolde == true){ ?>
                                <span class="label label-lg font-weight-bold label-warning label-inline"> Soldé</span>
                                <?php  }elseif($flagValide == false and $flagAnnule == false and $flagsolde == false){?>
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
                                    <a class="btn btn-sm btn-secondary" href="{{ route('facture') }}"> Retour</a>
                                    <a class="btn btn-sm btn-info"
                                       onclick="NewWindow('{{ route('etatfacture',\App\Helpers\Crypt::UrlCrypt($Result->num_fact))}}','',screen.width,screen.height,'yes','center',1);">
                                        Aper&ccedil;u</a>

                                    <?php if($flagValide != true and $flagAnnule != true) { ?>
                                    <button type="submit" name="action" value="Annuler"
                                            class="btn btn-sm btn-danger">Annuler
                                    </button>
                                    <button type="submit" name="action" value="Valider" class="btn btn-sm btn-success">
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
                <div class="row">
                    <div class="col-xl-7">
                        <div class="card card-custom" style="width: 100%">
                            <div class="card-header">
                                <div class="card-title">
											<span class="card-icon">
												<i class="flaticon2-favourite text-primary"></i>
											</span>
                                    <h3 class="card-label">Liste des produits</h3>
                                </div>
                            </div>
                            <div class="card-body">

                                {!! Form::open(  ['route' => ['factureedit',\App\Helpers\Crypt::UrlCrypt($Result->num_fact)], 'method' => 'post' ] ) !!}

                                <table class="table table-bordered table-hover table-checkable"
                                       style="margin-top: 13px !important">
                                    <thead>
                                    <tr>
                                        <th>Code</th>
                                        <th>Produits</th>
                                        <th>Quantité</th>
                                        <th>Remise %</th>
                                        <th>Mtt ht</th>
                                        <th>Mtt ttc</th>
                                        <th>Total ttc</th>
                                    </tr>

                                    </thead>
                                    <tbody>
                                    @foreach ($ligneResult as $key => $res)
                                        <tr>
                                            <td>{{ $res->code_prod }}</td>
                                            <td>{{ $res->lib_prod }}</td>
                                            <td align="center">{{ $res->qte_lfact }}</td>
                                            <td align="center">{{ $res->remise_lfact }} %</td>
                                            <td align="right">{{ number_format($res->prix_ht_lfact,'0',',','.') }}</td>
                                            <td align="right">{{ number_format($res->prix_ttc_lfact,'0',',','.') }}</td>
                                            <td align="right">{{ number_format($res->tot_ttc_lfact,'0',',','.')}}</td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                                {!! Form::close() !!}
                            </div>
                        </div>
                        <!--end::Card-->
                    </div>
                    <div class="col-xl-5">
                        <div class="card card-custom">
                            <div class="card-header">
                                <div class="card-title">
											<span class="card-icon">
												<i class="flaticon2-favourite text-primary"></i>
											</span>
                                    <h3 class="card-label">Reglement facture</h3>
                                </div>
                            </div>
                            <div class="card-body">
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
                                {!! Form::open(  ['route' => ['factureedit',\App\Helpers\Crypt::UrlCrypt($Result->num_fact)], 'method' => 'post' ] ) !!}
                                <input type="hidden" name="num_fact" id="num_fact" class="form-control"
                                       value="{{$Result->num_fact}}">
                                <table class="table table-bordered table-hover table-checkable"
                                       style="margin-top: 13px !important">
                                    <thead>
                                    <tr>
                                        <th>Date</th>
                                        <th>Mode de paiement</th>
                                        <th>Montant ttc</th>
                                        <th>Action</th>
                                    </tr>
                                    <?php if($flagsolde != true) { ?>
                                    <tr>
                                        <th></th>
                                        <th><select name="num_mpaie" class="form-control " id="num_mpaie">
                                                <?php  echo $ModepaieList; ?>
                                            </select>
                                        </th>
                                        <th><input min="0" value="0" type="number" class="form-control"
                                                   name="montant_ttc_reg"
                                                   id="montant_ttc_reg"></th>

                                        <td align="center">

                                            <button onclick='javascript:if (!confirm("Voulez-vous valider la proformat ?")) return false;' type="submit" name="action" value="Payer"
                                                    class="btn btn-sm btn-primary">Payer
                                            </button>

                                        </td>
                                    </tr>
                                    <?php } ?>
                                    </thead>
                                    <tbody>
                                    <?php    $totalMtt = 0; ?>
                                    @foreach ($ligneResultReg as $key => $res)
                                        <?php    $totalMtt += $res->montant_ttc_reg; ?>
                                        <tr>
                                            <td align="center">{{ $res->created_at }} </td>
                                            <td align="center">{{ $res->lib_mpaie }} </td>
                                            <td align="right">{{ number_format($res->montant_ttc_reg,'0',',','.')}}</td>
                                            <td></td>
                                        </tr>

                                    @endforeach
                                    <tr>
                                        <td colspan="4"> &nbsp;</td>
                                    </tr>
                                    <tr>
                                        <th colspan="2">Reste à payer</th>
                                        <td align="right">
                                            <span class="label label-lg font-weight-bold label-warning label-inline">
                                            {{ number_format($Result->prix_ttc_fact-$totalMtt,'0',',','.')}}
                                            </span>
                                        </td>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <th colspan="2">Déja payé</th>
                                        <td align="right">
                                             <span class="label label-lg font-weight-bold label-success label-inline">
                                            {{ number_format($totalMtt,'0',',','.')}}
                                            </span>
                                        </td>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <th colspan="2">Montant de la facture</th>
                                        <td align="right">
                                               <span class="label label-lg font-weight-bold label-info label-inline">
                                            {{ number_format($Result->prix_ttc_fact,'0',',','.')}}
                                            </span></td>
                                        <td></td>
                                    </tr>
                                    </tbody>
                                </table>
                                {!! Form::close() !!}
                            </div>
                        </div>
                        <!--end::Card-->
                    </div>
                </div>


                <br>

            </div>
            <!--end::Container-->
        </div>
        <!--end::Entry-->
    </div>


@endsection
