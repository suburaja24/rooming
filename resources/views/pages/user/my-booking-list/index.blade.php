@extends('layouts.main')

@section('title', 'My Booking List - ROOMING')

@section('header-title', 'My Booking List')
    
@section('breadcrumbs')
  <div class="breadcrumb-item"><a href="#">Transaksi</a></div>
  <div class="breadcrumb-item active">My Booking List</div>
@endsection

@section('section-title', 'My Booking List')
    
@section('section-lead')
  Berikut ini adalah daftar seluruh booking yang pernah kamu buat.
@endsection

@section('content')

  @component('components.datatables-full-width')

    @slot('add_button')
      <a href="{{ route('my-booking-list.create') }}" class="btn btn-primary"><i class="fas fa-plus"></i>&nbsp;Booking</a>
    @endslot
    
    @slot('table_id', 'my-booking-list-table')

    @slot('table_header')
      <tr>
        <th>#</th>
        <th>Foto</th>
        <th>Ruangan</th>
        <th>Tanggal</th>
        <th>Waktu Mulai</th>
        <th>Waktu Selesai</th>
        <th>Keperluan</th> 
        <th>Status</th> 
      </tr>
    @endslot
      
  @endcomponent

@endsection

@push('after-script')
  <script src="{{ asset('theme/datatables/sorting/enum.js') }}"></script>

  <script>
  $(document).ready(function() {
    $.fn.dataTable.enum( [ 'PENDING', 'DIBOOKING', 'DITOLAK', 'SELESAI', 'BATAL' ] );

    $('#my-booking-list-table').DataTable({
      processing: true,
      serverSide: true,
      ajax: '{{ route('my-booking-list.json') }}',
      columns: [
      {
        name: 'DT_RowIndex',
        data: 'DT_RowIndex',
        orderable: false, 
        searchable: false
      },
      {
        name: 'room.photo',
        data: 'room_status.room.photo',
        render: function ( data, type, row ) {
          if(data != null) {
            return `<div class="gallery gallery-fw">`
              + `<a href="{{ asset('storage/${data}') }}" data-toggle="lightbox">`
              + `<img src="{{ asset('storage/${data}') }}" class="img-fluid">`
              + `</a>`
            + '</div>';
          } else {
            return '-'
          }
        }
      },
      {
        name: 'room.name',
        data: 'room_status.room.name',
        render: function ( data, type, row ) {
          var result = data;

          var is_touch_device = 'ontouchstart' in window || navigator.msMaxTouchPoints;

          if (is_touch_device) {
            result += '<div>';
          } else {
            result += '<div class="table-links">';
          }

          if(row.status === 'PENDING' || row.status === 'DITOLAK') {
            result += ' <a href="javascript:;" data-id="'+row.id+'" '
            + ' data-title="Setujui"'
            + ' data-body="Yakin setujui booking ini?"'
            + ' data-value="1"'
            + ' class="text-primary"'
            + ' id="acc-btn"'
            + ' name="acc-btn">Setujui'
            + ' </a>';
          }

          if(row.status === 'PENDING'){
            result += ' <div class="bullet"></div>';
          }

          if(row.status === 'PENDING' || row.status === 'DISETUJUI') {
            result += ' <a href="javascript:;" data-id="'+row.id+'" '
            + ' data-title="Tolak"'
            + ' data-body="Yakin tolak booking ini?"'
            + ' data-value="0"'
            + ' class="text-danger"'
            + ' id="deny-btn"'
            + ' name="deny-btn">Tolak'
            + ' </a>';
          }

          + '</div>';

          return result;
            
        }
      },
      {
        name: 'date',
        data: 'date',
      },
      {
        name: 'start_time',
        data: 'start_time',
      },
      {
        name: 'end_time',
        data: 'end_time',
      },
      {
        name: 'purpose',
        data: 'purpose',
      },
      {
        name: 'status',
        data: 'status',
        render: function ( data, type, row ) {
          var result = `<span class="badge badge-`;

          if(data === 'PENDING') 
            result += `info`;
          else if(data === 'DISETUJUI')
            result += `primary`;
          else if(data === 'DITOLAK')
            result += `danger`;
          else if(data === 'BATAL')
            result += `warning`;
          else if(data === 'SELESAI')
            result += `success`;

          result += `">${data}</span>`;

          return result;
        } 
      },
    ],
    });

    $(document).on('click', '#acc-btn, #deny-btn', function() {
      var id    = $(this).data('id'); 
      var title = $(this).data('title');
      var body  = $(this).data('body');
      var value = $(this).data('value');

      var submit_btn_class = (value === 1) ? 'btn btn-primary' : 'btn btn-danger';

      $('.modal-title').html(title);
      $('.modal-body').html(body);
      $('#confirm-form').attr('action', '/my-booking-list/'+id+'/update/'+value);
      $('#confirm-form').attr('method', 'POST');
      $('#submit-btn').attr('class', submit_btn_class);
      $('#lara-method').attr('value', 'put');
      $('#confirm-modal').modal('show');
    });

    $(document).on('click', '[data-toggle="lightbox"]', function(event) {
        event.preventDefault();
        $(this).ekkoLightbox();
    });
  });

</script>

@include('includes.lightbox')

@include('includes.notification')

@include('includes.confirm-modal')

@endpush