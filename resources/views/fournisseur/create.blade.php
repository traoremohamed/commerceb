@extends('layouts.backLayout.designadmin')
@section('content')

    @php($Module='Parametre')
    @php($titre='Liste des fournisseurs')
    @php($soustitre='Ajouter un fournisseur')
    @php($titreRoute='fournisseur')


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
                            <h3 class="card-label">{{$soustitre}}</h3>
                        </div>
                    </div>
                    {!! Form::open(array('route' => $titreRoute.'.store','method'=>'POST')) !!}
                    @csrf
                    <div class="card-body">
                        <div class="form-group row">
                            <div class="col-lg-4">
                                <label>Libellé :</label>
                                {!! Form::text('lib_fourn',  null, ['placeholder' => 'Libellé ','class' => 'form-control','required' => 'required']) !!}
                                <span class="form-text text-muted">  </span>
                            </div>

                            <div class="col-lg-4">
                                <label>Tél :</label>
                                {!! Form::text('tel_fourn', null, array('placeholder' => 'Tél','class' => 'form-control')) !!}
                                <span class="form-text text-muted">  </span>
                            </div>
                            <div class="col-lg-4">
                                <label>Cel :</label>
                                {!! Form::text('cel_fourn', null, array('placeholder' => 'Cel','class' => 'form-control')) !!}
                                <span class="form-text text-muted">  </span>
                            </div>

                        </div>
                        <div class="form-group row">
                            <div class="col-lg-4">
                                <label>Fax :</label>
                                {!! Form::text('fax_fourn', null, array('placeholder' => 'Fax ','class' => 'form-control')) !!}
                                <span class="form-text text-muted">  </span>
                            </div>

                            <div class="col-lg-4">
                                <label>E-mail :</label>
                                {!! Form::text('mail_fourn', null, array('placeholder' => 'E-mail','class' => 'form-control')) !!}
                                <span class="form-text text-muted">  </span>
                            </div>
                            <div class="col-lg-4">
                                <label>Adresse :</label>
                                {!! Form::text('adr_fourn', null, array('placeholder' => 'Adresse','class' => 'form-control')) !!}
                                <span class="form-text text-muted">  </span>
                            </div>

                        </div>
                        <div class="form-group row">
                            <div class="col-lg-4">
                                <label>RCCM :</label>
                                {!! Form::text('rccm_fourn', null, array('placeholder' => 'RCCM ','class' => 'form-control')) !!}
                                <span class="form-text text-muted">  </span>
                            </div>

                            <div class="col-lg-4">
                                <label>Compte contribuable :</label>
                                {!! Form::text('cpte_contr_fourn', null, array('placeholder' => 'Compte contribuable','class' => 'form-control')) !!}
                                <span class="form-text text-muted">  </span>
                            </div>
                            <div class="col-lg-2">
                                <label>Statut :</label>
                                {!! Form::select('flag_fourn', [true => 'Actif', false => 'Inactif',], true ,['class' => 'form-control'] )  !!}
                                <span class="form-text text-muted">  </span>
                            </div>
                            <div class="col-lg-2">
                                <label>Tva :</label>
                                {!! Form::select('flag_tva_fourn', [true => 'OUI', false => 'NON',], true ,['class' => 'form-control'] )  !!}
                                <span class="form-text text-muted">  </span>
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


                <!--end::Card-->
            </div>
            <!--end::Container-->
        </div>
        <!--end::Entry-->
    </div>
    {!! Form::close() !!}

@endsection
