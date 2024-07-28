@extends('layouts/contentNavbarLayout')

@section('title', 'Items')

@section('content')
  <h4 class="py-3 mb-4">
    <span class="text-muted fw-light">Tables /</span> Basic Tables
  </h4>

  <!-- Basic Bootstrap Table -->
  <div class="card">
    @if (isset($message_error))
      <div class="m-4 alert alert-danger alert-dismissible" role="alert">
        {{ __($message_error) }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
        </button>
      </div>
    @endif

    @if (isset($message_success))
      <div class="m-4 alert alert-success alert-dismissible" role="alert">
        {{ __($message_success) }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
        </button>
      </div>
    @endif

    {{ Form::open(['url' => '/items', 'class' => 'm-4']) }}
    {{ Form::text('url', null, ['class' => 'form-control', 'placeholder' => 'https://jp.mercari.com/item/m65653219614']) }}
    {{ Form::button('Add', ['type' => 'submit', 'class' => 'my-2 w-25 mx-auto d-block btn btn-primary']) }}

    {{ Form::close() }}

    <h5 class="card-header">List urls</h5>
    <div class="table-responsive text-nowrap">
      <table class="table">
        <thead>
        <tr>
          <th>ID</th>
          <th>IMAGE</th>
          <th>NAME</th>
          <th>PRICE</th>
          <th>SHIPPING DURATION</th>
          <th>STATUS</th>
          <th>IMAGES</th>
          <th>ACTION</th>
        </tr>
        </thead>
        <tbody class="table-border-bottom-0">

        @foreach($items as $item)
          <tr>
            <td>{{$item->id}}</td>
            <td>
              <a href="https://jp.mercari.com/item/{{$item->mer_id}}" target="_blank">
                <img src="{{$item->thumbnail}}" class="img-thumbnail" width="120" height="120" />
              </a>
            </td>
            <td class="w-25">{{$item->name}}</td>
            <td>{{$item->price}}</td>
            <td>{{$item->shipping_duration}}</td>
            @if($item->status == "on_sale")
              <td><span class="badge bg-success">On sale</span></td>
            @elseif($item->status == "sold_out")
              <td><span class="badge bg-danger">Sold out</span></td>
            @else
              <td><span class="badge bg-warning">{{$item->status}}</span></td>
            @endif

            <td>
              <ul class="list-unstyled users-list m-0 avatar-group d-flex align-items-center">
                @foreach($item->photos()->limit(3)->get() as $photo)
                  <li data-bs-toggle="tooltip" data-popup="tooltip-custom" data-bs-placement="top"
                      class="avatar avatar-size-xl pull-up">
                    <img src="{{$photo->url}}" class="rounded-circle">
                  </li>
                @endforeach

              </ul>
              <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                      data-bs-target="#imagePreviewModal{{$item->id}}">
                Preview
              </button>

              @php
                $photos = [];
                foreach ($item->photos()->get() as $photo) {
                    $photos[] = $photo->url;
                }
              @endphp

              <div class="modal fade" id="imagePreviewModal{{$item->id}}" tabindex="-1"
                   aria-labelledby="imagePreviewModal{{$item->id}}" style="display: none;" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
                  <div class="modal-content">
                    <div class="modal-header">
                      <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                      <div id="carouselT{{$item->id}}" class="carousel slide"
                           data-bs-ride="carousel">
                        <div class="carousel-indicators">
                          @foreach($photos as $index=>$photo)
                            @if($index == 0)
                              <button type="button" data-bs-target="#carouselT{{$item->id}}" class="active"
                                      data-bs-slide-to="{{$index}}" aria-label="Slide {{$index}}"></button>
                            @else
                              <button type="button" data-bs-target="#carouselT{{$item->id}}"
                                      data-bs-slide-to="{{$index}}" aria-label="Slide {{$index}}"></button>
                            @endif
                          @endforeach
                        </div>
                        <div class="carousel-inner">
                          @foreach($photos as $index=>$photo)
                            @if($index == 0)
                              <div class="carousel-item active">
                            @else
                              <div class="carousel-item">
                            @endif
                              <img class="d-block w-100" src="{{$photo}}" alt="Slide {{$index}}">
                            </div>
                          @endforeach

                        </div>

                        <a class="carousel-control-prev" href="#carouselT{{$item->id}}" role="button" data-bs-slide="prev">
                          <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                          <span class="visually-hidden">Previous</span>
                        </a>
                        <a class="carousel-control-next" href="#carouselT{{$item->id}}" role="button" data-bs-slide="next">
                          <span class="carousel-control-next-icon" aria-hidden="true"></span>
                          <span class="visually-hidden">Next</span>
                        </a>
                      </div>
                    </div>
                    <div class="modal-footer">
                      <button type="button" class="btn btn-secondary mx-auto" data-bs-dismiss="modal">Close</button>
                    </div>
                  </div>
                </div>
              </div>
            </td>

            <td>
              <div class="dropdown">
                <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown"><i
                    class="bx bx-dots-vertical-rounded"></i></button>
                <div class="dropdown-menu">
                  {{ Form::open(['url' => '/items/' . $item->id, 'method' => 'delete', 'class' => 'm-4 dropdown-item']) }}
                  {{ Form::button('Delete', ['type' => 'submit', 'class' => 'btn btn-danger mx-auto']) }}
                  {{ Form::close() }}
                </div>
              </div>
            </td>
          </tr>
        @endforeach

        </tbody>
      </table>
    </div>
  </div>
@endsection
