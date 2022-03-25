@extends('layouts.backLayout.designadmin')
@section('content')
    @php($Module='Parametre')
    @php($titre='Liste des produits')
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
                        <h5 class="text-dark font-weight-bold my-1 mr-5">{{$titre}}</h5>
                        <!--end::Page Title-->
                        <!--begin::Breadcrumb-->
                        <ul class="breadcrumb breadcrumb-transparent breadcrumb-dot font-weight-bold p-0 my-2 font-size-sm">
                            <li class="breadcrumb-item">
                                <a class="text-muted">{{$Module}}</a>
                            </li>
                            <li class="breadcrumb-item">
                                <a class="text-muted">{{$titre}}</a>
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

            <!--end::Notice-->
                <!--begin::Card-->
                <div class="card card-custom" style="width: 100%">
                    <div class="card-header">
                        <div class="card-title">
    											<span class="card-icon">
    												<i class="flaticon2-favourite text-primary"></i>
    											</span>
                            <h3 class="card-label">{{$titre}}</h3>
                        </div>
                        <div class="card-toolbar">
                            <!--begin::Dropdown-->

                            <!--end::Dropdown-->
                            <!--begin::Button-->
                            @can('role-create')
                                <a href="{{ route($titreRoute.'.create') }}"
                                   class="btn btn-sm btn-primary font-weight-bolder">
                                    <i class="la la-plus"></i>Ajouter un {{$titreRoute}}</a>
                        @endcan
                        <!--end::Button-->
                        </div>
                    </div>
                    <div class="card-body">
                        <!--begin: Datatable-->
                        <table class="table table-bordered table-hover table-checkable" id="kt_datatable"
                               style="margin-top: 13px !important">
                            <thead>
                            <tr>
                                <th>No</th>
                                <th>Code</th>
                                <th>Code barre</th>
                                <th>Libellé
                                <th>Sous famille</th>
                                <th>Montant ttc</th>
                                <th>Montant ht</th>
                                <th>Taux de marque</th>
                                <th>TVA </th>
                                <th>Statut</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach ($Resultat as $key => $res)
                                <tr>
                                    <td>{{ $res->num_prod }}</td>
                                    <td>{{ $res->code_prod }}</td>
                                    <td>{{ $res->code_barre_prod }}</td>
                                    <td>{{ $res->lib_prod  }}</td>
                                    <td>{{ $res->lib_sousfam  }}</td>
                                     <td align="rigth"><?php echo number_format($res->prix_ttc); ?></td>
                                     <td align="rigth"><?php echo number_format($res->prix_ht); ?></td>
                                     <td>{{ $res->prix_revient_prod / $res->prix_ttc  }}</td>
                                    <td align="center">
                                        <?php if($res->flag_tva_prod == 1){ ?>
                                        <span  class="label label-lg font-weight-bold label-success label-inline"> OUI</span>
                                        <?php  }else{?>
                                        <span class="label label-lg font-weight-bold label-danger label-inline">NON</span>
                                        <?php } ?>
                                    </td>
                                    <td align="center">
                                        <?php if($res->flag_prod == true){ ?>
                                        <span  class="label label-lg font-weight-bold label-success label-inline"> Actif</span>
                                        <?php  }else{?>
                                        <span class="label label-lg font-weight-bold label-danger label-inline">Inactif</span>
                                        <?php } ?>
                                    </td>
                                    <td align="center">
                                        @can($titreRoute.'-edit')
                                            <a href="{{ route($titreRoute.'.edit',$res->num_prod) }}"
                                               class="btn btn-warning btn-xs btn-clean btn-icon"
                                               title="Modifier"> <i class="la la-edit"></i> </a>
                                        @endcan
                                        @can($titreRoute.'-delete')
                                            {!! Form::open(['method' => 'DELETE','route' => [$titreRoute.'.destroy', $res->num_prod],'style'=>'display:inline']) !!}
                                            {!! Form::submit('Delete', ['class' => 'btn btn-danger']) !!}
                                            {!! Form::close() !!}
                                        @endcan
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                        <!--end: Datatable-->
                    </div>
                </div>
                <!--end::Card-->
            </div>
            <!--end::Container-->
        </div>
        <!--end::Entry-->

    </div>

@endsection
