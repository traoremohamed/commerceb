@extends('layouts.backLayout.designadmin')
@section('content')

    @php($Module='vente')
    @php($titre='Liste des bons de livraisons')
    @php($soustitre='Modifier bon de livraison')
    @php($titreRoute='bonlivraison')
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
                            <h3 class="card-label">{{$soustitre}} / N° reception : {{$Result->num_bl}}</h3>
                        </div>
                    </div>
                    {!! Form::open(  ['route' => ['bonlivraisonedit',\App\Helpers\Crypt::UrlCrypt($Result->num_bl)], 'method' => 'post' ] ) !!}
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
                                {!! Form::text('', $Result->date_cre_bl, array('placeholder' => 'Date de création ','class' => 'form-control','disabled' => 'disabled')) !!}
                                <span class="form-text text-muted">  </span>
                            </div>
                            <div class="col-lg-2">
                                <label>Date de validation :</label>
                                {!! Form::text( '',$Result->date_val_bl, ['placeholder' => 'Date de validation','class' => 'form-control','disabled' => 'disabled' ]) !!}
                                <span class="form-text text-muted">  </span>
                            </div>
                            <div class="col-lg-2">
                                <label>Montant total :</label>
                                {!! Form::text('', $Result->prix_ttc_bl, array('placeholder' => 'Montant total ','class' => 'form-control','disabled' => 'disabled' )) !!}
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
                                    <a class="btn btn-sm btn-secondary" href="{{ route('bonlivraison') }}"> Retour</a>
                                    <a class="btn btn-sm btn-info"
                                       onclick="NewWindow('{{ route('etatblcli',\App\Helpers\Crypt::UrlCrypt($Result->num_bl))}}','',screen.width,screen.height,'yes','center',1);">
                                        Aper&ccedil;u</a>
                                    <?php if($flagValide == true ) { ?>
                                    <a class="btn btn-sm btn-info" href="{{ route('facture'.'edit',\App\Helpers\Crypt::UrlCrypt($Result->num_fact)) }}"> Voir la facture</a>
                                    <?php } ?>
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
                <div class="card card-custom" style="width: 100%">
                    <div class="card-body">
                        {!! Form::open(  ['route' => ['bonlivraisonedit',\App\Helpers\Crypt::UrlCrypt($Result->num_bl)], 'method' => 'post' ] ) !!}

                        <table class="table table-bordered table-hover table-checkable"
                               style="margin-top: 13px !important">
                            <thead>
                            <tr>
                                <th>Code</th>
                                <th>Produits</th>
                                <th>Quantité</th>
                                <th>Remise en %</th>
                                <th>Montant ht</th>
                                <th>Montant ttc</th>
                                <th>Total ttc</th>
                                <th>Action</th>
                            </tr>

                            </thead>
                            <tbody>
                            @foreach ($ligneResult as $key => $res)
                                <tr>
                                    <td>
                                        {{ $res->code_prod }}
                                        <input type="hidden" class="form-control" name="num_lbl/{{ $res->code_prod }}" value="{{$res->num_lbl}}"/>
                                    </td>
                                    <td>{{ $res->lib_prod }}</td>
                                    <td align="center">
                                        {{$res->qte_lbl}}
                                    </td>
                                    <td align="center">{{ $res->remise_lbl }} %</td>
                                    <td align="right">{{ number_format($res->prix_ht_lbl,'0',',','.') }}</td>
                                    <td align="right">{{ number_format($res->prix_ttc_lbl,'0',',','.') }}</td>
                                    <td align="right">{{ number_format($res->tot_ttc_lbl,'0',',','.')}}</td>
                                    <td align="center">
                                     <?php if($flagValide != true) { ?>
                                         <!--<button type="submit" name="action" value="Modifier" class="btn btn-success btn-xs btn-clean btn-icon">
                                             <i class="la la-file"></i>
                                         </button>-->

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
