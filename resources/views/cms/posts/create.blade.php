@extends('layouts.cms')

@section('content')
<div class="page-inner">
    <div class="page-header">
        <h4 class="page-title"> Posts </h4>
        <ul class="breadcrumbs">
            <li class="nav-home">
                <a href="#">
                    <i class="flaticon-home"></i>
                </a>
            </li>
            <li class="separator">
                <i class="flaticon-right-arrow"></i>
            </li>
            <li class="nav-item">
                <a href="#">Posts</a>
            </li>
            <li class="separator">
                <i class="flaticon-right-arrow"></i>
            </li>
            <li class="nav-item">
                <a href="#">Create</a>
            </li>
        </ul>
    </div>
    <div class="row">


        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex align-items-center">
                        <h4 class="card-title">Add Record</h4>
                        <a href="{{ route('posts.index') }}" class="btn btn-primary btn-round ml-auto" >
                            <i class="flaticon-left-arrow-4 mr-2"></i>
                            View Records
                        </a> 
                    </div>
                </div>
                <div class="card-body">

                    <!-- form -->
                    @include('cms.helpers.partials.feedback')
                    <form id="posts-create" 
                            action="@if(isset($post->id))  
                            {{ route('posts.update', ['post' => $post->id]) }}
                            @else {{ route('posts.store' ) }} @endif"  
                            method="post" 
                            enctype="multipart/form-data">

                        @csrf
                        @if(isset($post->id))
                        @method('PUT')
                        @endif

                        <div class="form-group">
                            <label for="title">Title</label>
                            <input id="title" type="text" class="form-control form-control" name="title" placeholder="Enter title ..." value="{{ $post->title ?? '' }}"  />
                            @error('title') <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="category_id">Category</label>
                            <select name="category_id" id="product_category" class="form-control form-control">
                                @foreach($post_categories as $category)
                                    <option value="{{ $category->id }}"> {{ $category->name }} </option>
                                    <!-- <option value=""> -- No item -- </option>  -->
                                @endforeach
                            </select>
                            @error('category_id') <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="content">Content</label>
                            <textarea name="content" id="content" class="form-control form-control" placeholder="Enter content ...">{{ $post->content ?? '' }}</textarea>
                            @error('content') <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group form-floating-label">
                            <label for="featured_img" class=""> Featured Image </label>
                            <input id="featured_img" type="file" class="form-control input-border-bottom" name="featured_img" />
                            <img id="blah" src="#" alt="your image" height="50px"/>
                        </div>

                    </div>  




                        <div class="card">
                            <div class="form-group form-floating-label">
                                <button class="btn btn-success btn-round float-right">Submit</button>
                            </div>
                        </div>
                    </form>
                    <!-- End form -->

                </div>
            </div>
        </div>
    </div>
</div>
<!-- .page-inner -->
 
@endsection


@push('scripts')
<script>
    $(document).ready(function() {
        $("#featured_img").change(function() {
			readURL(this);
		});

        $('#createallcb').change(function () {
            $('.perm_check').prop('checked', $(this).prop('checked'));
        });
    });


    
</script>

@endpush