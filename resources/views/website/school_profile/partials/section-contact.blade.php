<section id="contact" class="content-section">
    <h2 class="section-title">Contact Us</h2>
    <div class="section-content">
        <div class="contact-form-section">
            <form id="contactForm" class="contact-form" action="{{ route('contact.inquiry.store') }}" method="POST">
                @csrf
                <input type="hidden" name="school_id" value="{{ $school->id }}">

                <div class="form-row">
                    <div class="form-group">
                        <label for="name">Full Name *</label>
                        <input type="text" id="name" name="name" required>
                    </div>
                    <div class="form-group">
                        <label for="email">Email Address *</label>
                        <input type="email" id="email" name="email" required>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="phone">Phone Number</label>
                        <input type="tel" id="phone" name="phone">
                    </div>
                    <div class="form-group">
                        <label for="subject">Subject *</label>
                        <select id="subject" name="subject" required>
                            <option value="">Select a subject</option>
                            <option value="admission">Admission Inquiry</option>
                            <option value="tour">Schedule a Tour</option>
                            <option value="general">General Information</option>
                            <option value="feedback">Feedback</option>
                            <option value="other">Other</option>
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label for="message">Message *</label>
                    <textarea id="message" name="message" rows="5" placeholder="Please enter your message here..." required></textarea>
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn-submit">
                        <i class="fas fa-paper-plane"></i>
                        Send Message
                    </button>
                </div>
            </form>
        </div>
    </div>
</section>
