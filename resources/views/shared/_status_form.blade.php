<form action="{{ route('statuses.store') }}" method="POST">
  @include('shared._errors')
  {{ csrf_field() }}
  <textarea class="form-control" rows="3" placeholder="聊聊新鮮事..." name="content">{{ old('content') }}</textarea>
  <div class="text-right">
      <button type="submit" class="btn btn-primary mt-3">發佈</button>
  </div>
</form>