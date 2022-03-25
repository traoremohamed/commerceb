@extends('layouts.backLayout.designadmin')
@section('content')

    @php($Module='Achat')
    @php($titre='Liste des commandes fournisseurs')
    @php($soustitre='Modifier commande fournisseur')
    @php($titreRoute='comfour')
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
                            <h3 class="card-label">{{$soustitre}} / N° cmd : {{$comFour->num_comf}}</h3>
                        </div>
                    </div>
                    {!! Form::open(  ['route' => ['comfouredit',\App\Helpers\Crypt::UrlCrypt($comFour->num_comf)], 'method' => 'post' ] ) !!}
                    @csrf
                    <div class="card-body">
                        <div class="form-group row">
                            <div class="col-lg-3">
                                <label>Fournisseur :</label>
                                <select {{$disabled}} name="num_fourn" id="num_fourn" required="required"
                                        class="form-control">
                                    <?php echo $TFournisseurList; ?>
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
                                {!! Form::text('', $comFour->created_at, array('placeholder' => 'Date de création ','class' => 'form-control','disabled' => 'disabled')) !!}
                                <span class="form-text text-muted">  </span>
                            </div>
                            <div class="col-lg-2">
                                <label>Date de validation :</label>
                                {!! Form::text( '',$comFour->date_val_comf, ['placeholder' => 'Date de validation','class' => 'form-control','disabled' => 'disabled' ]) !!}
                                <span class="form-text text-muted">  </span>
                            </div>
                            <div class="col-lg-2">
                                <label>Montant total :</label>
                                {!! Form::text('', $comFour->prix_ttc_comf, array('placeholder' => 'Montant total ','class' => 'form-control','disabled' => 'disabled' )) !!}
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
                                    <a class="btn btn-sm btn-secondary" href="{{ route('comfour') }}"> Retour</a>
                                    <a class="btn btn-sm btn-info"
                                     onclick="NewWindow('{{ route('etatcomfour',\App\Helpers\Crypt::UrlCrypt($comFour->num_comf))}}','',screen.width,screen.height,'yes','center',1);">
                                    Aper&ccedil;u</a>
                                    <?php if($flagValide != true) { ?>
                                    <button type="submit" name="action" value="Enregistrer"
                                            class="btn btn-sm btn-primary">Enregistrer
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
                        {!! Form::open(  ['route' => ['comfouredit',\App\Helpers\Crypt::UrlCrypt($comFour->num_comf)], 'method' => 'post' ] ) !!}

                        <table class="table table-bordered table-hover table-checkable"
                               style="margin-top: 13px !important">
                            <thead>
                            <tr>
                                <th>Code</th>
                                <th>Produits</th>
                                <th>Quantité</th>
                                <th>Montant ht</th>
                                <th>Montant ttc</th>
                                <th>Total ht</th>
                                <th>Total ttc</th>
                                <th>Action</th>
                            </tr>
                            <?php if($flagValide != true) { ?>
                            <tr>
                                <th></th>
                                <th><select name="num_prod"  class="form-control " id="kt_select2_1">
                                        <?php echo $ProduitList; ?>
                                    </select>
                                </th>
                                <th><input value="1" type="number" class="form-control" name="qte_lcomfour"
                                           id="qte_lcomfour"></th>
                                <th><input value="0" type="number" class="form-control" name="prix_ttc_lcomfour"
                                           id="prix_ttc_lcomfour"></th>
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
                            @foreach ($ligneComfour as $key => $res)
                                <tr>
                                    <td>
                                        {{ $res->code_prod }}
                                        <input type="hidden" class="form-control" name="num_prod/{{ $res->code_prod }}" value="{{$res->num_prod}}"/>
                                    </td>
                                    <td>{{ $res->lib_prod }}</td>
                                    <td align="center">
                                        <?php if($flagValide != true) { ?>
                                            <input type="text" class="form-control" name="qte_lcomfour/{{ $res->code_prod }}" value="{{$res->qte_lcomfour}}"/>
                                        <?php }else{ ?>
                                            {{ $res->qte_lcomfour }}
                                        <?php } ?>
                                    </td>
                                    <td align="right">
                                        <?php if($flagValide != true) { ?>
                                            <input type="text" class="form-control" name="prix_ttc_lcomfour/{{ $res->code_prod }}" value="{{$res->prix_ht_lcomfour}}"/>
                                        <?php }else{ ?>
                                            {{ number_format($res->prix_ht_lcomfour,'0',',','.') }}
                                        <?php } ?>

                                    </td>
                                    <td>
                                        {{ number_format($res->prix_ttc_lcomfour,'0',',','.') }}
                                    </td>
                                    <td align="right">{{ number_format($res->prix_ht_lcomfour * $res->qte_lcomfour,'0',',','.')}}</td>
                                    <td align="right">{{ number_format($res->prix_ttc_lcomfour * $res->qte_lcomfour,'0',',','.')}}</td>
                                    <td align="center">
                                        <?php if($flagValide != true) { ?>
                                            <button type="submit" name="action" value="Modifier" class="btn btn-success btn-xs btn-clean btn-icon">
                                                <i class="la la-file"></i>
                                            </button>

                                        <a href="{{ route($titreRoute.'delete',\App\Helpers\Crypt::UrlCrypt($res->num_lcomfour)) }}"
                                           class="btn btn-danger btn-xs btn-clean btn-icon"
                                           title="Suprimer"> <i class="la la-trash"></i> </a>
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
