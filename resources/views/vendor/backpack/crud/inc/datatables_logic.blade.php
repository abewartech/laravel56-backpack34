  <!-- DATA TABLES SCRIPT -->
  <script src="https://cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js" type="text/javascript"></script>
  <script src="https://cdn.datatables.net/1.10.16/js/dataTables.bootstrap.min.js" type="text/javascript"></script>
  <script src="https://cdn.datatables.net/responsive/2.2.1/js/dataTables.responsive.min.js"></script>
  <script src="https://cdn.datatables.net/responsive/2.2.1/js/responsive.bootstrap.min.js"></script>

  <script>
    var crud = {
      exportButtons: JSON.parse('{!! json_encode($crud->export_buttons) !!}'),
      functionsToRunOnDataTablesDrawEvent: [],
      addFunctionToDataTablesDrawEventQueue: function (functionName) {
          if (this.functionsToRunOnDataTablesDrawEvent.indexOf(functionName) == -1) {
          this.functionsToRunOnDataTablesDrawEvent.push(functionName);
        }
      },
      responsiveToggle: function(dt) {
          $(dt.table().header()).find('th').toggleClass('all');
          dt.responsive.rebuild();
          dt.responsive.recalc();
      },
      executeFunctionByName: function(str, args) {
        var arr = str.split('.');
        var fn = window[ arr[0] ];

        for (var i = 1; i < arr.length; i++)
        { fn = fn[ arr[i] ]; }
        fn.apply(window, args);
      },
      dataTableConfiguration: {
        responsive: {
            details: {
                display: $.fn.dataTable.Responsive.display.modal( {
                    header: function ( row ) {
                        var data = row.data();
                        return data[0];
                    }
                } ),
                renderer: function ( api, rowIdx, columns ) {
                  var data = $.map( columns, function ( col, i ) {
                      return '<tr data-dt-row="'+col.rowIndex+'" data-dt-column="'+col.columnIndex+'">'+
                                '<td><strong>'+col.title.trim()+':'+'<strong></td> '+
                                '<td>'+col.data+'</td>'+
                              '</tr>';
                  } ).join('');

                  return data ?
                      $('<table class="table table-striped table-condensed m-b-0">').append( data ) :
                      false;
                },
            }
        },
        autoWidth: false,
        pageLength: {{ $crud->getDefaultPageLength() }},
        lengthMenu: @json($crud->getPageLengthMenu()),
        /* Disable initial sort */
        aaSorting: [],
        language: {
              "emptyTable":     "{{ trans('backpack::crud.emptyTable') }}",
              "info":           "{{ trans('backpack::crud.info') }}",
              "infoEmpty":      "{{ trans('backpack::crud.infoEmpty') }}",
              "infoFiltered":   "{{ trans('backpack::crud.infoFiltered') }}",
              "infoPostFix":    "{{ trans('backpack::crud.infoPostFix') }}",
              "thousands":      "{{ trans('backpack::crud.thousands') }}",
              "lengthMenu":     "{{ trans('backpack::crud.lengthMenu') }}",
              "loadingRecords": "{{ trans('backpack::crud.loadingRecords') }}",
              "processing":     "<img src='{{ asset('vendor/backpack/crud/img/ajax-loader.gif') }}' alt='{{ trans('backpack::crud.processing') }}'>",
              "search":         "{{ trans('backpack::crud.search') }}",
              "zeroRecords":    "{{ trans('backpack::crud.zeroRecords') }}",
              "paginate": {
                  "first":      "{{ trans('backpack::crud.paginate.first') }}",
                  "last":       "{{ trans('backpack::crud.paginate.last') }}",
                  "next":       "<span class='hidden-xs hidden-sm'>{{ trans('backpack::crud.paginate.next') }}</span><span class='hidden-md hidden-lg'>></span>",
                  "previous":   "<span class='hidden-xs hidden-sm'>{{ trans('backpack::crud.paginate.previous') }}</span><span class='hidden-md hidden-lg'><</span>"
              },
              "aria": {
                  "sortAscending":  "{{ trans('backpack::crud.aria.sortAscending') }}",
                  "sortDescending": "{{ trans('backpack::crud.aria.sortDescending') }}"
              },
              "buttons": {
                  "copy":   "{{ trans('backpack::crud.export.copy') }}",
                  "excel":  "{{ trans('backpack::crud.export.excel') }}",
                  "csv":    "{{ trans('backpack::crud.export.csv') }}",
                  "pdf":    "{{ trans('backpack::crud.export.pdf') }}",
                  "print":  "{{ trans('backpack::crud.export.print') }}",
                  "colvis": "{{ trans('backpack::crud.export.column_visibility') }}"
              },
          },
          processing: true,
          serverSide: true,
          ajax: {
              "url": "{!! url($crud->route.'/search').'?'.Request::getQueryString() !!}",
              "type": "POST"
          },
          dom:
            "<'row'<'col-sm-6 hidden-xs'l><'col-sm-6'f>>" +
            "<'row'<'col-sm-12'tr>>" +
            "<'row'<'col-sm-5'i><'col-sm-2'B><'col-sm-5'p>>",
      }
  }
  </script>

  @include('crud::inc.export_buttons')

  <script type="text/javascript">
    jQuery(document).ready(function($) {

      crud.table = $("#crudTable").DataTable(crud.dataTableConfiguration);

      // override ajax error message
      $.fn.dataTable.ext.errMode = 'none';
      $('#crudTable').on('error.dt', function(e, settings, techNote, message) {
          new PNotify({
              type: "error",
              title: "{{ trans('backpack::crud.ajax_error_title') }}",
              text: "{{ trans('backpack::crud.ajax_error_text') }}"
          });
      });

      // make sure AJAX requests include XSRF token
      $.ajaxPrefilter(function(options, originalOptions, xhr) {
          var token = $('meta[name="csrf_token"]').attr('content');

          if (token) {
                return xhr.setRequestHeader('X-XSRF-TOKEN', token);
          }
      });

      // on DataTable draw event run all functions in the queue
      // (eg. delete and details_row buttons add functions to this queue)
      $('#crudTable').on( 'draw.dt',   function () {
         crud.functionsToRunOnDataTablesDrawEvent.forEach(function(functionName) {
            // console.log(functionName);
            crud.executeFunctionByName(functionName);
         });
      } ).dataTable();

      // when columns are hidden by reponsive plugin,
      // the table should have the has-hidden-columns class
      crud.table.on( 'responsive-resize', function ( e, datatable, columns ) {
          if (crud.table.responsive.hasHidden()) {
            $("#crudTable").removeClass('has-hidden-columns').addClass('has-hidden-columns');
           } else {
            $("#crudTable").removeClass('has-hidden-columns');
           }
      } );

    });
  </script>

  @include('crud::inc.details_row_logic')
