@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="page-header">
                <h1>
                    <span>Goods Gallery</span>
                    <button type="button" class="btn btn-default btn-lg pull-right">Add Goods</button>
                </h1>
            </div>

            @foreach ($goods as $good)
                {{ App\Picture::$imageBasePath }}
                <div class="col-lg-3 col-md-4 col-xs-6 thumb">
                    <a class="thumbnail" href="#">
                        <img class="img-responsive" src="{{ App\Picture::getElementById($good['good_picture']) }}" alt="{{ $good['title'] }}" title="{{ $good['title'] }}">
                    </a>
                </div>
            @endforeach
        </div>
        {{ $goods->links() }}
    </div>
@endsection