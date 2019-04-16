
@extends('layout.hf_dboard')

  <!-- stylesheet -->
@section('stylesheet')
  <link rel="stylesheet" href="{{ asset('/css/a_style.css') }}">
@endsection



@section('content')





<div class="container">
  <h2>Shipment Request</h2>
  
  <table class="table">
    <thead class="thead-dark">
      <tr>
        <th>id</th>
        <th>Description</th>
        <th>Accept</th>
        <th>Reject</th>
      </tr>
    </thead>
    <tbody>
      <tr>
        <td>John</td>
        <td>Doe</td>
        <td><button class="btn btn-success" >Accept</button></td>
        <td><button class="btn btn-danger" >Reject</button></td>
      </tr>
      <tr>
        <td>John</td>
        <td>Doe</td>
        <td>john@example.com</td>
        <td>john@example.com</td>
      </tr>
      <tr>
        <td>John</td>
        <td>Doe</td>
        <td>john@example.com</td>
        <td>john@example.com</td>
      </tr>
    </tbody>
  </table>





  @endsection

@section('script')
<!-- main js -->
    <script src="{{ asset('js/a_main.js') }}" ></script>

@endsection
