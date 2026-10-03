@props(["count" => 0, "title" => "Products"])
<li class="mb-6">
  <div class="d-flex align-items-center">
    <div class="badge bg-label-secondary text-body p-2 me-4 rounded">
      <i class="icon-base ti tabler-shadow icon-md"></i>
    </div>
    <div class="d-flex justify-content-between w-100 flex-wrap gap-2">
      <div class="me-2">
        <h6 class="mb-0">{{ $title }}</h6>
      </div>
      <div class="d-flex align-items-center">
        <p class="mb-0">{{ $count }}</p>
      </div>
    </div>
  </div>
</li>