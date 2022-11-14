<form action="{{ route('admin.users.update', $user->id) }}" method="POST">
@csrf
@method('PUT')
<div class="card-body">                                                                                            
    <div class="form-group">
        <label>Email*</label>
        <input class="form-control form-control-sm @error('email') is-invalid @enderror" name="email" value="{{ $user->email }}" autocomplete="off">
        @error('email')
        <div class="text-danger small">{{ $message }}</div>
        @enderror
    </div>   
    <div class="form-group">
        <label>Password</label>
        <input type="password" class="form-control form-control-sm" name="password" value="" autocomplete="off">
    </div>                  
    <div class="form-group">
        <label>Name*</label>
        <input class="form-control form-control-sm @error('name') is-invalid @enderror" name="name" value="{{ $user->name }}" autocomplete="off">
        @error('name')
        <div class="text-danger small">{{ $message }}</div>
        @enderror
    </div>           
    <div class="form-group">
        <label>Second Name*</label>
        <input class="form-control form-control-sm @error('second_name') is-invalid @enderror" name="second_name" value="{{ $userProfile->second_name }}" autocomplete="off">
        @error('second_name')
        <div class="text-danger small">{{ $message }}</div>
        @enderror
    </div>
    <div class="form-group">
        <label>Phone*</label>
        <input class="form-control form-control-sm form-control-sm @error('phone') is-invalid @enderror" name="phone" value="{{ $userProfile->phone }}" autocomplete="off">
        @error('phone')
        <div class="text-danger small">{{ $message }}</div>
        @enderror
    </div>
    <div class="form-group">
        <label>Telegram</label>
        <input class="form-control form-control-sm" name="telegram" value="{{ $userProfile->telegram }}" autocomplete="off">
    </div>                    
    <div class="form-check">
        <input type="checkbox" {{ $user->is_active ? 'checked' : '' }} class="form-check-input" id="isActive" name="is_active" value="1">
        <label class="form-check-label" for="isActive">Active</label>
    </div>                    
</div>
<!-- /.card-body -->

<div class="card-footer">
    <button type="submit" class="btn btn-primary">Save</button>
</div>
</form>            