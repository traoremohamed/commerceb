@extends('layouts.backLayout.designadmin')
@section('content')

    @php($Module='Vente')
    @php($titre='Liste des Proformats client')
    @php($soustitre='Modifier Proformat client')
    @php($titreRoute='comclient')
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
                            <h3 class="card-label">{{$soustitre}} / N° cmd : {{$comCli->code_comc}}</h3>
                        </div>
                    </div>
                    {!! Form::open(  ['route' => ['comclientedit',\App\Helpers\Crypt::UrlCrypt($comCli->num_comc)], 'method' => 'post' ] ) !!}
                    @csrf
                    <div class="card-body">
                        <div class="form-group row">
                            <div class="col-lg-3">
                                <label>Client :</label>
                                <select {{$disabled}} name="num_cli" id="num_cli" required="required"
                                        class="form-control">
                                    <?php echo $TClientList; ?>
                                </select>
                                <span class="form-text text-muted">  </span>
                            </div>
                            <div class="col-lg-2">
                                <label>Agence :</label>
                                <select {{$disabled}} name="num_agce" id="num_agce" required="required"
                                        class="form-control">
                                    <?php echo $AgenceList; ?>
                                </select>
                                <span class="form-text text-muted">  </span>
                            </div>
                            <div class="col-lg-2">
                                <label>Date de création :</label>
                                {!! Form::text('', $comCli->date_cre_comc, array('placeholder' => 'Date de création ','class' => 'form-control','disabled' => 'disabled')) !!}
                                <span class="form-text text-muted">  </span>
                            </div>
                            <div class="col-lg-2">
                                <label>Date de validation :</label>
                                {!! Form::text( '',$comCli->date_val_comc, ['placeholder' => 'Date de validation','class' => 'form-control','disabled' => 'disabled' ]) !!}
                                <span class="form-text text-muted">  </span>
                            </div>
                            <div class="col-lg-2">
                                <label>Montant total :</label>
                                {!! Form::text('', $comCli->prix_ttc_comc, array('placeholder' => 'Montant total ','class' => 'form-control','disabled' => 'disabled' )) !!}
                                <span class="form-text text-muted">  </span>
                            </div>
                            <div class="col-lg-1">
                                <label>Statut :</label>
                                <?php if($flagValide == true and $flagAnnule == false){ ?>
                                <span class="label label-lg font-weight-bold label-success label-inline"> Validé</span>
                                <?php  }elseif($flagValide == false and $flagAnnule == false){?>
                                <span class="label label-lg font-weight-bold label-default label-inline">En cours</span>
                                <?php } elseif ( $flagAnnule == true) { ?>
                                <span class="label label-lg font-weight-bold label-default label-inline">Annulé</span>
                                <?php }  ?>
                                <span class="form-text text-muted">  </span>
                            </div>
                        </div>
                    </div>

                    <div class="card-footer">
                        <div class="row">
                            <div class="col-lg-12 text-right">
                                @can('role-create')
                                    <a class="btn btn-sm btn-secondary" href="{{ route('comclient') }}"> Retour</a>
                                    <a class="btn btn-sm btn-info"
                                       onclick="NewWindow('{{ route('etatcomcli',\App\Helpers\Crypt::UrlCrypt($comCli->num_comc))}}','',screen.width,screen.height,'yes','center',1);">
                                        Aper&ccedil;u</a>
                                    <?php if($flagValide != true) { ?>

                                    <?php if($ligneComCli->isEmpty()){ ?>
                                            <button type="submit" name="action" value="Enregistrer"
                                                    class="btn btn-sm btn-primary">Enregistrer
                                            </button>
                                    <?php }else{

                                        }?>

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
                        {!! Form::open(  ['route' => ['comclientedit',\App\Helpers\Crypt::UrlCrypt($comCli->num_comc)], 'method' => 'post' ] ) !!}

                        <table class="table table-bordered table-hover table-checkable"
                               style="margin-top: 13px !important">
                            <thead>
                            <tr>
                                <th>Code</th>
                                <th>Code barre</th>
                                <th>Produits</th>
                                <th>Quantité</th>
                                <th>Remise en %</th>
                                <th>Montant ht</th>
                                <th>Montant ttc</th>
                                <th>Total ttc</th>
                                <th>Action</th>
                            </tr>
                            <?php if($flagValide != true) { ?>
                            <tr>
                                <th></th>
                                <th><input min="0"  type="number" class="form-control" name="code_barre_prod"
                                           id="code_barre_prod"></th>
                                <th><select name="num_prod"   class="form-control " id="kt_select2_1">
                                        <?php echo $ProduitList; ?>
                                    </select>
                                </th>
                                <th><input min="0" value="1" type="number" class="form-control" name="qte_lcomc"
                                           id="qte_lcomc"></th>
                                <th><input min="0" value="0" type="number" class="form-control" name="remise_lcomc"
                                           id="remise_lcomc"></th>

                                <th></th>
                                <th></th>
                                <th></th>
                                <td align="center">

                                    <button type="submit" name="action" value="Ajouter"
                                            class="btn btn-sm btn-primary">Ajouter
                                    </button>

                                </td>
                            </tr>
                            <?php } ?>
                            </thead>
                            <tbody>
                            <?php
                            $i=0;
                            $tothtlcomfour=0;
                            $tottvalcomfour=0;
                            $totttclcomfour=0;
                            foreach ($ligneComCli as $key => $res):
                            $tothtlcomfour+= $res->tot_ht_lcomc;
                            $tottvalcomfour+= $res->tot_tva_lcomc;
                            $totttclcomfour+= $res->tot_ttc_lcomc;
                            $i++;

                            ?>
                                <tr>
                                    <td>{{ $res->code_prod }}</td>
                                    <td>{{ $res->code_barre_prod }}</td>
                                    <td>{{ $res->lib_prod }}</td>
                                    <td align="center">{{ $res->qte_lcomc }}</td>
                                    <td align="center">{{ $res->remise_lcomc }} %</td>
                                    <td align="right">{{ number_format($res->prix_ht_lcomc,'0',',','.') }}</td>
                                    <td align="right">{{ number_format($res->prix_ttc_lcomc,'0',',','.') }}</td>
                                    <td align="right">{{ number_format($res->tot_ttc_lcomc ,'0',',','.')}}</td>
                                    <td align="center">
                                        <?php if($flagValide != true) { ?>
                                        <a href="{{ route($titreRoute.'delete',\App\Helpers\Crypt::UrlCrypt($res->num_bl_lcomc)) }}"
                                           class="btn btn-danger btn-xs btn-clean btn-icon"
                                           title="Suprimer"> <i class="la la-trash"></i> </a>
                                    <?php } ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                            <tr>
                                <td height="41" colspan="9">&nbsp;</td>
                            </tr>
                            <tr>
                                <td colspan="6" nowrap="nowrap" rowspan="4"></td>
                                <td align="right">Sous total :</td>
                                <td align="right">{{ number_format($tothtlcomfour ,'0',',','.')}}</td>
                                <td align="center">

                                </td>
                            </tr>
                            <tr>

                                <td align="right" nowrap="nowrap">Tva :</td>
                                <td align="right">{{ number_format($tottvalcomfour ,'0',',','.')}}</td>
                                <td align="center">

                                </td>
                            </tr>
                            <tr>

                                <td align="right" nowrap="nowrap">Total TTC XOF:</td>
                                <td align="right">{{ number_format($totttclcomfour ,'0',',','.')}}</td>
                                <td align="center">

                                </td>
                            </tr>
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
