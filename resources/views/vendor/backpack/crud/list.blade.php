@extends('backpack::layout')

@section('header')
	<section class="content-header">
	  <h1>
	    <span class="text-capitalize">{{ $crud->entity_name_plural }}</span>
	    <small>{{ trans('backpack::crud.all') }} <span>{{ $crud->entity_name_plural }}</span> {{ trans('backpack::crud.in_the_database') }}.</small>
	  </h1>
	  <ol class="breadcrumb">
	    <li><a href="{{ url(config('backpack.base.route_prefix'), 'dashboard') }}">{{ trans('backpack::crud.admin') }}</a></li>
	    <li><a href="{{ url($crud->route) }}" class="text-capitalize">{{ $crud->entity_name_plural }}</a></li>
	    <li class="active">{{ trans('backpack::crud.list') }}</li>
	  </ol>
	</section>
@endsection

@section('content')
<!-- Default box -->
  <div class="row">

    <!-- THE ACTUAL CONTENT -->
    <div class="col-md-12">
      <div class="box">
        <div class="box-header {{ $crud->hasAccess('create')?'with-border':'' }}">

          @include('crud::inc.button_stack', ['stack' => 'top'])

          <div id="datatable_button_stack" class="pull-right text-right hidden-xs"></div>
        </div>

        <div class="box-body overflow-hidden">

        {{-- Backpack List Filters --}}
        @if ($crud->filtersEnabled())
          @include('crud::inc.filters_navbar')
        @endif

        <table id="crudTable" class="table table-striped table-hover display responsive nowrap" cellspacing="0">
            <thead>
              <tr>
                {{-- Table columns --}}
                @foreach ($crud->columns as $column)
                  <th {{ isset($column['orderable']) ? 'data-orderable=' .var_export($column['orderable'], true) : '' }}>
                    {{ $column['label'] }}
                  </th>
                @endforeach

                @if ( $crud->buttons->where('stack', 'line')->count() )
                  <th data-orderable="false">{{ trans('backpack::crud.actions') }}</th>
                @endif
              </tr>
            </thead>
            <tbody>
            </tbody>
            <tfoot>
              <tr>
                {{-- Table columns --}}
                @foreach ($crud->columns as $column)
                  <th>{{ $column['label'] }}</th>
                @endforeach

                @if ( $crud->buttons->where('stack', 'line')->count() )
                  <th>{{ trans('backpack::crud.actions') }}</th>
                @endif
              </tr>
            </tfoot>
          </table>

        </div><!-- /.box-body -->

        @include('crud::inc.button_stack', ['stack' => 'bottom'])

      </div><!-- /.box -->
    </div>

  </div>

@endsection

@section('after_styles')
  <!-- DATA TABLES -->
  <link href="https://cdn.datatables.net/1.10.16/css/dataTables.bootstrap.min.css" rel="stylesheet" type="text/css" />
  <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.2.1/css/responsive.bootstrap.min.css">

  <link rel="stylesheet" href="{{ asset('vendor/backpack/crud/css/crud.css') }}">
  <link rel="stylesheet" href="{{ asset('vendor/backpack/crud/css/form.css') }}">
  <link rel="stylesheet" href="{{ asset('vendor/backpack/crud/css/list.css') }}">

  <!-- CRUD LIST CONTENT - crud_list_styles stack -->
  @stack('crud_list_styles')
@endsection

@section('after_scripts')
	@include('crud::inc.datatables_logic')

  <script src="{{ asset('vendor/backpack/crud/js/crud.js') }}"></script>
  <script src="{{ asset('vendor/backpack/crud/js/form.js') }}"></script>
  <script src="{{ asset('vendor/backpack/crud/js/list.js') }}"></script>

  <!-- CRUD LIST CONTENT - crud_list_scripts stack -->
  @stack('crud_list_scripts')
@endsection
