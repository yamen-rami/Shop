<x-main-layout>
  <x-slot:title>
    Dashborad
  </x-slot:title>
  <div class="row g-6">
    <!-- Source Visit -->
    <div class="col-xxl-4 col-md-6 col-12">
      <div class="card h-100">
        <div class="card-header d-flex justify-content-between">
          <div class="card-title mb-0">
            <h5 class="mb-1">Details</h5>
          </div>
          <div class="dropdown">
            <button class="btn btn-text-secondary rounded-pill text-body-secondary border-0 p-2 me-n1" type="button"
              id="sourceVisits" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
              <i class="icon-base ti tabler-dots-vertical icon-md text-body-secondary"></i>
            </button>
            <div class="dropdown-menu dropdown-menu-end" aria-labelledby="sourceVisits">
              <a class="dropdown-item" href="javascript:void(0);">Refresh</a>
              <a class="dropdown-item" href="javascript:void(0);">Download</a>
              <a class="dropdown-item" href="javascript:void(0);">View All</a>
            </div>
          </div>
        </div>
        <div class="card-body">
          <ul class="list-unstyled mb-0">
            <x-counts title="Products" :count="$productCount" />
            <x-counts title="Tags" :count="$tagsCount" />
            <x-counts title="Contacts" :count="$conatctCount" />
            <x-counts title="Companies" :count="$companiesCount" />
            <x-counts title="Orders" :count="$orderCount" />
            <x-counts title="Active Offers" :count="$ActiveOffers" />
            <x-counts title="Products Offers" :count="$productsOffers" />
            <x-counts title="Coupon Offers" :count="$couponOffers" />
            <x-counts title="Products Offers" :count="$productsOffers" />
            <x-counts title="Category Offers" :count="$categoryOffers" />
          </ul>
        </div>
      </div>
    </div>
    <!--/ Source Visit -->

    <!-- Projects table -->
    
    <!--/ Projects table -->
  </div>
</x-main-layout>