<section id="branches" class="content-section">
    <h2 class="section-title">Our Branches</h2>
    <div class="section-content">
        @if($school->branches && $school->branches->count() > 0)
            <div class="branches-list">
                @foreach($school->branches as $branch)
                    <div class="branch-item">
                        <h4 class="branch-name">{{ $branch->name }}</h4>
                        <div class="branch-details">
                            <p class="branch-address">
                                <i class="fas fa-map-marker-alt"></i>
                                {{ $branch->address }}, {{ $branch->city }}
                            </p>
                            @if($branch->contact_number)
                                <p class="branch-phone">
                                    <i class="fas fa-phone"></i>
                                    {{ $branch->contact_number }}
                                </p>
                            @endif
                            @if($branch->branch_head_name)
                                <p class="branch-head">
                                    <i class="fas fa-user-tie"></i>
                                    {{ $branch->branch_head_name }}
                                </p>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <p class="no-content">No additional branches information available.</p>
        @endif
    </div>
</section>
