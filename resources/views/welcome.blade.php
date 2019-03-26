<?php 

 $pic = "img/cat1.jpg";
// $pic = 'img/cat1.jpg';

 ?>

@extends('layout.hf')

@section('content')
            
        <div id="app">
            
            <example-component></example-component>

            

        </div>

		

		<img src="{{ asset($pic) }}" alt="">

        <h1>hellow wordl</h1>
    

@endsection