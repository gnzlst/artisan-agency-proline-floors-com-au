@extends('layouts.app')

@section('content')
  <div class="container mx-auto py-8">
    <h1 class="text-3xl font-bold mb-4">{{ the_title() }}</h1>
    <div class="flex flex-col md:flex-row gap-8">
      <div class="md:w-1/2">
        @php(do_action('woocommerce_before_single_product'))
      </div>
      <div class="md:w-1/2">
        @php(do_action('woocommerce_single_product_summary'))
      </div>
    </div>
  </div>
@endsection
