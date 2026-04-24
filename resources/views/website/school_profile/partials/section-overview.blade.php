<section id="overview" class="content-section active">
    <h2 class="section-title">About Our School</h2>
    <div class="section-content">
        <p class="school-description">
            {{ $school->localized('description') !== '' ? $school->localized('description') : 'No description available for this school.' }}
        </p>

        <div class="quick-facts">
            <h3>Quick Facts</h3>
            <div class="facts-grid">
                @if($school->profile->established_year)
                    <div class="fact-item">
                        <i class="fas fa-calendar"></i>
                        <span>Established: {{ $school->profile->established_year ?? 'N/A' }}</span>
                    </div>
                @endif
                @if($school->profile->student_strength)
                <div class="fact-item">
                    <i class="fas fa-users"></i>
                    <span>Student Strength: {{ $school->profile->student_strength ?? 'N/A' }}</span>
                </div>
                @endif
                @if($school->profile->faculty_count)
                <div class="fact-item">
                    <i class="fas fa-chalkboard-teacher"></i>
                    <span>Faculty: {{ $school->profile->faculty_count ?? 'N/A' }} teachers</span>
                </div>
                @endif
                @if($school->profile->campus_size)
                <div class="fact-item">
                    <i class="fas fa-building"></i>
                    <span>Campus Size: {{ $school->profile->campus_size ?? 'N/A' }}</span>
                </div>
                @endif

                @if($school->profile && $school->profile->quick_facts)
                    @php
                        $quickFacts = json_decode($school->profile->quick_facts, true);
                    @endphp

                    @foreach($quickFacts as $key => $value)
                        @if(!in_array($key, ['established_year', 'student_strength', 'faculty_count', 'campus_size']))
                            <div class="fact-item">
                                <i class="fas fa-check-circle"></i>
                                <span>{{ ucfirst(str_replace('_', ' ', $key)) }}: {{ $value }}</span>
                            </div>
                        @endif
                    @endforeach
                @endif
            </div>
        </div>
    </div>
</section>
