<x-layouts.app :title="$editMode ? 'Edit Social' : 'Add Social'">
  <div class="page-content">
    <div class="container-fluid">

      <div class="row">
        <div class="col-12">
          <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0 font-size-18">Social Setting</h4>
          </div>
        </div>
      </div>

      <div class="row">
        <div class="col-xl-12">
          <div class="card">
            <div class="card-body">

              <h4 class="mb-4">{{ $editMode ? 'Update' : 'Add' }} Social Link</h4>

              @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
              @endif

              <form action="{{ $editMode ? route('social.update', $editData->id) : route('social.store') }}" method="POST" enctype="multipart/form-data" class="row g-3">
                @csrf
                <div class="col-md-4">
                  <label>Social Name</label>
                  <input type="text" name="social_name" class="form-control" value="{{ old('social_name', optional($editData)->social_name) }}" required>
                </div>

                <div class="col-md-4">
                  <label>Social Link</label>
                  <input type="url" name="social_link" class="form-control" value="{{ old('social_link', optional($editData)->social_link) }}" required>
                </div>

                <div class="col-md-4">
                  <label>Social Image</label>
                  <input type="file" name="social_image" class="form-control">
                  @if(optional($editData)->social_image)
                    <img src="{{ asset('admin_uploads/' . optional($editData)->social_image) }}" width="50" class="mt-2">
                  @endif
                </div>

                <div class="col-md-12">
                  <button type="submit" class="btn btn-primary">
                    {{ $editMode ? 'Update Link' : 'Add Link' }}
                  </button>
                </div>
              </form>

              @if ($errors->any())
                  <div class="alert alert-danger mt-3">
                      <ul>
                          @foreach ($errors->all() as $error)
                              <li>{{ $error }}</li>
                          @endforeach
                      </ul>
                  </div>
              @endif

              @if(!$editMode)
              <h5 class="mt-5 mb-3">All Social Links</h5>

              <table class="table table-bordered">
                <thead>
                  <tr>
                    <th>#</th>
                    <th>Image</th>
                    <th>Name</th>
                    <th>Link</th>
                    <th>Status</th>
                    <th>Action</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach($socials as $key => $item)
                    <tr>
                      <td>{{ $key + 1 }}</td>
                      <td>
                        @if($item->social_image)
                          <img src="{{ asset('admin_uploads/' . $item->social_image) }}" width="40">
                        @endif
                      </td>
                      <td>{{ $item->social_name }}</td>
                      <td><a href="{{ $item->social_link }}" target="_blank">{{ $item->social_link }}</a></td>
                      <td>
                        <span class="badge {{ $item->status == 'active' ? 'bg-success' : 'bg-danger' }}">
                          {{ ucfirst($item->status) }}
                        </span>
                      </td>
                      <td>
                        <form action="{{ route('social.toggle', $item->id) }}" method="POST" class="d-inline">
                          @csrf
                          <button type="submit" class="btn btn-sm {{ $item->status == 'active' ? 'btn-danger' : 'btn-success' }}" title="Toggle Status">
                            <i class="bx {{ $item->status == 'active' ? 'bx-x-circle' : 'bx-check-circle' }}"></i>
                          </button>
                        </form>

                        <a href="{{ route('social.edit', $item->id) }}" class="btn btn-sm btn-warning ms-1" title="Edit">
                            <i class="bx bx-edit"></i>
                        </a>
                      </td>
                    </tr>
                  @endforeach
                </tbody>
              </table>
              @endif

            </div>
          </div>
        </div>
      </div>

    </div>
  </div>

</x-layouts.app>
