@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <strong>Form Aplikasi</strong>
                </div>

                @if (session('message'))
                    <div class="card-body">
                        <div class="alert alert-{{ session('type') }}" role="alert">
                            {{ session('message') }}
                        </div>
                    </div>
                    <div class="card-footer">
                        <a href="{{ route('create') }}" class="btn btn-dark">Kembali</a>
                    </div>
                @else
                    <form action="{{ route('store') }}" method="post" enctype="multipart/form-data">
                        @csrf
                        <div class="card-body">
                            <div class="form-group">
                                <label for="name">Nama</label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ isset($candidate) ? $candidate->name : old('name') }}">
                                <small id="nameHelp" class="form-text @error('name') invalid-feedback @enderror">{{ $errors->first('name') ?? '' }}</small>
                            </div>
                            <div class="form-group">
                                <label for="gender">Jenis Kelamin</label><br>
                                <div class="form-check form-check-inline">
                                    <input type="radio" class="form-check-input @error('gender') is-invalid @enderror" name="gender" value="M" @if(old('gender') == 'M') checked @endif>
                                    <label class="form-check-label">Pria</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input type="radio" class="form-check-input @error('gender') is-invalid @enderror" name="gender" value="F" @if(old('gender') == 'F') checked @endif>
                                    <label class="form-check-label">Wanita</label>
                                </div>
                                <small id="genderHelp" class="form-text @error('gender') invalid-feedback @enderror">{{ $errors->first('gender') ?? '' }}</small>
                            </div>
                            <div class="form-group">
                                <label for="email">Email</label>
                                <input type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ isset($candidate) ? $candidate->email : old('email') }}">
                                <small id="emailHelp" class="form-text @error('email') invalid-feedback @enderror">{{ $errors->first('email') ?? '' }}</small>
                            </div>
                            <div class="form-group">
                                <label for="phone">No. Telepon</label>
                                <input type="text" class="form-control @error('phone') is-invalid @enderror" name="phone" value="{{ isset($candidate) ? $candidate->phone : old('phone') }}" placeholder="08xxxxxxxxxx">
                                <small id="phoneHelp" class="form-text @error('phone') invalid-feedback @enderror">{{ $errors->first('phone') ?? '' }}</small>
                            </div>
                            <div class="form-group">
                                <label for="file">PDF</label>
                                <br>
                                <input type="file" class="form-control @error('file') is-invalid @enderror" name="file" accept="application/pdf">
                                <small id="fileHelp" class="form-text @error('file') invalid-feedback @enderror">{{ $errors->first('file') ?? '' }}</small>
                            </div>
                        </div>
                        <div class="card-footer">
                            <button type="submit" class="btn btn-primary">Submit</button>
                        </div>
                    </form>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
